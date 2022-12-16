<?php
namespace Xelm2\Cafe\Block\Adminhtml;

class Order extends \Magento\Framework\View\Element\Template
{
    protected $orderCollection;
    protected $imageHelper;
    protected $productFactory;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        // \Xelm2\Cafe\Model\ResourceModel\CafeOrder\Collection $orderCollection,
        // \Magento\Quote\Model\ResourceModel\Quote\Item\CollectionFactory $orderCollection,
        \Magento\Sales\Model\ResourceModel\Order\Item\CollectionFactory $orderCollection,
        \Magento\Catalog\Helper\Image $imageHelper,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        array $data = []
    )
    {
        parent::__construct($context, $data);
        $this->orderCollection = $orderCollection;
        $this->imageHelper = $imageHelper;
        $this->productFactory = $productFactory;
    }

    public function getActiveOrders(){
        $collection = $this->orderCollection->create();
        $collection->getSelect()->columns(
            new \Zend_Db_Expr("CASE WHEN (select count(qi.item_id) from sales_order_item as qi where qi.parent_item_id = main_table.item_id) > 0
            THEN (SELECT group_concat((select cpet.value from catalog_product_entity_text as cpet where cpet.entity_id = qi1.product_id)) from sales_order_item as qi1 where qi1.parent_item_id = main_table.item_id)
            ELSE 'none' END as add_ons"));
        $collection->getSelect()->columns(
            new \Zend_Db_Expr("CASE WHEN (select count(value_id) from catalog_product_entity_media_gallery gallery where gallery.value_id = main_table.product_id and media_type = 'image' ) > 0
            THEN (select distinct(value ) from catalog_product_entity_media_gallery gallery where gallery.value_id = main_table.product_id limit 1)
            ELSE 'none' END as product_image")
        );
        $collection->join('cafe_order', 'cafe_order.order_id = main_table.order_id');
        $collection->join('cafe_order_item', 'cafe_order_item.order_id = cafe_order.entity_id',['item_status' => 'cafe_order_item.status']);
		$collection->addFieldToFilter('main_table.parent_item_id', array('null' => true));
        $collection->getSelect()->group("main_table.item_id");
        $collection->getSelect()->order("cafe_order.order_id desc");
        return $collection;
    }

    public function getImageUrl($id)
    {
        try{
            $product = $this->productFactory->create()->load($id);
        }catch (\Magento\Framework\Exception\NoSuchEntityException $e){
            return 'No image found';
        }

        $url = $this->imageHelper->init($product, 'product_thumbnail_image')->getUrl();
        return $url;
    }

    public function updateItemUrl()
    {
        return $this->getUrl('cafe/order/updateItem');
    }
}