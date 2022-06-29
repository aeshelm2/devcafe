<?php
namespace Techxspace\Cafe\Model\ResourceModel\Order;

use Magento\Sales\Model\EntityInterface;

class Item extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
	
    /**
     * Event prefix
     *
     * @var string
     */
    protected $_eventPrefix = 'cafe_order_item_resource';

    /**
     * Event object
     *
     * @var string
     */
    protected $_eventObject = 'resource';

	public function __construct(
		\Magento\Framework\Model\ResourceModel\Db\Context $context
	)
	{
		parent::__construct($context);
	}
	
	protected function _construct()
	{
		$this->_init('cafe_order_item', 'entity_id');
	}

	public function loadByItemId(\Techxspace\Cafe\Model\Order\Item $cafeOrderItem, $itemId)
	{
        $connection = $this->getConnection();
		$bind = ['item_id' => $itemId];
        $select = $connection->select()->from(
            $this->getTable('cafe_order_item'),
            ['entity_id']
        )->where(
            'item_id = :item_id'
        );

		$itemId = $connection->fetchOne($select, $bind);
		if ($itemId) {
            $this->load($cafeOrderItem, $itemId);
        } else {
            $cafeOrderItem->setData([]);
        }
	}
}