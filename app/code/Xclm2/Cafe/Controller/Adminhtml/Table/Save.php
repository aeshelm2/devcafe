<?php
namespace Xclm2\Cafe\Controller\Adminhtml\Table;

class Save extends \Magento\Backend\App\Action
{    
    /**
     * __construct
     * 
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $pageFactory
     * @param \Xclm2\Cafe\Model\CafeTableFactory $cafeTable
     * @param \Magento\Backend\Model\Session $adminSession
     * @return void
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        protected \Magento\Framework\View\Result\PageFactory $pageFactory,
        protected \Xclm2\Cafe\Model\CafeTableFactory $cafeTable,
        protected \Magento\Backend\Model\Session $adminSession
    )
    {
        parent::__construct($context);
    }

    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            try {
                $entity_id = $this->getRequest()->getParam('entity_id');
                
                $cafeTable = $this->cafeTable->create()->load($entity_id);
                $cafeTable->setData($data);
                $cafeTable->save();

                $this->messageManager->addSuccess(__('The data has been saved.'));
                $this->adminSession->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    if ($this->getRequest()->getParam('back') == 'add') {
                        return $resultRedirect->setPath('*/*/add');
                    } else {
                        return $resultRedirect->setPath('*/*/edit', ['entity_id' => $cafeTable->getEntityId(), '_current' => true]);
                    }
                }
                return $resultRedirect->setPath('*/*/index');
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\RuntimeException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the data.'));
            }
            
            $this->_getSession()->setFormData($data);
            return $resultRedirect->setPath('*/*/edit', ['entity_id' => $this->getRequest()->getParam('entity_id')]);
        }
        
        return $resultRedirect->setPath('*/*/');
    }
}