<?php
namespace Xelm2\Cafe\Model\Config\Source;

class OrderStatus implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => "pending", 'label' => __('Pending')], 
            ['value' => "preparing", 'label' => __('Preparing')],
            ['value' => "served", 'label' => __('Served')],
            ['value' => "cancelled", 'label' => __('Cancelled')],
        ];
    }

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray()
    {
        return [
            "pending" => __('Pending'), 
            "preparing" => __('Preparing'),
            "served" => __('Served'),
            "cancelled" => __('Cancelled')
        ];
    }
}
