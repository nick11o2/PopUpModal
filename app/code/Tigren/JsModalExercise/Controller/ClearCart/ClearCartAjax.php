<?php
namespace Tigren\JsModalExercise\Controller\ClearCart;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;

class ClearCartAjax extends Action
{

    protected $_checkoutSession;

    protected $_jsonFactory;

    protected $_cart;

    protected $_jsonHelper;



    public function __construct(
    \Magento\Checkout\Model\Session $checkoutSession
    ,\Magento\Checkout\Model\Cart $cart
    ,\Magento\Framework\Controller\Result\JsonFactory $jsonFactory
    ,\Magento\Framework\Json\Helper\Data $jsonHelper
    ,Context $context)
    {
        $this->_checkoutSession = $checkoutSession;
        $this->_cart = $cart;
        $this->_jsonFactory = $jsonFactory;
        $this->_jsonHelper = $jsonHelper;
        parent::__construct($context);
    }

    public function getCart()
    {
        return $this->_cart;
    }

    public function execute()
    {
        $response = [ 'error' => false];
        if($this->getRequest()->isAjax())
        {
            try{
                $this->_cart->truncate()->save();
                $response['message'] = __('Empty Cart.');
            } catch(\Exception $e){
                $response = ['errors' => true,
                    'message' => __('Some thing went wrong.')];
            };
        }else {
            $response = ['errors' => true,
                'message' => __('Need to access via Ajax.')];
        }
        $resultJson = $this->_jsonFactory->create();
        return $resultJson->setData($response);
    }
}