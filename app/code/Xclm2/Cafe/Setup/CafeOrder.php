<?php
namespace Xclm2\Cafe\Setup;

use Magento\Eav\Setup\EavSetup;

class CafeOrder extends EavSetup {
    /**
     * Entity type for Hello World EAV attributes
     */
    const ENTITY_TYPE_CODE = 'cafe_order';

    /**
     * EAV Entity type for Hello World EAV attributes
     */
    const EAV_ENTITY_TYPE_CODE = 'cafe_order_entity';

    public function getDefaultEntities() {
        $entities = [
            self::ENTITY_TYPE_CODE => [
                'entity_model' => \Xclm2\Cafe\Model\ResourceModel\CafeOrder::class,
                'table' => 'cafe_order',
                'increment_model' => \Magento\Eav\Model\Entity\Increment\NumericValue::class,
                'increment_per_store' => true,
                'attributes' => [],
            ],
        ];

        return $entities;
    }
}