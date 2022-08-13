<?php

declare(strict_types=1);

namespace Adojado\Qr\Block\Catalog\Product;

use Adojado\Qr\Api\QrCodeInterface;
use Adojado\Qr\Model\ResourceModel\QrCode\CollectionFactory;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Block\Product\Context;
use Magento\Catalog\Block\Product\View;
use Magento\Catalog\Helper\Product;
use Magento\Catalog\Model\ProductTypes\ConfigInterface;
use Magento\Customer\Model\Session;
use Magento\Framework\Json\EncoderInterface;
use Magento\Framework\Locale\FormatInterface;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Framework\Stdlib\StringUtils;
use Magento\Framework\Url\EncoderInterface as UrlEncoder;

/**
 * Gate-Software
 *
 * @copyright Copyright (c) 2022 Gate-Software Sp. z o.o. (www.gate-software.com). All rights reserved.
 * @author    Gate-Software Dev Team
 * @author    adrian.biedrzycki@gate-software.com
 *
 * @package Adojadoj_Qr
 */
class QrCode extends View
{
    /**
     * @var CollectionFactory
     * */
    private $collectionFactory;

    /**
     * @param Context $context
     * @param UrlEncoder $urlEncoder
     * @param EncoderInterface $jsonEncoder
     * @param StringUtils $string
     * @param Product $productHelper
     * @param ConfigInterface $productTypeConfig
     * @param FormatInterface $localeFormat
     * @param Session $customerSession
     * @param ProductRepositoryInterface $productRepository
     * @param PriceCurrencyInterface $priceCurrency
     * @param CollectionFactory $collectionFactory
     * @param array $data
     */
    public function __construct(
        Context $context,
        UrlEncoder $urlEncoder,
        EncoderInterface $jsonEncoder,
        StringUtils $string,
        Product $productHelper,
        ConfigInterface $productTypeConfig,
        FormatInterface $localeFormat,
        Session $customerSession,
        ProductRepositoryInterface $productRepository,
        PriceCurrencyInterface $priceCurrency,
        CollectionFactory $collectionFactory,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $urlEncoder,
            $jsonEncoder,
            $string,
            $productHelper,
            $productTypeConfig,
            $localeFormat,
            $customerSession,
            $productRepository,
            $priceCurrency,
            $data
        );

        $this->collectionFactory = $collectionFactory;
    }

    /**
     * getQrBase64 method
     *
     * @return string
     */
    public function getQrBase64(): string
    {
        $collection = $this->collectionFactory->create()
            ->addFieldToFilter(QrCodeInterface::KEY_PRODUCT_ID, ['eq' => $this->getProduct()->getId()])
            ->addFieldToFilter(QrCodeInterface::KEY_STORE_ID, ['eq' => $this->getProduct()->getStoreId()]);

        if (!$collection->count()) {
            return '';
        }

        return (string)$collection->getFirstItem()->getData(QrCodeInterface::KEY_CODE);
    }
}
