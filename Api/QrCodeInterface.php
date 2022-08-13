<?php

declare(strict_types=1);

namespace Adojado\Qr\Api;

/**
 * Gate-Software
 *
 * @copyright Copyright (c) 2022 Gate-Software Sp. z o.o. (www.gate-software.com). All rights reserved.
 * @author    Gate-Software Dev Team
 * @author    adrian.biedrzycki@gate-software.com
 *
 * @package Adojadoj_Qr
 */
interface QrCodeInterface
{
    const KEY_ID = 'entity_id';
    const KEY_PRODUCT_ID = 'product_id';
    const KEY_STORE_ID = 'store_id';
    const KEY_CODE = 'qr_code';
}
