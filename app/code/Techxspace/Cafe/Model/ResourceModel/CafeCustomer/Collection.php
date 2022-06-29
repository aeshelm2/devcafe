<?php
namespace Techxspace\Cafe\Model\ResourceModel\CafeCustomer;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'entity_id';
	protected $_eventPrefix = 'techxspace_cafe_customer_collection';
	protected $_eventObject = 'cafe_customer_collection';

	/**
	 * Define resource model
	 *
	 * @return void
	 */
	protected function _construct()
	{
		$this->_init('Techxspace\Cafe\Model\CafeCustomer', 'Techxspace\Cafe\Model\ResourceModel\CafeCustomer');
	}

    /**
     * Get currently dined customers by table code
	 * 
     * @param string $tableCode
     * @return $this
     */
    public function getDinedCustomerByTableCode($tableCode)
    {
		$this->addFieldToFilter('main_table.status', array('eq', 1));
		$this->addFieldToFilter('main_table.table_code', array('eq', $tableCode));
        return $this;
    }
	
	/**
	 * Set Status
	 * returns number of affected rows
	 * 
	 * @param  string $status
	 * @param  string $tableCode
	 * @return int
	 */
	public function setStatus($status, $tableCode, $checkoutBy = 'customer')
	{
		return $this->getConnection()->update(
			$this->getTable('cafe_customer'),
			['status' => $status, 'check_out_by' => $checkoutBy ],
			'`table_code` = "' . $tableCode . '" and status = "1"'
		);
	}
}