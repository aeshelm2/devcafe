<?php
namespace Xelm2\Cafe\UiComponent\Form;
 
use Xelm2\Cafe\Model\ResourceModel\CafeTable\CollectionFactory;
 
class CafeTable extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    protected $loadedData;

    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $cafeTableCollectionFactory
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $cafeTableCollectionFactory,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $cafeTableCollectionFactory->create();

        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }
 
    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }

        $items = $this->collection->getItems();

        foreach ($items as $cafe) {
            $this->loadedData[$cafe->getEntityId()] = $cafe->getData();
        }

        return $this->loadedData;
    }
}