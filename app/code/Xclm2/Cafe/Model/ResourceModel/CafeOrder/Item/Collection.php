<?php
namespace Xclm2\Cafe\Model\ResourceModel\CafeOrder\Item;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'entity_id';
	protected $_eventPrefix = 'xclm2_cafe_order_item_collection';
	protected $_eventObject = 'cafe_order_item_collection';

    	/**
	 * Define resource model
	 *
	 * @return void
	 */
	protected function _construct()
	{
		$this->_init('Xclm2\Cafe\Model\Order\Item', 'Xclm2\Cafe\Model\ResourceModel\Order\Item');
	}
}