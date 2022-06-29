<?php
namespace Techxspace\Cafe\Setup;

use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;

class InstallData implements InstallDataInterface
{
    protected $setupFactory;

    public function __construct(
        \Techxspace\Cafe\Setup\CafeOrderFactory $setupFactory
    )
    {
        $this->setupFactory = $setupFactory;
    }

    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        $cafeOrderSetup = $this->setupFactory->create(['setup' => $setup]);

        $cafeOrderSetup->installEntities();
        $setup->endSetup();
    }
}