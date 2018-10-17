<?php

    namespace Tigren\JsModalExercise\Block;
    use Magento\Catalog\Api\ProductRepositoryInterface;
    use Magento\Catalog\Block\Product\Context;
    use Magento\Catalog\Block\Product\ListProduct;
    use Magento\Catalog\Api\CategoryRepositoryInterface;
    use Magento\Catalog\Model\Layer\Resolver;
    use Magento\Framework\Data\Helper\PostHelper;
    use Magento\Framework\Url\Helper\Data;

    class ProductCarousel extends ListProduct
    {
        protected $_resource;

        protected $_product;

        protected $_productCollection;

        protected $_productCollectionFactory;

        protected $_catalogProductVisibility;

        protected $_productReposity;

        protected $ignoredProductTypes = [
            \Magento\Catalog\Model\Product\Type::TYPE_BUNDLE => \Magento\Catalog\Model\Product\Type::TYPE_BUNDLE,
        ];

        public function __construct(Context $context, PostHelper $postDataHelper, Resolver $layerResolver, CategoryRepositoryInterface $categoryRepository, Data $urlHelper, array $data = []
            ,\Magento\Catalog\Model\Product $product
            ,\Magento\Framework\App\ResourceConnection $resource
            ,\Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory
            ,\Magento\Catalog\Model\ResourceModel\Product\Collection $productCollection
            ,\Magento\Catalog\Model\Product\Visibility $catalogProductVisibility
            ,\Magento\Directory\Model\Currency $currency
            ,ProductRepositoryInterface $productRepository
            )
        {
            $this->_product = $product;
            $this->_resource = $resource;
            $this->_productCollectionFactory = $productCollectionFactory;
            $this->_productCollection = $productCollection;
            $this->_catalogProductVisibility = $catalogProductVisibility;
            $this->_currency = $currency;
            $this->productRepository = $productRepository;
            parent::__construct($context, $postDataHelper, $layerResolver, $categoryRepository, $urlHelper, $data);
        }


//        public function getBestSeller()
//        {
//            $bestSellerCollection = $this->_bestSellerCollection->create()
//                ->setModel('Magento\Catalog\Model\Product')->setPeriod('month');
//            return $bestSellerCollection;
//        }

        protected function getNewsProductCollection()
        {

                $collection = $this->_productCollectionFactory->create();
                $collection->setVisibility($this->_catalogProductVisibility->getVisibleInCatalogIds());
                $todayStartOfDayDate = $this->_localeDate->date()->setTime(0, 0, 0)->format('Y-m-d H:i:s');
                $todayEndOfDayDate = $this->_localeDate->date()->setTime(23, 59, 59)->format('Y-m-d H:i:s');
                $collection->addStoreFilter()->addAttributeToFilter(
                    'news_from_date',
                    [
                        'or' => [
                            0 => ['date' => true, 'to' => $todayEndOfDayDate],
                            1 => ['is' => new \Zend_Db_Expr('null')],
                        ]
                    ],
                    'left'
                )->addAttributeToFilter(
                    'news_to_date',
                    [
                      'or' => [
                          0 => ['date' => true, 'from' => $todayStartOfDayDate],
                          1 => ['is' => new \Zend_Db_Expr('null')],
                      ]
                    ],
                    'left'
                )->addAttributeToFilter(
                    [
                        ['attribute' => 'news_from_date', 'is' => new \Zend_Db_Expr('not null')],
                        ['attribute' => 'news_to_date', 'is' => new \Zend_Db_Expr('not null')],
                    ]
                )->setPageSize(
                    12
                )->addAttributeToSelect(
                    '*'
                )->addAttributeToSort(
                    'create_at','DESC'
                );

            return $collection;
        }

        public function getLoadedNewProductCollection()
        {
            return $this->getNewsProductCollection();
        }

        protected function getBestSellersCollection()
        {
            $today = date("Y-m-d h:i:s");
            $today = strtotime('+1 day', strtotime($today));
            $today = date('Y-m-d h:i:s',$today);
            $from = strtotime('-30 day', strtotime($today));
            $from = date('Y-m-d h:i:s',$from);
//            $columns = [
//                'store_id' => 'source_table.store_id',
//                'product_id' => 'order_item.product_id',
//                'qty_ordered' => new \Zend_Db_Expr('SUM(order_item.qty_ordered)'),
//            ];

            $Connection = $this->_productCollection->getConnection();
            $select = $Connection->select();
            $select->from(
                ['source_table' => $this->_productCollection->getTable('sales_order')],
                []
            )->joinInner(
                ['order_item' => $this->_productCollection->getTable('sales_order_item')],
                'order_item.order_id = source_table.entity_id',
                [
                    'order_item.product_id',
                    'qty_ordered' => new \Zend_Db_Expr('SUM(order_item.qty_ordered)'),
                ]
            )->joinLeft(
                ['order_item_parent' => $this->_productCollection->getTable('sales_order_item')],
                'order_item.parent_item_id = order_item_parent.item_id',
                []
            )->where(
                'order_item.parent_item_id is null'
            )->where(
                'order_item.product_type NOT IN(?)',
                $this->ignoredProductTypes
            )->where(
                'source_table.state != ?',
                \Magento\Sales\Model\Order::STATE_CANCELED
            )->where(
                'source_table.created_at BETWEEN "'. $from .'" AND "'. $today .'" '
            )->group(
                ['source_table.store_id','order_item.product_id']
            )->order(
                'qty_ordered DESC'
            )->limit(
                12
            );
            $items = $Connection->fetchAll($select);
            $Collection = $this->_productCollectionFactory->create();
            $Ids = array();
            foreach ($items as $item)
            {
                array_push($Ids, $item['product_id']);
            }
            $Collection->addIdFilter($Ids)->addAttributeToSelect('*');
                return $Collection;
//            return $select->__toString();
        }

        public function getLoadedBestSellersCollection()
        {
            return $this->getBestSellersCollection();
        }

    }