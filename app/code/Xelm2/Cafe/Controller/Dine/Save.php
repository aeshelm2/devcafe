<?php
namespace Xelm2\Cafe\Controller\Dine;

use Xelm2\Cafe\Api\CustomerInterface as CafeCustomer;

class Save extends \Magento\Framework\App\Action\Action
{
    const GENERATE_EMAIL_LENGTH = 8;
    const DEFAULT_PASSWORD = "MyNewPass123!@#";
    protected $cafeCustomerId = 0;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        protected \Magento\Checkout\Model\Session $checkoutSession,
        protected \Magento\Customer\Api\Data\CustomerInterfaceFactory $customerInterface,
        protected \Magento\Framework\Encryption\EncryptorInterface $encryptorInterface,
        protected \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
        protected \Magento\Customer\Model\CustomerFactory $customerFactory,
        protected \Magento\Customer\Model\AddressFactory $addressFactory,
        protected \Magento\Store\Model\StoreManagerInterface $storeManager,
        protected \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator,
        protected \Magento\Customer\Model\Session $customerSession,
        protected \Xelm2\Cafe\Model\CafeTableFactory $cafeTable,
        protected \Magento\Framework\Registry $registry,
        protected \Magento\Framework\Data\Form\FormKey $formKey,
        protected \Xelm2\Cafe\Model\CafeCustomerFactory $cafeCustomer,
        protected \Xelm2\Cafe\Model\CafeManagement $cafeManagement,
        protected \Magento\Customer\Api\Data\AddressInterface $addressDataFactory,
        protected \Magento\Customer\Api\AddressRepositoryInterface $addressRepository,
        protected \Magento\Customer\Model\AuthenticationInterface $authenticate
    )
    {
        parent::__construct($context);   
    }

    public function execute()
    {
        $redirect = $this->resultRedirectFactory->create();
        if(!$this->formKeyValidator->validate($this->getRequest())){
            $this->messageManager->addErrorMessage(__("Invalid Form Key!"));
            return $redirect->setPath($this->_redirect->getRefererUrl());
        }

        if($this->getRequest()->isPost()){
            $tableCode = $this->getRequest()->getParam('table');
            if(!empty($tableCode)){
                // Check in Customer
                $customerId = $this->createCustomer();
                if($customerId && $this->cafeManagement->checkInCustomer($customerId, $tableCode)){
                    if(!$this->setCustomerAddress($customerId)){
                        $dashboardUrl = $this->_url->getUrl('customer/account');
                        $message = 'Unable to save default address. <a href="'.$dashboardUrl.'">You can manually change it here.</a>';
                        $this->messageManager->addWarning(
                            $message
                        );
                    }

                    $this->_eventManager->dispatch(
                        'cafe_login_success',
                        ['table_code' => $tableCode, 'customer_id' => $customerId]
                    );
                    return $redirect->setPath('/');
                }else{
                    $this->cafeManagement->deleteCustomer($customerId);
                    $this->cafeManagement->checkoutCustomer($customerId, $tableCode);
                }
            }
        }

        return $redirect->setPath($this->_redirect->getRefererUrl());
    }
    
    /**
     * createCustomer
     *
     * @return bool|int
     */
    private function createCustomer()
    {
        try {

            $firstname = $this->getRequest()->getParam('firstname');
            $lastname = $this->getRequest()->getParam('lastname');
            $email = $this->getRequest()->getParam('email');
            $sendPasswordToEmail = false;
            if(empty($email)){
                $email = $this->generateEmail();
                $sendPasswordToEmail = true;
            }
            
            $storeManager = $this->storeManager;
            $storeId = $storeManager->getStore()->getId();
            
            $websiteId = $storeManager->getStore($storeId)->getWebsiteId();

            // collect the customer data
            $customer = $this->customerInterface->create();
            $customer->setWebsiteId($websiteId);
            $customer->setEmail($email);
            $customer->setFirstname($firstname);
            $customer->setLastname($lastname);
            $hashedPassword = $this->encryptorInterface->getHash(self::DEFAULT_PASSWORD, true);
        
            $customerResult = $this->customerRepository->save($customer, $hashedPassword);
        
            $customer = $this->customerFactory->create();
            $customer->setWebsiteId($websiteId)->loadByEmail($email);
            
            if($sendPasswordToEmail){
                // Write logic here
            }
            
            $this->customerSession->setCustomerDataAsLoggedIn($customerResult);
            
            return $customer->getId();
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
            return false;
        }
    }
    
    /**
     * generateEmail
     *
     * @return string
     */
    private function generateEmail()
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';

        for ($i = 0; $i < self::GENERATE_EMAIL_LENGTH; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return 'guest-' . $randomString . "@example.com";
    }

    private function setCustomerAddress($customerId)
    {
        try{
            $customer = $this->customerRepository->getById($customerId);
            $address = $this->addressDataFactory;
            $address->setCustomerId($customer->getId())
            ->setFirstname($customer->getFirstName())
            ->setLastname($customer->getLastname())
            ->setCountryId('PH')
            ->setPostcode(6331)
            ->setCity("Sagbayan")
            ->setStreet(['San Agustin',''])
            ->setTelephone('123456789')
            ->setIsDefaultBilling(true)
            ->setIsDefaultShipping(true);
            $savedAddress = $this->addressRepository->save($address);
            return $savedAddress->getId();
        }catch(\Exception $e){
            return false;
        }
        // ->setRegionId()
    }
}