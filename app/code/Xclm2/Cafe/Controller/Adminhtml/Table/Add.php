<?php
namespace Xclm2\Cafe\Controller\Adminhtml\Table;

class Add extends \Magento\Backend\App\Action
{    
    /**
     * __construct
     * 
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $pageFactory
     * @return void
     */
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
        $pageFactory->getConfig()->getTitle()->prepend('Add New Table');
        return $pageFactory;
    }
}