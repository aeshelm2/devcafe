<?php
namespace Xelm2\Cafe\Controller\Dine;

use Xelm2\Cafe\Api\TableInterface;

class Index extends \Magento\Framework\App\Action\Action
{
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        protected \Magento\Framework\View\Result\PageFactory $pageFactory,
        protected \Magento\Framework\Encryption\Encryptor $encryptor,
        protected \Magento\Customer\Model\Session $session,
        protected \Xelm2\Cafe\Model\ResourceModel\CafeTable $cafeTable,
        protected \Xelm2\Cafe\Model\CafeTableFactory $cafeTableModel
    )
    {
        parent::__construct($context);   
    }

    public function execute()
    {
        $redirect = $this->resultRedirectFactory->create();
        $table = $this->getRequest()->getParam('table');
        if($this->session->isLoggedIn() or empty($table)){
            $this->messageManager->addErrorMessage(__("Please scan the qrcode."));
            return $redirect->setPath('/');
        }

        $tableData = $this->cafeTableModel->create()->loadByTableCode($table);
        if(empty($tableData)){
            $this->messageManager->addErrorMessage(__("Invalid Table, Please scan the qrcode again."));
            return $redirect->setPath('/');
        }else if($tableData->getTableStatus() == TableInterface::TABLE_STATUS_OCCUPIED){
            $this->messageManager->addErrorMessage(__("Table is already occupied."));
            return $redirect->setPath('/');
        }

        $result = $this->pageFactory->create();
        $result->getConfig()->getTitle()->set('Dine');
        return $result;
    }
}