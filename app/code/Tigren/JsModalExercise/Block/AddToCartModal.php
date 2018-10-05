<?php

namespace Tigren\JsModalExercise\Block;

use Magento\Framework\View\Element\Template;

class AddToCartModal extends \Magento\Framework\View\Element\Template
{
    protected $_checkoutSession;

    protected $_quote;

    protected $_product;

    protected $_helperdata;

    public function __construct(\Magento\Checkout\Model\Session $checkoutSession
        , \Magento\Catalog\Model\Product $product
        , \Tigren\JsModalExercise\Helper\Data $helperdata
        , Template\Context $context, array $data = [])
    {
        $this->_checkoutSession = $checkoutSession;
        $this->_product = $product;
        $this->_helperdata = $helperdata;
        parent::__construct($context, $data);
    }

    protected function _prepareLayout()
    {
        return parent::_prepareLayout();
    }

    public function getQuote()
    {
        if (!isset($this->_quote)) {
            $this->_quote = $this->_checkoutSession->getQuote();
        }
        return $this->_quote;
    }


    public function getContent()
    {
        return 'Items in cart : ';
    }

    public function getItems()
    {
        return $this->getQuote()->getAllVisibleItems();
    }

    public function getMultipleCart()
    {
        $items = $this->getItems();
        $i = 0;
        foreach ($items as $item)
        {
//           $product = $this->_product->load($item->getProductId());
//           $attribute = $product->getCustomAttribute('is_multiple_cart')->getValue();
           $attribute = $item->getProduct()->getData('is_multiple_cart');
           if($attribute > 0)
           {
               $i++;
           }
        }
        return $i;
    }

    public function isEnable()
    {
        return $this->_helperdata->getConfigValue();
    }
}