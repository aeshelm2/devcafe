<?php
namespace Techxspace\Cafe\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class SaveToCafeOrder implements ObserverInterface
{    
    /**
     * cafeOrder
     *
     * @var \Techxspace\Cafe\Model\CafeOrderFactory $cafeOrder
     */
    protected $cafeOrder;

    /**
     * customerSession
     *
     * @var \Techxspace\Cafe\Model\Customer\Session $customerSession
     */
    protected $customerSession;

    /**
     * checkoutSession
     *
     * @var \Magento\Checkout\Model\Session $checkoutSession
     */
    protected $checkoutSession;

    /**
     * 
     * @var \Techxspace\Cafe\Model\Order\ItemFactory
     */
    protected $orderItem;
    
    /**
     * __construct
     * 
     * @param \Techxspace\Cafe\Model\CafeOrderFactory $cafeOrder
     * @param \Techxspace\Cafe\Model\Customer\Session $customerSession
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @return void
     */
    public function __construct(
        \Techxspace\Cafe\Model\CafeOrderFactory $cafeOrder,
        \Techxspace\Cafe\Model\Customer\Session $customerSession,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Techxspace\Cafe\Model\Order\ItemFactory $orderItem
    )
    {
        $this->cafeOrder = $cafeOrder;
        $this->customerSession = $customerSession;
        $this->checkoutSession = $checkoutSession;
        $this->orderItem = $orderItem;

    }

    public function execute(Observer $observer)
    {
        $order = $observer->getEvent()->getOrder();
        $quote = $observer->getEvent()->getQuote();
        $customer = $this->customerSession;

        try{
            if($order){

                $cafeOrder = $this->cafeOrder->create();
                $cafeOrder->setCustomerId($customer->getId());
                $cafeOrder->setQuoteId($quote->getId());
                $cafeOrder->setOrderId($order->getId());
                $cafeOrder->setTableCode($customer->getTableCode());
                $cafeOrder->save();

                $orderItems = $order->getAllVisibleItems();
                $items = [];
                foreach($orderItems as $item){
                    $items[] = [
                        'item_id' => $item->getId(),
                        'order_id' => $cafeOrder->getId()
                    ];
                }

                $orderItemModel = $this->orderItem->create();
                $orderItemModel->getResource()->getConnection()->insertOnDuplicate('cafe_order_item', $items);

                // $orderItem
                new \MyLog($items,'addtocart');
            }
        }catch(\Exception $e){
            new \MyLog($e->getMessage(),'addtocart');
        }
    }
}