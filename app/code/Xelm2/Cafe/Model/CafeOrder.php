<?php
namespace Xelm2\Cafe\Model;

use Magento\Framework\Model\AbstractModel;
use Magento\Framework\DataObject\IdentityInterface;
use Xelm2\Cafe\Api\CustomerInterface;

class CafeOrder extends AbstractModel implements IdentityInterface
{
    const CACHE_TAG = 'cafe_order';

	protected $_cacheTag = 'cafe_order';

	protected $_eventPrefix = 'cafe_order';

	protected function _construct()
	{
		$this->_init('Xelm2\Cafe\Model\ResourceModel\CafeOrder');
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
	
	public function loadByCustomerId($customerId)
    {
        $this->_getResource()->loadByCustomerId($this, $customerId);
        return $this;
    }

	public function loadByQuoteId($quoteId)
    {
        $this->_getResource()->loadByQuoteId($this, $quoteId);
        return $this;
    }


}