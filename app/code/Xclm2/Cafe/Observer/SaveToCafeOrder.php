<?php
namespace Xclm2\Cafe\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class SaveToCafeOrder implements ObserverInterface
{    
    /**
     * cafeOrder
     *
     * @var \Xclm2\Cafe\Model\CafeOrderFactory $cafeOrder
     */
    protected $cafeOrder;

    /**
     * customerSession
     *
     * @var \Xclm2\Cafe\Model\Customer\Session $customerSession
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
     * @var \Xclm2\Cafe\Model\Order\ItemFactory
     */
    protected $orderItem;
    
    /**
     * __construct
     * 
     * @param \Xclm2\Cafe\Model\CafeOrderFactory $cafeOrder
     * @param \Xclm2\Cafe\Model\Customer\Session $customerSession
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @return void
     */
    public function __construct(
        \Xclm2\Cafe\Model\CafeOrderFactory $cafeOrder,
        \Xclm2\Cafe\Model\Customer\Session $customerSession,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Xclm2\Cafe\Model\Order\ItemFactory $orderItem
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

            }
        }catch(\Exception $e){
        }
    }
}