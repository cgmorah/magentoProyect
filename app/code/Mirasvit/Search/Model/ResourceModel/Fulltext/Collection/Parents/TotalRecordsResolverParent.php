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
 * @package   mirasvit/module-search
 * @version   1.0.146
 * @copyright Copyright (C) 2020 Mirasvit (https://mirasvit.com/)
 */



namespace Mirasvit\Search\Model\ResourceModel\Fulltext\Collection\Parents;

use Magento\Framework\ObjectManagerInterface;

class TotalRecordsResolverParent
{
    /**
     * @var ObjectManagerInterface
     */
    private $objectManager;

    /**
     * @var null
     */
    private $resolver;

    /**
     * TotalRecordsResolverParent constructor.
     * @param ObjectManagerInterface $objectManager
     */
    public function __construct(ObjectManagerInterface $objectManager)
    {
        $this->objectManager = $objectManager;
        if (class_exists('\Magento\CatalogSearch\Model\ResourceModel\Fulltext\Collection\TotalRecordsResolver')) {
            $this->resolver = $this->objectManager->create(
                '\Magento\CatalogSearch\Model\ResourceModel\Fulltext\Collection\TotalRecordsResolver'
            );
        } else {
            $this->resolver = null;
        }
    }

    /**
     * @return null
     */
    public function resolve()
    {
        if ($this->resolver) {
            return $this->resolver->resolve();
        } else {
            return null;
        }
    }
}
