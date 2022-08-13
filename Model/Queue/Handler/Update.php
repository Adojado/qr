<?php

declare(strict_types=1);

namespace Adojado\Qr\Model\Queue\Handler;

use Adojado\Qr\Api\AttributeInterface;
use Adojado\Qr\Api\QrCodeInterface;
use Adojado\Qr\Model\Api\Client;
use Adojado\Qr\Model\QrCodeFactory;
use Adojado\Qr\Model\ResourceModel\QrCode\CollectionFactory as QrCollectionFactory;
use Exception;
use Magento\Catalog\Api\Data\ProductAttributeInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\Product\Action;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Store\Api\Data\StoreInterface;
use Magento\Store\Api\StoreRepositoryInterface;
use Adojado\Qr\Model\ResourceModel\QrCode\Collection as QrCollection;

/**
 * Gate-Software
 *
 * @copyright Copyright (c) 2022 Gate-Software Sp. z o.o. (www.gate-software.com). All rights reserved.
 * @author    Gate-Software Dev Team
 * @author    adrian.biedrzycki@gate-software.com
 *
 * @package Adojadoj_Qr
 */
class Update
{
    /**
     * @var StoreRepositoryInterface
     * */
    private $storeRepository;

    /**
     * @var CollectionFactory
     * */
    private $productCollectionFactory;

    /**
     * @var Action
     * */
    private $action;

    /**
     * @var ProductRepositoryInterface
     * */
    private $productRepository;

    /**
     * @var QrCodeFactory
     * */
    private $qrCodeFactory;

    /**
     * @var QrCollectionFactory
     * */
    private $qrCollectionFactory;

    /**
     * @var Client
     * */
    private $client;

    /**
     * @param CollectionFactory $productCollectionFactory
     * @param StoreRepositoryInterface $storeRepository
     * @param ProductRepositoryInterface $productRepository
     * @param QrCodeFactory $qrCodeFactory
     * @param QrCollectionFactory $qrCollectionFactory
     * @param Client $client
     */
    public function __construct(
        CollectionFactory $productCollectionFactory,
        StoreRepositoryInterface $storeRepository,
        ProductRepositoryInterface $productRepository,
        QrCodeFactory $qrCodeFactory,
        QrCollectionFactory $qrCollectionFactory,
        Client $client
    ) {
        $this->productCollectionFactory = $productCollectionFactory;
        $this->storeRepository = $storeRepository;
        $this->productRepository = $productRepository;
        $this->qrCodeFactory = $qrCodeFactory;
        $this->qrCollectionFactory = $qrCollectionFactory;
        $this->client = $client;
    }

    /**
     * execute method
     *
     * @param string$request
     * @return void
     * */
    public function execute(string $request): void
    {
        $productIds = explode(',', $request);
        $stores = $this->storeRepository->getList();

        foreach ($productIds as $productId) {
            foreach ($stores as $store) {
                $productName = $this->getProductName($store, $productId);

                $product = $this->productRepository->getById($productId, true, $store->getId())
                    ->setCustomAttribute(AttributeInterface::QR_ATTRIBUTE_NAME, $productName);

                $this->productRepository->save($product);

                $result = $this->client->call($productName);


                if (!isset($result['base64QRCode'])) {
                    return;
                }

                $qrCollection = $this->getQrCodeCollection($productId, $store);

                if (!$qrCollection->count()) {
                    $this->saveQrCode((int)$productId, $store, $result['base64QRCode']);
                    return;
                }

                $this->updateQrCode($qrCollection, $result['base64QRCode']);
            }
        }
    }

    /**
     * @param StoreInterface $store
     * @param string $productId
     * @return string
     */
    private function getProductName(StoreInterface $store, string $productId): string
    {
        return (string) $this->productCollectionFactory->create()
            ->addFieldToSelect('entity_id')
            ->addFieldToSelect(ProductAttributeInterface::CODE_NAME)
            ->addStoreFilter($store)
            ->addFieldToFilter('entity_id', ['eq' => $productId])
            ->getFirstItem()
            ->getData(ProductAttributeInterface::CODE_NAME) ?: '';
    }

    /**
     * getQrCodeCollection method
     *
     * @param string $productId
     * @param StoreInterface $store
     * @return QrCollection
     */
    private function getQrCodeCollection(string $productId, StoreInterface $store): QrCollection
    {
        return $this->qrCollectionFactory->create()
            ->addFieldToFilter(QrCodeInterface::KEY_PRODUCT_ID, ['eq' => $productId])
            ->addFieldToFilter(QrCodeInterface::KEY_STORE_ID, ['eq' => $store->getId()]);
    }

    /**
     * saveQrCode method
     *
     * @param int $productId
     * @param StoreInterface $store
     * @param string $baseQRCode
     * @throws Exception
     */
    private function saveQrCode(int $productId, StoreInterface $store, string $baseQRCode): void
    {
        $this->qrCodeFactory->create()
            ->setData(QrCodeInterface::KEY_PRODUCT_ID, $productId)
            ->setData(QrCodeInterface::KEY_STORE_ID, $store->getId())
            ->setData(QrCodeInterface::KEY_CODE, $baseQRCode)
            ->save();
    }

    /**
     * updateQrCode method
     *
     * @param QrCollection $qrCollection
     * @param string $baseQRCode
     */
    private function updateQrCode(QrCollection $qrCollection, string $baseQRCode): void
    {
        $this->qrCodeFactory->create()
            ->load($qrCollection->getFirstItem()->getData(QrCodeInterface::KEY_ID))
            ->setData(QrCodeInterface::KEY_CODE, $baseQRCode)
            ->save();
    }
}
