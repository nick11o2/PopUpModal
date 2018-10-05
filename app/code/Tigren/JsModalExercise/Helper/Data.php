<?php

namespace Tigren\JsModalExercise\Helper;
use \Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
class Data extends AbstractHelper
{
    protected $_quote;

    protected $_checkoutSession;

    protected $_storeManager;

    protected $_scopeConfig;

    public function __construct(Context $context,
        \Magento\Checkout\Model\Session $checkoutSession
        ,\Magento\Store\Model\StoreManagerInterface $storeManager
        ,\Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig)
    {
        $this->_checkoutSession = $checkoutSession;
        $this->_storeManager = $storeManager;
        $this->scopeConfig = $scopeConfig;
        parent::__construct($context);
    }

    protected function getQuote()
    {
        if(!isset($this->_quote)){
            $this->_quote = $this->_checkoutSession->getQuote();
        }
        return $this->_quote;
    }

    public function CheckMultipleCart()
    {
        $i = 0;
        $items = $this->getQuote()->getAllVisibleItems();
        foreach ($items as $item)
        {
            $attribute = $item->getProduct()->getData('is_multiple_cart');
            if($attribute > 0)
            {
                $i++;
            }
        }
        return $i;
    }

    public function getBaseUrl()
    {
        return $this->_storeManager->getStore()->getBaseUrl();
    }

    public function getConfigValue()
    {
        return $this->scopeConfig->getValue('tocartmodal/general/enabled',\Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }


}