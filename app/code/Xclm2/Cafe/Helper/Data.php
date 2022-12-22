<?php
namespace Xclm2\Cafe\Helper;

use Magento\Framework\App\Helper\AbstractHelper;

class Data extends AbstractHelper
{
    private $hash_key = "xclm2";

    public function __construct(
        protected \Magento\Framework\App\Helper\Context $context,
        protected \Magento\Framework\Encryption\EncryptorInterface $encryptor,
    )
    {
        parent::__construct($context);
    }

    public function hash($str)
    {
        return $this->encryptor->encrypt($str);
    }
}