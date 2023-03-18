<?php
namespace Xclm2\Cafe\Model\Order;

use Magento\Framework\Model\AbstractModel;
use Magento\Framework\DataObject\IdentityInterface;
use Xclm2\Cafe\Api\CustomerInterface;

class Item extends AbstractModel implements IdentityInterface
{
    const CACHE_TAG = 'cafe_order_item';
	protected $_cacheTag = 'cafe_order_item';

	protected $_eventPrefix = 'cafe_order_item';
    
	protected function _construct()
	{
		$this->_init('Xclm2\Cafe\Model\ResourceModel\Order\Item');
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