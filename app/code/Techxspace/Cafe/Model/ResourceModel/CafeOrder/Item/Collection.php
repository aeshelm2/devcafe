<?php
namespace Techxspace\Cafe\Model\ResourceModel\CafeOrder\Item;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'entity_id';
	protected $_eventPrefix = 'techxspace_cafe_order_item_collection';
	protected $_eventObject = 'cafe_order_item_collection';

    	/**
	 * Define resource model
	 *
	 * @return void
	 */
	protected function _construct()
	{
		$this->_init('Techxspace\Cafe\Model\Order\Item', 'Techxspace\Cafe\Model\ResourceModel\Order\Item');
	}
}