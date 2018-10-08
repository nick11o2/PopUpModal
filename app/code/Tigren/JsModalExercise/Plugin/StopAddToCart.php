<?php
namespace Tigren\JsModalExercise\Plugin;

use Magento\Framework\Exception\LocalizedException;

class StopAddToCart
{
    /**
     * @var \Magento\Quote\Model\Quote
     */
    protected $_quote;

    protected $_helperdata;

    /**
     * Plugin constructor.
     *
     * @param \Magento\Checkout\Model\Session $checkoutSession
     */
    public function __construct(
        \Magento\Checkout\Model\Session $checkoutSession
        ,\Tigren\JsModalExercise\Helper\Data $helperdata
    ) {
        $this->_quote = $checkoutSession->getQuote();
        $this->_helperdata = $helperdata;
    }

    /**
     * beforeAddProduct
     *
     * @param      $subject
     * @param      $productInfo
     * @param null $requestInfo
     *
     * @return array
     * @throws LocalizedException
     */
    public function beforeAddProduct($productInfo, $requestInfo = null)
    {
        if ($this->_helperdata->CheckMultipleCart() > 0 && $this->_helperdata->getConfigValue() > 0)  {
            die();
        }
        return [$productInfo, $requestInfo];
    }
}