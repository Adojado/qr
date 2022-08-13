<?php

declare(strict_types=1);

namespace Adojado\Qr\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Gate-Software
 *
 * @copyright Copyright (c) 2022 Gate-Software Sp. z o.o. (www.gate-software.com). All rights reserved.
 * @author    Gate-Software Dev Team
 * @author    adrian.biedrzycki@gate-software.com
 *
 * @package Adojadoj_Qr
 */
class QrCode extends AbstractDb
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'qr_code_resource_model';

    /**
     * @inheritdoc
     */
    protected function _construct()
    {
        $this->_init('qr_code', 'entity_id');
        $this->_useIsObjectNew = true;
    }
}
