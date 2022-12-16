<?php
namespace Xelm2\Cafe\Model\ResourceModel\CafeOrder;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'entity_id';
	protected $_eventPrefix = 'xelm2_cafe_order_collection';
	protected $_eventObject = 'cafe_order_collection';

	/**
	 * Define resource model
	 *
	 * @return void
	 */
	protected function _construct()
	{
		$this->_init('Xelm2\Cafe\Model\CafeOrder', 'Xelm2\Cafe\Model\ResourceModel\CafeOrder');
	}


    /**
     * Get currently dined customers by table code
	 * 
     * @param string $tableCode
     * @return $this
     */
    public function getActiveOrders()
    {
		$this->join('quote_item', 'quote_item.quote_id = main_table.quote_id');
		$this->join('cafe_table', 'cafe_table.table_code = main_table.table_code');
		$this->join('cafe_customer', 'cafe_customer.table_code = main_table.table_code');
		$this->addFieldToFilter('cafe_customer.status', array('eq', 1));
		$this->addFieldToFilter('quote_item.parent_item_id', array('null' => true));
        return $this;
    }
}