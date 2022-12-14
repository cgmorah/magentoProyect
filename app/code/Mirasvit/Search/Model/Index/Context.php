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



namespace Mirasvit\Search\Model\Index;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\ObjectManagerInterface;
use Magento\Store\Model\StoreManagerInterface;
use Mirasvit\Search\Api\Repository\IndexRepositoryInterface;
use Mirasvit\Search\Model\Config;

class Context
{
    /**
     * @var Indexer
     */
    private $indexer;

    /**
     * @var Searcher
     */
    private $searcher;

    /**
     * @var ResourceConnection
     */
    private $resourceConnection;

    /**
     * @var ObjectManagerInterface
     */
    private $objectManager;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var Config
     */
    private $config;

    /**
     * @var ContextFactory
     */
    private $contextFactory;

    /**
     * @var IndexRepositoryInterface
     */
    private $indexRepository;

    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * Context constructor.
     * @param IndexerFactory $indexerFactory
     * @param SearcherFactory $searcherFactory
     * @param ResourceConnection $resourceConnection
     * @param ObjectManagerInterface $objectManager
     * @param StoreManagerInterface $storeManager
     * @param Config $config
     * @param ContextFactory $contextFactory
     * @param IndexRepositoryInterface $indexRepository
     * @param RequestInterface $request
     */
    public function __construct(
        IndexerFactory $indexerFactory,
        SearcherFactory $searcherFactory,
        ResourceConnection $resourceConnection,
        ObjectManagerInterface $objectManager,
        StoreManagerInterface $storeManager,
        Config $config,
        ContextFactory $contextFactory,
        IndexRepositoryInterface $indexRepository,
        RequestInterface $request
    ) {
        $this->indexer = $indexerFactory->create();
        $this->searcher = $searcherFactory->create();
        $this->resourceConnection = $resourceConnection;
        $this->objectManager = $objectManager;
        $this->storeManager = $storeManager;
        $this->config = $config;
        $this->contextFactory = $contextFactory;
        $this->indexRepository = $indexRepository;
        $this->request = $request;
    }

    /**
     * @return Context
     */
    public function getInstance()
    {
        return $this->contextFactory->create();
    }

    /**
     * @return Indexer
     */
    public function getIndexer()
    {
        return $this->indexer;
    }

    /**
     * @return Searcher
     */
    public function getSearcher()
    {
        return $this->searcher;
    }

    /**
     * @return ResourceConnection
     */
    public function getResourceConnection()
    {
        return $this->resourceConnection;
    }

    /**
     * @return ObjectManagerInterface
     */
    public function getObjectManager()
    {
        return $this->objectManager;
    }

    /**
     * @return StoreManagerInterface
     */
    public function getStoreManager()
    {
        return $this->storeManager;
    }

    /**
     * @return Config
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @return IndexRepositoryInterface
     */
    public function getIndexRepository()
    {
        return $this->indexRepository;
    }

    /**
     * @return RequestInterface
     */
    public function getRequest()
    {
        return $this->request;
    }
}
