<?php
namespace Xclm2\Cafe\Model;

use Magento\Framework\Model\AbstractModel;
use Magento\Framework\DataObject\IdentityInterface;
use Xclm2\Cafe\Api\CustomerInterface;

class CafeCustomer extends AbstractModel implements IdentityInterface, CustomerInterface
{
    const CACHE_TAG = 'cafe_customer';

	protected $_cacheTag = 'cafe_customer';

	protected $_eventPrefix = 'cafe_customer';

	protected function _construct()
	{
		$this->_init('Xclm2\Cafe\Model\ResourceModel\CafeCustomer');
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
	
	/**
	 * checkout
	 *
	 * @return $this
	 */
	public function checkout()
	{
		return $this->setCheckOutBy('customer')->setStatus(0);
	}
	
	/**
	 * setStatus
	 *
	 * @param  mixed $value
	 * @return $this
	 */
	public function setStatus($value)
	{
		return $this->setData(self::STATUS, $value);
	}
	
	/**
	 * setCheckoutBy
	 *
	 * @param  mixed $value
	 * @return $this
	 */
	public function setCheckoutBy($value)
	{
		return $this->setData(self::CHECKOUT_BY, $value);
	}

}