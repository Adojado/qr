<?php

namespace Adojado\Qr\Console\Command;

use Adojado\Qr\Api\AttributeInterface;
use Adojado\Qr\Api\QueueInterface;
use Adojado\Qr\Model\Config;
use Magento\Catalog\Api\Data\ProductAttributeInterface;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Framework\MessageQueue\PublisherInterface;
use Magento\Store\Api\StoreRepositoryInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class AddProductsToQueue extends Command
{
    /**
     * @var string
     * */
    const COUNT_OPTION = 'products-count';

    /**
     * @var Config
     * */
    private $config;

    /**
     * @var CollectionFactory
     * */
    private $productCollectionFactory;

    /**
     * @var StoreRepositoryInterface
     * */
    private $storeRepository;

    /**
     * @var PublisherInterface
     * */
    private $publisher;

    public function __construct(
        Config $config,
        CollectionFactory $productCollectionFactory,
        StoreRepositoryInterface $storeRepository,
        PublisherInterface $publisher,
        string $name = null
    ) {
        parent::__construct($name);

        $this->config = $config;
        $this->productCollectionFactory = $productCollectionFactory;
        $this->storeRepository = $storeRepository;
        $this->publisher = $publisher;
    }

    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $options = [
            new InputOption(
                self::COUNT_OPTION,
                '-c',
                InputOption::VALUE_OPTIONAL,
                'Count of products default value is '
            )
        ];

        $this->setDefinition($options);

        $this->setName('qr:add-to-queue');
        $this->setDescription(
            'Add product with difrent name and qr code to rabbit queue you  can set products count in queue 
            using parameter --products-count if is not set count will by get from configuration'
        );
        parent::configure();
    }

    /**
     * CLI command description
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $count = (int)$input->getOption(self::COUNT_OPTION);

        if ($count === 0) {
            $count = $this->config->getDefaultQueueCount();
        }

        $idsToQueue = $this->getIdsToQueue($count);
        unset($idsToQueue[0]);

        $this->publisher->publish(QueueInterface::UPDATE_PRODUCT_TOPIC, implode(',', $idsToQueue));
    }

    /**
     * @param int $count
     * @return array
     */
    private function getIdsToQueue(int $count): array
    {
        $idsToQueue = [0];
        $stores = $this->storeRepository->getList();

        foreach ($stores as $store) {
            $productCountToGet = $count - count($idsToQueue) + 1;

            if ($store->getId() == 1) {
                continue;
            }

            if ($productCountToGet <= 0) {
                return $idsToQueue;
            }

            $collection = $this->productCollectionFactory->create()
                ->addFieldToSelect('entity_id')
                ->addFieldToSelect(ProductAttributeInterface::CODE_NAME)
                ->addAttributeToSelect(AttributeInterface::QR_ATTRIBUTE_NAME, 'left')
                ->addStoreFilter($store)
                ->addFieldToFilter('entity_id', ['nin' => $idsToQueue])
                ->addFieldToFilter(
                    ProductAttributeInterface::CODE_NAME,
                    [
                        'neq' => new \Zend_Db_Expr(
                            'IF(at_' . AttributeInterface::QR_ATTRIBUTE_NAME . '.value_id > 0, at_' . AttributeInterface::QR_ATTRIBUTE_NAME . '.value , true)'
                        )
                    ],
                )
                ->setPage(1, $productCountToGet);

            $idsToQueue = array_unique(array_merge($idsToQueue, $collection->getAllIds($productCountToGet)));
        }

        return $idsToQueue;
    }
}
