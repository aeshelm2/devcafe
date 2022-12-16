<?php
namespace Xelm2\Cafe\Controller\Adminhtml\Order;

class Index extends \Magento\Backend\App\Action
{
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        protected \Magento\Framework\View\Result\PageFactory $pageFactory,
        protected \Magento\Customer\Model\Session\SessionCleaner $sessionCleaner
    )
    {
        parent::__construct($context);
    }

    public function execute()
    {
        $pageFactory = $this->pageFactory->create();
        $pageFactory->getConfig()->getTitle()->prepend('Manage Orders');
        return $pageFactory;
    }
}