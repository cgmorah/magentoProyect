<?php

namespace Webindiainc\Base\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper {

	protected $_productloader;
	protected $swatchesHelper;
	protected $_registry;
	protected $_storeManager;
	protected $cartHelper;
	protected $_urlInterface;
	protected $imageHelper;
	protected $_productRepositoryFactory;
	protected $currencyCode;

   
    /**
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Magento\Catalog\Model\ProductFactory $_productloader
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
		\Magento\Catalog\Model\ProductFactory $_productloader,
		\Magento\Swatches\Helper\Data $swatchesHelper,
		\Magento\Framework\Registry $registry,
		\Magento\Store\Model\StoreManagerInterface $storeManager,
		\Magento\Checkout\Helper\Cart $cartHelper,
		\Magento\Framework\UrlInterface $urlInterface,
		\Magento\Catalog\Helper\Image $imageHelper,
    	\Magento\Catalog\Api\ProductRepositoryInterfaceFactory $productRepositoryFactory,
		\Magento\Directory\Model\CurrencyFactory $currencyFactory
    ) {
        $this->_productloader = $_productloader;
        $this->swatchesHelper = $swatchesHelper;
		$this->_registry = $registry;
		$this->_storeManager = $storeManager;
		$this->cartHelper = $cartHelper;
		$this->_urlInterface = $urlInterface;
		$this->imageHelper = $imageHelper;
    	$this->_productRepositoryFactory = $productRepositoryFactory;
    	$this->currencyCode = $currencyFactory->create();
		

        parent::__construct($context);
    }

	public function loadProduct($product_id) {
		return $this->_productloader->create()->load($product_id);
	}

	public function getSwatchesByOptionsId($optionIdArray) {
		return $this->swatchesHelper->getSwatchesByOptionsId($optionIdArray);
	}
	
	public function getCurrentCategory() {
		return $this->_registry->registry('current_category');
	}
	
	public function getCurrentStore() {
		return $this->_storeManager->getStore();
	}
	
	public function quoteHasQty() {
		if ($this->cartHelper->getItemsCount() === 0) {
			return false;
   		}
		return true;
	}
	
	public function getCheckoutUrl($pagename) {
		return $this->_storeManager->getStore()->getUrl($pagename);
	}

	public function getPageUrl($pagename) {
		return $this->_storeManager->getStore()->getUrl($pagename);
	}
	
	public function getConfig($config_path) {
        return $this->scopeConfig->getValue($config_path, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }
	
	public function getWebsiteId() {
        return $this->_storeManager->getStore()->getWebsiteId();
    }
	
	public function getProductData($productId) {
		$product = $this->_productRepositoryFactory->create()->getById($productId);
		return $product;
	}
	
	public function getCurrencySymbol() {
        $currentCurrency = $this->_storeManager->getStore()->getCurrentCurrencyCode();
        $currency = $this->currencyCode->load($currentCurrency);
        return $currency->getCurrencySymbol();
    }
	
}

