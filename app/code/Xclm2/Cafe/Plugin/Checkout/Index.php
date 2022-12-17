<?php 
namespace Xclm2\Cafe\Plugin\Checkout;

class Index
{    
    public function __construct(
        protected \Magento\Customer\Model\Session $session,
        protected \Xclm2\Cafe\Model\CafeTableFactory $cafeTable
    ){}

    /**
     * beforeExecute
     *
     * @param  \Magento\Checkout\Controller\Index\Index $subject
     * @return void
     */
    public function beforeExecute(\Magento\Checkout\Controller\Index\Index $subject)
    {
        $table = $this->session->getTableCode();
        $cafeTable = $this->cafeTable->create()->loadByTableCode($table);
        if(empty($table) || empty($cafeTable) || $cafeTable->isVacant()){
            $this->session->logout();
        }
    }
}