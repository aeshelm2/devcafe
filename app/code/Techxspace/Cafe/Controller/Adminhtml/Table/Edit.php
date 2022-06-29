<?php
namespace Techxspace\Cafe\Controller\Adminhtml\Table;

class Edit extends \Magento\Backend\App\Action
{
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        protected \Magento\Framework\View\Result\PageFactory $pageFactory
    )
    {
        parent::__construct($context);
    }

    public function execute()
    {
        $pageFactory = $this->pageFactory->create();
        $pageFactory->getConfig()->getTitle()->prepend('Edit Table');
        return $pageFactory;
    }
}