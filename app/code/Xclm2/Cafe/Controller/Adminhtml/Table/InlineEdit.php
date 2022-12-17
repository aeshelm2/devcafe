<?php
namespace Xclm2\Cafe\Controller\Adminhtml\Table;

use Xclm2\Cafe\Api\TableInterface;

class InlineEdit extends \Magento\Backend\App\Action
{
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        protected \Magento\Framework\View\Result\PageFactory $pageFactory,
        protected \Xclm2\Cafe\Model\CafeTableFactory $cafeTable,
        protected \Xclm2\Cafe\Model\CafeCustomerFactory $cafeCustomer,
        protected \Magento\Framework\Api\DataObjectHelper $dataObjectHelper,
        protected \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        protected \Magento\Customer\Model\Session\SessionCleaner $sessionCleaner,
        protected \Xclm2\Cafe\Model\ResourceModel\CafeCustomer\CollectionFactory $cafeCustomerCollection
    )
    {
        parent::__construct($context);
    }

    public function execute()
    {
        $resultJson = $this->resultJsonFactory->create();
        
        $postItems = $this->getRequest()->getParam('items', []);
        if (!($this->getRequest()->getParam('isAjax') && count($postItems))) {
            return $resultJson->setData(
                [
                    'messages' => [
                        __('Please correct the data sent.')
                    ],
                    'error' => true,
                ]
            );
        }
        
        $table = "";
        foreach($postItems as $data){
            $cafeTable = $this->cafeTable->create();
            $cafeTable->setData($data);
            $cafeTable->save();
            if($data['table_status'] == TableInterface::TABLE_STATUS_VACANT){
                $table = $data['table_code'];
            }
        }
        
        if($table){
            $this->logoutCustomer($table);
        }

        return $resultJson->setData([
            'messages' => [
                __('Updated')
            ],
            'error' => false,
        ]) ;
    }

    private function logoutCustomer($tableCode)
    {
        try{
            $dinedCustomers = $this->cafeCustomerCollection->create();
            $dinedCustomers->getDinedCustomerByTableCode($tableCode);
            foreach($dinedCustomers as $cafe){
                $this->sessionCleaner->clearFor($cafe->getCustomerId());
            }

            $status = $this->cafeCustomerCollection->create()->setStatus(0, $tableCode, 'admin');
        }catch(\Exception $e){
            $this->messageManager->addExceptionMessage($e, __("Something went wrong."));
        }
    }
}