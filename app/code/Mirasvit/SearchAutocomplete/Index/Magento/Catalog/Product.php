<?php
/**
 * Mirasvit
 *
 * This source file is subject to the Mirasvit Software License, which is available at https://mirasvit.com/license/.
 * Do not edit or add to this file if you wish to upgrade the to newer versions in the future.
 * If you wish to customize this module for your needs.
 * Please refer to http://www.magentocommerce.com for more information.
 *
 * @category  Mirasvit
 * @package   mirasvit/module-search-autocomplete
 * @version   1.1.109
 * @copyright Copyright (C) 2020 Mirasvit (https://mirasvit.com/)
 */



namespace Mirasvit\SearchAutocomplete\Index\Magento\Catalog;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\CatalogInventory\Helper\Stock as StockHelper;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\RequestInterface;
use Magento\Review\Model\ResourceModel\Review\Summary\CollectionFactory as SummaryFactory;
use Mirasvit\Core\Service\CompatibilityService;
use Mirasvit\SearchAutocomplete\Index\AbstractIndex;
use Mirasvit\SearchAutocomplete\Model\Config;

class Product extends AbstractIndex
{

    private $productMapper;

    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * @var \Magento\Catalog\Api\ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var Config
     */
    private $config;

    /**
     * @var StockHelper
     */
    private $stockHelper;

    /**
     * @var \Magento\Framework\Api\SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * @var SummaryFactory
     */
    private $summaryFactory;

    /**
     * @var array
     */
    private $reviews = [];

    public function __construct(
        Product\Mapper $productMapper,
        Config $config,
        RequestInterface $request,
        StockHelper $stockHelper,
        ProductRepositoryInterface $productRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        SummaryFactory $summaryFactory
    ) {
        $this->productMapper         = $productMapper;
        $this->config                = $config;
        $this->request               = $request;
        $this->stockHelper           = $stockHelper;
        $this->productRepository     = $productRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->summaryFactory        = $summaryFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function getItems()
    {
        $items      = [];
        $categoryId = intval($this->request->getParam('cat'));
        $storeId    = intval($this->request->getParam('store_id'));

        $collection = $this->getCollection();

        $collection->addAttributeToSelect('name')
            ->addAttributeToSelect('short_description')
            ->addAttributeToSelect('description');

        $collection->getSelect()->order('score desc');

        if (!$this->config->isOutOfStockAllowed()) {
            try {
                $this->stockHelper->addInStockFilterToCollection($collection);
            } catch (\Exception $e) {
            }
        }

        if ($categoryId) {
            $om       = ObjectManager::getInstance();
            $category = $om->create(\Magento\Catalog\Model\Category::class)->load($categoryId);
            $collection->addCategoryFilter($category);
        }

        if ($this->config->isShowRating()) {
            $this->prepareRatingData($collection->getAllIds(), $storeId);
        }

        /** @var \Magento\Catalog\Model\Product $product */
        foreach ($collection as $product) {
            $map = $this->mapProduct($product, $storeId);

            if ($map) {
                $items[] = $map;
            }
        }

        return $items;
    }

    /**
     * @param array                                         $documents
     * @param \Magento\Framework\Search\Request\Dimension[] $dimensions
     *
     * @return array
     */
    public function map($documents, $dimensions)
    {
        if (!$this->config->isFastMode() || count($documents) === 0) {
            return $documents;
        }

        $storeId    = current($dimensions)->getValue();
        $productIds = array_keys($documents);

        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter('entity_id', $productIds, 'in');

        if (CompatibilityService::is20() || CompatibilityService::is21()) {
            // magento disallows addFilter 'store'
        } elseif (CompatibilityService::is22()) {
            $searchCriteria->addFilter('store', $storeId);
        } else {
            $searchCriteria->addFilter('store_id', $storeId);
        }

        $searchCriteria    = $searchCriteria->create();
        $productCollection = $this->productRepository->getList($searchCriteria)->getItems();

        if ($this->config->isShowRating()) {
            $this->prepareRatingData($productIds, $storeId);
        }

        foreach ($productCollection as $product) {
            $documents[$product->getId()]['autocomplete'] = $this->mapProduct($product, $storeId);
        }

        unset($productCollection);

        return $documents;
    }

    /**
     * @param array $productIds
     * @param int   $storeId
     */
    private function prepareRatingData($productIds, $storeId)
    {
        $reviewsCollection = $this->summaryFactory->create()
            ->addEntityFilter($productIds)
            ->addStoreFilter($storeId)
            ->load();

        /** @var \Magento\Review\Model\Review\Summary $reviewSummary */
        foreach ($reviewsCollection as $reviewSummary) {
            $this->reviews[$reviewSummary->getData('entity_pk_value')] = $reviewSummary;
        }
    }

    /**
     * @param ProductInterface|\Magento\Catalog\Model\Product $product
     * @param int                                             $storeId
     *
     * @return array
     */
    private function mapProduct(ProductInterface $product, $storeId = 1)
    {
        return [
            'name'        => $this->productMapper->getProductName($product),
            'url'         => $this->productMapper->getProductUrl($product, $storeId),
            'sku'         => $this->productMapper->getProductSku($product),
            'description' => $this->productMapper->getDescription($product),
            'image'       => $this->productMapper->getProductImage($product, $storeId),
            'price'       => $this->productMapper->getPrice($product),
            'rating'      => $this->productMapper->getRating($product, $storeId, $this->reviews),
            'cart'        => $this->productMapper->getCart($product, $storeId),
            'optimize'    => false,
        ];
    }
}
