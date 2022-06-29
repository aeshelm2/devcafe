<?php
namespace Techxspace\Cafe\Model\Config\Source;

use Techxspace\Cafe\Api\TableInterface;

class TableStatus implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [['value' => TableInterface::TABLE_STATUS_OCCUPIED, 'label' => __('Occupied')], ['value' => TableInterface::TABLE_STATUS_VACANT, 'label' => __('Vacant')]];
    }

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray()
    {
        return [TableInterface::TABLE_STATUS_VACANT => __('Vacant'), TableInterface::TABLE_STATUS_OCCUPIED => __('Occupied')];
    }
}
