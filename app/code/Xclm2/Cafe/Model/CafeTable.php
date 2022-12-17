<?php
namespace Xclm2\Cafe\Model;

use Magento\Framework\Model\AbstractModel;
use Magento\Framework\DataObject\IdentityInterface;
use Xclm2\Cafe\Api\TableInterface;

class CafeTable extends AbstractModel implements IdentityInterface, TableInterface
{
    const CACHE_TAG = 'cafe_table';

	protected $_cacheTag = 'cafe_table';

	protected $_eventPrefix = 'cafe_table';

	protected function _construct()
	{
		$this->_init('Xclm2\Cafe\Model\ResourceModel\CafeTable');
	}

	public function getIdentities()
	{
		return [self::CACHE_TAG . '_' . $this->getId()];
	}

	public function getDefaultValues()
	{
		$values = [];

		return $values;
	}
	
	/**
	 * occupyTable
	 *
	 * @return void
	 */
	public function occupyTable()
	{
		if($this->getTableStatus() == self::TABLE_STATUS_VACANT){
			$this->setTableStatus(self::TABLE_STATUS_OCCUPIED);
		}else{
			throw new \Exception("Table is already occupied.", 1);
		}
	}

	public function vacantTable()
	{
		if($this->getTableStatus() == self::TABLE_STATUS_OCCUPIED){
			$this->setTableStatus(self::TABLE_STATUS_VACANT);
		}
	}

    public function setTableCode($tableCode)
    {
        $this->setData(self::TABLE_CODE, $tableCode);
    }

    public function setTableName($tableName)
	{
        $this->setData(self::TABLE_NAME, $tableName);
	}

    public function setTableStatus($tableStatus)
	{
        $this->setData(self::TABLE_STATUS, $tableStatus);
	}

	public function getTableCode()
	{
		return $this->getData(self::TABLE_CODE);
	}
	
	/**
	 * getTableName
	 *
	 * @return string
	 */
	public function getTableName()
	{
		return $this->getData(self::TABLE_NAME);
	}
	
	/**
	 * getTableStatus
	 *
	 * @return string
	 */
	public function getTableStatus()
	{
		return $this->getData(self::TABLE_STATUS);
	}

	public function loadByTableCode($tableCode)
    {
        $this->_getResource()->loadByTableCode($this, $tableCode);
        return $this;
    }
	
	/**
	 * isVacant
	 *
	 * @return bool
	 */
	public function isVacant()
	{
		return ($this->getTableStatus() == TableInterface::TABLE_STATUS_VACANT);
	}
}