<?php
namespace Xelm2\Cafe\Model;

use Xelm2\Cafe\Api\CafeManagementInterface;
use Xelm2\Cafe\Model\CafeTableFactory;
use Xelm2\Cafe\Model\ResourceModel\CafeTableFactory as CafeTableResource;
use Xelm2\Cafe\Model\ResourceModel\CafeTable\CollectionFactory as CafeTableCollection;
use Xelm2\Cafe\Model\CafeCustomerFactory;
use Xelm2\Cafe\Model\ResourceModel\CafeCustomerFactory as CafeCustomerResource;
use Xelm2\Cafe\Model\ResourceModel\CafeCustomer\CollectionFactory as CafeCustomerCollection;
use Xelm2\Cafe\Model\Customer\Session as CustomerSession;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Customer\Model\CustomerFactory as CustomerModel;
use Magento\Framework\Registry;
use Psr\Log\LoggerInterface;

class CafeManagement implements CafeManagementInterface
{    
    /**
     * @var CafeTableFactory $cafeTable
     */
    protected $cafeTable;

    /**
     * @var CafeTableResource $cafeTableResource
     */
    protected $cafeTableResource;

    /**
     * @var CafeTableCollection $cafeTableCollection
     */
    protected $cafeTableCollection;

    /**
     * @var CafeCustomerFactory $cafeCustomer
     */
    protected $cafeCustomer;

    /**
     * @var CafeCustomerResource $cafeCustomerResource
     */
    protected $cafeCustomerResource;

    /**
     * @var CafeCustomerCollection $cafeCustomerCollection
     */
    protected $cafeCustomerCollection;

    /**
     * @var CheckoutSession $checkoutSession
     */
    protected $checkoutSession;

    /**
     * @var CustomerSession $customerSession
     */
    protected $customerSession;

    /**
     * @var Registry $registry
     */
    protected $registry;

    /**
     * @var CustomerModel $customerModel
     */
    protected $customerModel;

    /**
     * @var LoggerInterface $loggerInterface
     */
    protected $loggerInterface;

    protected $cafeCustomerId = null;

    /**
     * __construct
     * @param CafeTableFactory $cafeTable
     * @param CafeTableResource $cafeTableResource
     * @param CafeTableCollection $cafeTableCollection
     * @param CafeCustomerFactory $cafeCustomer
     * @param CafeCustomerResource $cafeCustomerResource
     * @param CafeCustomerCollection $cafeCustomerCollection
     * @param CheckoutSession $checkoutSession
     * 
     * @return void
     */
    public function __construct(
        CafeTableFactory $cafeTable,
        CafeTableResource $cafeTableResource,
        CafeTableCollection $cafeTableCollection,
        CafeCustomerFactory $cafeCustomer,
        CafeCustomerResource $cafeCustomerResource,
        CafeCustomerCollection $cafeCustomerCollection,
        CheckoutSession $checkoutSession,
        Customersession $customerSession,
        Registry $registry,
        CustomerModel $customerModel,
        LoggerInterface $loggerInterface
    )
    {
        $this->cafeTable = $cafeTable;
        $this->cafeTableResource = $cafeTableResource;
        $this->cafeTableCollection = $cafeTableCollection;
        $this->cafeCustomer = $cafeCustomer;
        $this->cafeCustomerResource = $cafeCustomerResource;
        $this->cafeCustomerCollection = $cafeCustomerCollection;
        $this->checkoutSession = $checkoutSession;
        $this->customerSession = $customerSession;
        $this->customerModel = $customerModel;
        $this->registry = $registry;
        $this->loggerInterface = $loggerInterface;
    }

    /**
     * checkInCustomer
     *
     * @param  string $customerId
     * @param  string $tableCode
     * @return mixed
     */
    public function checkInCustomer($customerId, $tableCode)
    {
        try{
            if ($this->occupyTable($tableCode)) {
                $customer = $this->cafeCustomer->create();
                $customer->setData([
                    CafeCustomer::TABLE_CODE => $tableCode,
                    CafeCustomer::CUSTOMER_ID => $customerId,
                    CafeCustomer::STATUS => 1
                ]);
    
                $customer->save();
                $this->cafeCustomerId = $customer->getId();
                $this->customerSession->setRequiredSession($customer->getId(), $tableCode);

                return true;
            } else {
                return false;
            }
            
        }catch(\Exception $e){
            $this->loggerInterface->critical($e->getMessage());
            return false;
        }
    }
    
    /**
     * checkoutCustomer
     *
     * @param  int|string $customerId
     * @param  string $table
     * @return void
     */
    public function checkoutCustomer($customerId, $table)
    {
        $cafeTable = $this->cafeTable->create()->loadByTableCode($table);
        if($cafeTable){
            $cafeTable->vacantTable();
            $cafeTable->save();
            $cafeCustomer = $this->cafeCustomer->create()->loadByCustomerId($customerId);
            $cafeCustomer->checkout()->save();
        }
    }

    /**
     * occupyTable
     *
     * @param  string $table
     * @return bool
     */
    public function occupyTable($table)
    {
        try{
            $cafeTable = $this->cafeTable->create()->loadByTableCode($table);
            $cafeTable->occupyTable();
            $cafeTable->save();

            return true;
        }catch(\Exception $e){
            throw new \Exception($e->getMessage());
        }
    }
    
    /**
     * deleteCustomer
     *
     * @param  mixed $customerId
     * @return void
     */
    public function deleteCustomer($customerId = null)
    {
        $this->registry->register('isSecureArea', true);
        $customer = $this->customerSession->getCustomer();
        $this->customerSession->logout();

        if($customerId){
            $customer = $this->customerModel->create()->load($customerId);
        }

        $customer->delete();
    }
    
    /**
     * generateCheckInID
     *
     * @param  string|int $customerId
     * @return string
     */
    public function generateCheckInID($customerId)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';

        for ($i = 0; $i < 5; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

		return $customerId . "_" . date("YMD") . "_" . $randomString;
    }
}