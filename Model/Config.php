<?php

declare(strict_types=1);

namespace Adojado\Qr\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

/**
 * Gate-Software
 *
 * @copyright Copyright (c) 2022 Gate-Software Sp. z o.o. (www.gate-software.com). All rights reserved.
 * @author    Gate-Software Dev Team
 * @author    adrian.biedrzycki@gate-software.com
 *
 * @package Adojadoj_Qr
 */
class Config
{
    /**
     * @var string
     */
    const XML_PATH_DEFAULT_PRODUCT_COUNT = 'catalog/qr/queue_count';
    const XML_PATH_AUTHORIZATION = 'catalog/qr/authorization';
    const XML_PATH_CODE_COLOR = 'catalog/qr/code_color';
    const XML_PATH_BACKGROUND_COLOR = 'catalog/qr/background_color';
    const XML_PATH_LOGO = 'catalog/qr/logo';

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Get Default queue count
     *
     * @return int
     */
    public function getDefaultQueueCount(): int
    {
        return (int)$this->scopeConfig->getValue(
            self::XML_PATH_DEFAULT_PRODUCT_COUNT,
            ScopeInterface::SCOPE_STORE
        ) ?: 0;
    }

    /**
     * Get authorization to api
     *
     * @return string
     */
    public function getAuthorization(): string
    {
        return (string)$this->scopeConfig->getValue(
            self::XML_PATH_AUTHORIZATION,
            ScopeInterface::SCOPE_STORE
        ) ?: '';
    }

    /**
     * Get code color
     *
     * @return string
     */
    public function getCodeColor(): string
    {
        return (string)$this->scopeConfig->getValue(
            self::XML_PATH_CODE_COLOR,
            ScopeInterface::SCOPE_STORE
        ) ?: '#000000';
    }


    /**
     * Get background color
     *
     * @return string
     */
    public function getBackgroundColor(): string
    {
        return (string)$this->scopeConfig->getValue(
            self::XML_PATH_BACKGROUND_COLOR,
            ScopeInterface::SCOPE_STORE
        ) ?: '#FFFFFF';
    }

    /**
     * Get background color
     *
     * @return string
     */
    public function getLogo(): string
    {
        return (string)$this->scopeConfig->getValue(
            self::XML_PATH_LOGO,
            ScopeInterface::SCOPE_STORE
        ) ?: '';
    }
}
