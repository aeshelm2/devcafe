<?php 
namespace Techxspace\Cafe\Plugin\Checkout\Cart;

use MyLog;

class Add
{    
    public function __construct(
        protected \Magento\Customer\Model\Session $session,
        protected \Techxspace\Cafe\Model\CafeTableFactory $cafeTable,
        protected \Magento\Framework\Controller\Result\RedirectFactory $redirectFactory
    ){}

    /**
     * beforeExecute
     *
     * @param  \Magento\Checkout\Controller\Cart\Add $subject
     * @return void
     */
    public function beforeExecute(\Magento\Checkout\Controller\Cart\Add $subject)
    {
        $table = $this->session->getTableCode();
        $customerId = $this->session->getId();
        $cafeTable = $this->cafeTable->create()->loadByTableCode($table);
        if(empty($table) || empty($cafeTable) || $cafeTable->isVacant()){
            $this->session->logout()->setLastCustomerId($customerId);;
        }
    }
}