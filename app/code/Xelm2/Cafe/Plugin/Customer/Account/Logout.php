<?php 
namespace Xelm2\Cafe\Plugin\Customer\Account;

class Logout
{    
    public function __construct(
        protected \Magento\Customer\Model\Session $session,
        protected \Xelm2\Cafe\Model\CafeManagement $cafeManagement,

    ){}

    /**
     * beforeExecute
     *
     * @param  \Magento\Customer\Controller\Account\Logout $subject
     * @return void
     */
    public function beforeExecute(\Magento\Customer\Controller\Account\Logout $subject)
    {
        $table = $this->session->getTableCode();
        $customerId = $this->session->getId();
        $this->cafeManagement->checkoutCustomer($customerId, $table);
    }
}