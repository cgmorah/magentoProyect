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



namespace Mirasvit\SearchAutocomplete\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Mirasvit\SearchAutocomplete\Service\JsonConfigService;

class OnConfigChanged implements ObserverInterface
{
    /**
     * @var JsonConfigService
     */
    private $jsonConfigService;

    /**
     * OnConfigChanged constructor.
     * @param JsonConfigService $jsonConfigService
     */
    public function __construct(
        JsonConfigService $jsonConfigService
    ) {
        $this->jsonConfigService = $jsonConfigService;
    }

    /**
     * @param Observer $observer
     */
    public function execute(Observer $observer)
    {
        $this->jsonConfigService->ensure(JsonConfigService::AUTOCOMPLETE);
        $this->jsonConfigService->ensure(JsonConfigService::TYPEAHEAD);
    }
}
