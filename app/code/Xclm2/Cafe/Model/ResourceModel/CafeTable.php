<?php
namespace Xclm2\Cafe\Model\ResourceModel;

use Xclm2\Cafe\Api\TableInterface;

class CafeTable extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
	
	public function __construct(
		\Magento\Framework\Model\ResourceModel\Db\Context $context
	)
	{
		parent::__construct($context);
	}
	
	protected function _construct()
	{
		$this->_init('cafe_table', 'entity_id');
	}

	public function loadByTableCode(\Xclm2\Cafe\Model\CafeTable $cafeTable, $tableCode)
	{
        $connection = $this->getConnection();
		$bind = ['table_code' => $tableCode];
        $select = $connection->select()->from(
            $this->getTable('cafe_table'),
            ['entity_id']
        )->where(
            'table_code = :table_code'
        );

		$tableId = $connection->fetchOne($select, $bind);
        if ($tableId) {
            $this->load($cafeTable, $tableId);
        } else {
            $cafeTable->setData([]);
        }
	}

	public function getOccupiedTables()
	{
        $connection = $this->getConnection();
		$query = $connection->select()->from(
			$this->getTable('cafe_table')
		)->where(
			'table_status = ? ', TableInterface::TABLE_STATUS_OCCUPIED
		);

		$result = $connection->fetchAll($query);
		return $result;
	}

	public function getVacantTables()
	{
        $connection = $this->getConnection();
		$query = $connection->select()->from(
			$this->getTable('cafe_table')
		)->where(
			'table_status = ? ', TableInterface::TABLE_STATUS_VACANT
		);

		$result = $connection->fetchAll($query);
		return $result;
	}
}