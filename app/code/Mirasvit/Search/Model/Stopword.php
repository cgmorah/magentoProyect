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


namespace Mirasvit\Search\Model;

use Magento\Framework\Model\AbstractModel;
use Mirasvit\Search\Api\Data\StopwordInterface;

class Stopword extends AbstractModel implements StopwordInterface
{
    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        $this->_init('Mirasvit\Search\Model\ResourceModel\Stopword');
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getData(self::ID);
    }

    /**
     * {@inheritdoc}
     */
    public function getTerm()
    {
        return $this->getData(self::TERM);
    }

    /**
     * {@inheritdoc}
     */
    public function setTerm($input)
    {
        return $this->setData(self::TERM, $input);
    }

    /**
     * {@inheritdoc}
     */
    public function getStoreId()
    {
        return $this->getData(self::STORE_ID);
    }

    /**
     * {@inheritdoc}
     */
    public function setStoreId($input)
    {
        return $this->setData(self::STORE_ID, $input);
    }
}
