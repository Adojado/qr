<?php

namespace Adojado\Qr\Model;

use Adojado\Qr\Model\ResourceModel\QrCode as ResourceModel;
use Magento\Framework\Model\AbstractModel;

/**
 * Gate-Software
 *
 * @copyright Copyright (c) 2022 Gate-Software Sp. z o.o. (www.gate-software.com). All rights reserved.
 * @author    Gate-Software Dev Team
 * @author    adrian.biedrzycki@gate-software.com
 *
 * @package Adojadoj_Qr
 */
class QrCode extends AbstractModel
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'qr_code_model';

    /**
     * @inheritdoc
     */
    protected function _construct()
    {
        $this->_init(ResourceModel::class);
    }
}
