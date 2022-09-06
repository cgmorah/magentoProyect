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


namespace Mirasvit\Search\Api\Data\Index;

use Magento\Framework\Search\Request\Dimension;

interface DataMapperInterface
{
    /**
     * @param array       $documents
     * @param Dimension[] $dimensions
     * @param string      $indexIdentifier
     * @return array
     */
    public function map(array $documents, $dimensions, $indexIdentifier);
}
