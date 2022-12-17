<?php
namespace Xclm2\Cafe\Controller\Adminhtml\Order;

class UpdateItem extends \Magento\Backend\App\Action
{    
    /**
     * resultJsonFactory
     *
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $resultFactory;

    /**
     * orderItem
     *
     * @var \Xclm2\Cafe\Model\Order\ItemFactory
     */
    protected $orderItem;
    
    /**
     * __construct
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     * @param \Xclm2\Cafe\Model\Order\ItemFactory $orderItem
     * 
     * @return void
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Xclm2\Cafe\Model\Order\ItemFactory $orderItem
    )
    {
        parent::__construct($context);
        $this->resultFactory = $resultJsonFactory;
        $this->orderItem = $orderItem;
    }

    public function execute()
    {
        $resultJson = $this->resultFactory->create();
        $response = ['messages' => 'Something went wrong.', 'error' => true];
        try{
            if($this->getRequest()->isPost() && $this->getRequest()->getParam('isAjax')){
                $status = $this->getRequest()->getParam('status');
                $itemId = $this->getRequest()->getParam('item_id');
                
                // Check if status from request is valid
                if(in_array($status, $this->getOrderStatus())){
                    $item = $this->orderItem->create()->loadByItemId($itemId);
                    if($item){
                        $item->setStatus($status);
                        $item->save();
                        $response['error'] = false;
                        $response['messages'] = __('Updated');
                    }else{
                        $response['messages'] = __('Item not found.');
                    }
                    
                }else{
                    $response['messages'] = __('Invalid Status');
                }
                
            }
        }catch(\Exception $e){
            $response['messages'] = $e->getMessage();
        }

        return $resultJson->setData($response);
    }

    public function getOrderStatus()
    {
        $status = new \Xclm2\Cafe\Model\Config\Source\OrderStatus();
        return array_keys($status->toArray());
    }
}