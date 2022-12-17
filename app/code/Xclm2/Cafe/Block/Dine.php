<?php
namespace Xclm2\Cafe\Block;

class Dine extends \Magento\Framework\View\Element\Template
{
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context
    )
    {
        parent::__construct($context);
    }

    public function getSaveUrl()
    {
        $table = $this->getRequest()->getParam('table');
        if (empty($table)) {
            return "";
        }

        return $this->getUrl('cafe/dine/save', ['_current' => true, 'back' => null, 'table' => $table]);
    }
}