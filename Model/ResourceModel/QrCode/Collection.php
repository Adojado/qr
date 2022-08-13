<?php

declare(strict_types=1);

namespace Adojado\Qr\Model\ResourceModel\QrCode;

use Adojado\Qr\Model\QrCode as Model;
use Adojado\Qr\Model\ResourceModel\QrCode as ResourceModel;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Gate-Software
 *
 * @copyright Copyright (c) 2022 Gate-Software Sp. z o.o. (www.gate-software.com). All rights reserved.
 * @author    Gate-Software Dev Team
 * @author    adrian.biedrzycki@gate-software.com
 *
 * @package Adojadoj_Qr
 */
class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'qr_code_collection';

    /**
     * @inheritdoc
     */
    protected function _construct()
    {
        $this->_init(Model::class, ResourceModel::class);
    }
}
