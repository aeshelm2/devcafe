<?php
namespace Xelm2\Cafe\Model\ResourceModel;

use Magento\Sales\Model\EntityInterface;

class CafeOrder extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
	
    /**
     * Event prefix
     *
     * @var string
     */
    protected $_eventPrefix = 'cafe_order_resource';

    /**
     * Event object
     *
     * @var string
     */
    protected $_eventObject = 'resource';

	public function __construct(
		\Magento\Framework\Model\ResourceModel\Db\Context $context
	)
	{
		parent::__construct($context);
	}
	
	protected function _construct()
	{
		$this->_init('cafe_order', 'entity_id');
	}

	public function loadByCustomerId(\Xelm2\Cafe\Model\CafeOrder $cafeOrder, $customerId)
	{
        $connection = $this->getConnection();
		$bind = ['customer_id' => $customerId];
        $select = $connection->select()->from(
            $this->getTable('cafe_order'),
            ['entity_id']
        )->where(
            'customer_id = :customer_id'
        );

		$customerId = $connection->fetchOne($select, $bind);
        if ($customerId) {
            $this->load($cafeOrder, $customerId);
        } else {
            $cafeOrder->setData([]);
        }
	}

	public function loadByQuoteId(\Xelm2\Cafe\Model\CafeOrder $cafeOrder, $quoteId)
	{
        $connection = $this->getConnection();
		$bind = ['quote_id' => $quoteId];
        $select = $connection->select()->from(
            $this->getTable('cafe_order'),
            ['entity_id']
        )->where(
            'quote_id = :quote_id'
        );

		$quoteId = $connection->fetchOne($select, $bind);
		if ($quoteId) {
            $this->load($cafeOrder, $quoteId);
        } else {
            $cafeOrder->setData([]);
        }
	}


    /**
     * Perform actions before object save
     *
     * Perform actions before object save, calculate next sequence value for increment Id
     *
     * @param \Magento\Framework\Model\AbstractModel|\Magento\Framework\DataObject $object
     * @return $this
     */
    protected function _beforeSave(\Magento\Framework\Model\AbstractModel $object)
    {
        /** @var \Magento\Sales\Model\AbstractModel $object */
        if ($object instanceof EntityInterface && $object->getEntityId() == null && $object->getIncrementId() == null) {
            $store = $object->getStore();
            $storeId = $store->getId();
            if ($storeId === null) {
                $storeId = $store->getGroup()->getDefaultStoreId();
            }
            $object->setIncrementId(
                $this->sequenceManager->getSequence(
                    $object->getEntityType(),
                    $storeId
                )->getNextValue()
            );
        }

        parent::_beforeSave($object);
        return $this;
    }
}