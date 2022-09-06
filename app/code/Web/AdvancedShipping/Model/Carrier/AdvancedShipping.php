<?php
namespace Web\AdvancedShipping\Model\Carrier;
 
use Magento\Quote\Model\Quote\Address\RateRequest;
use Magento\Shipping\Model\Rate\Result;
 
class AdvancedShipping extends \Magento\Shipping\Model\Carrier\AbstractCarrier implements
    \Magento\Shipping\Model\Carrier\CarrierInterface
{
    /**
     * @var string
     */
    protected $_code = 'advancedshipping';
 
    /**
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory $rateErrorFactory
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Magento\Shipping\Model\Rate\ResultFactory $rateResultFactory
     * @param \Magento\Quote\Model\Quote\Address\RateResult\MethodFactory $rateMethodFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory $rateErrorFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Shipping\Model\Rate\ResultFactory $rateResultFactory,
        \Magento\Quote\Model\Quote\Address\RateResult\MethodFactory $rateMethodFactory,
        array $data = []
    ) {
        $this->_rateResultFactory = $rateResultFactory;
        $this->_rateMethodFactory = $rateMethodFactory;
        parent::__construct($scopeConfig, $rateErrorFactory, $logger, $data);
    }
 
    /**
     * @return array
     */
    public function getAllowedMethods()
    {
        return ['advancedshipping' => $this->getConfigData('name')];
    }
 
    /**
     * @param RateRequest $request
     * @return bool|Result
     */
    public function collectRates(RateRequest $request)
    {
        if (!$this->getConfigFlag('active')) {
            return false;
        }
 
        /** @var \Magento\Shipping\Model\Rate\Result $result */
        $result = $this->_rateResultFactory->create();
 
        /** @var \Magento\Quote\Model\Quote\Address\RateResult\Method $method */
        $method = $this->_rateMethodFactory->create();
 
        $method->setCarrier('advancedshipping');
        $method->setCarrierTitle($this->getConfigData('title'));
 
        $method->setMethod('advancedshipping');
        $method->setMethodTitle($this->getConfigData('name'));
 
        /*you can fetch shipping price from different sources over some APIs, we used price from config.xml - xml node price*/
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $cart = $objectManager->get('\Magento\Checkout\Model\Cart'); 
        $items = $cart->getQuote()->getAllVisibleItems();
        $amount = $this->getConfigData('price');
        
		
		$country_id = $cart->getQuote()->getShippingAddress()->getCountryId();
	
		$amoutArray = array();
		$i = 0;
		foreach($items as $item) {
            $product = $objectManager->create('Magento\Catalog\Model\Product')->load($item->getProductId());
			
			
			if($country_id == 'AU') {
				$amoutArray[$i]['sku'] = $product->getSku();
				$amoutArray[$i]['qty'] = $item->getQty();
				$amoutArray[$i]['first_qty'] = $product->getAusFirstItem();
				$amoutArray[$i]['second_qty'] = $product->getAusSecondItem();
			} else {
				$amoutArray[$i]['sku'] = $product->getSku();
				$amoutArray[$i]['qty'] = $item->getQty();
				$amoutArray[$i]['first_qty'] = $product->getOverseasFirstItem();
				$amoutArray[$i]['second_qty'] = $product->getOverseasSecondItem();
			}
			$i++;
		}
		
		/* $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/test.log');
		$logger = new \Zend\Log\Logger();
		$logger->addWriter($writer);
		$logger->info('country Id: ' . print_r($amoutArray, 1)); */
		
		$max = 0;
		$maxShippingKey = 0;
		foreach($amoutArray as $key => $value) {
			if ($value['first_qty'] > $max) {
				$max = $value['first_qty'];
				$maxShippingKey = $key;
			}
		}
		$amoutArray[$maxShippingKey]['qty'] = $amoutArray[$maxShippingKey]['qty'] - 1;
		
		$amount += $max;
		foreach($amoutArray as $key => $value) {
			$amount += ( $value['qty'] * $value['second_qty'] );
		}
        
        /* End */
 
        $method->setPrice($amount);
        $method->setCost($amount);
 
        $result->append($method);
 
        return $result;
    }
}