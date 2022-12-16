<?php
namespace Xelm2\Cafe\Model\Order;

use Magento\Framework\Model\AbstractModel;
use Magento\Framework\DataObject\IdentityInterface;
use Xelm2\Cafe\Api\CustomerInterface;

class Item extends AbstractModel implements IdentityInterface
{
    const CACHE_TAG = 'cafe_order_item';
	protected $_cacheTag = 'cafe_order_item';

	protected $_eventPrefix = 'cafe_order_item';
    
	protected function _construct()
	{
		$this->_init('Xelm2\Cafe\Model\ResourceModel\Order\Item');
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
	
	public function loadByItemId($itemId)
    {
        $this->_getResource()->loadByItemId($this, $itemId);
        return $this;
    }
}