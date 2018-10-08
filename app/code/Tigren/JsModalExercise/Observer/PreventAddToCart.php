<?php

    namespace Tigren\JsModalExercise\Observer;

    use Magento\Framework\Event\ObserverInterface;

    class PreventAddToCart implements ObserverInterface
    {
        /**
         * @var \Magento\Framework\Message\ManagerInterface
         */
        protected $_messageManager;

        protected $_helperdata;

        /**
         * @param \Magento\Framework\Message\ManagerInterface $messageManager
         */
        public function __construct(
            \Magento\Framework\Message\ManagerInterface $messageManager
            ,\Tigren\JsModalExercise\Helper\Data $helperdata
        )
        {
            $this->_messageManager = $messageManager;
            $this->_helperdata = $helperdata;
        }

        /**
         * add to cart event handler.
         *
         * @param \Magento\Framework\Event\Observer $observer
         *
         * @return $this
         */
        public function execute(\Magento\Framework\Event\Observer $observer)
        {
            if ($this->_helperdata->CheckMultipleCart() > 0 && $this->_helperdata->getConfigValue() > 0) {
                $this->_messageManager->addError(__('you cannot add more products'));
                //set false if you not want to add product to cart
                $observer->getRequest()->setParam('product', false);
                return $this;
            }

            return $this;
        }
    }