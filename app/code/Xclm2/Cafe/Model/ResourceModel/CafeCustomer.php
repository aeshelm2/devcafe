<?php
namespace Xclm2\Cafe\Model\ResourceModel;

class CafeCustomer extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
	
	public function __construct(
		\Magento\Framework\Model\ResourceModel\Db\Context $context
	)
	{
		parent::__construct($context);
	}
	
	protected function _construct()
	{
		$this->_init('cafe_customer', 'entity_id');
	}

	public function loadByCustomerId(\Xclm2\Cafe\Model\CafeCustomer $cafeCustomer, $customerId)
	{
        $connection = $this->getConnection();
		$bind = ['customer_id' => $customerId];
        $select = $connection->select()->from(
            $this->getTable('cafe_customer'),
            ['entity_id']
        )->where(
            'customer_id = :customer_id'
        );

		$customerId = $connection->fetchOne($select, $bind);
        if ($customerId) {
            $this->load($cafeCustomer, $customerId);
        } else {
            $cafeCustomer->setData([]);
        }
	}
}