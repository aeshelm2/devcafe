<?php
namespace Techxspace\Cafe\Model\Customer;

class Session extends \Magento\Customer\Model\Session
{
    const CHECK_IN_CODE = 'check_in_code';
    const TABLE_CODE = 'table_code';

    public function setRequiredSession($customerId, $tableCode)
    {
        $checkInCode = $this->generateCheckInID($customerId);
        $this->setCheckInCode($checkInCode);
        $this->setTableCode($tableCode);
    }

    public function setTableCode($value)
    {
        return $this->storage->setData(self::TABLE_CODE, $value);
    }

    public function setCheckInCode($value)
    {
        if(!$this->getCheckInCode()){
            $checkInCode = $this->generateCheckInID($value);
            return $this->storage->setData(self::CHECK_IN_CODE, $checkInCode);
        }
        return $this;
    }
    
    /**
     * getCheckInCode
     *
     * @return $this
     */
    public function getCheckInCode()
    {
        return $this->storage->getData(self::CHECK_IN_CODE);
    }
    
    /**
     * getTableCode
     *
     * @return $this
     */
    public function getTableCode()
    {
        return $this->storage->getData(self::TABLE_CODE);
    }

    public function generateCheckInID($customerId)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';

        for ($i = 0; $i < 5; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

		return $this->getId() . "_" . date("YMD") . "_" . $randomString;
    }

}