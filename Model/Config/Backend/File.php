<?php

declare(strict_types=1);

namespace Adojado\Qr\Model\Config\Backend;

use Magento\Config\Model\Config\Backend\File as CoreFile;

/**
 * Gate-Software
 *
 * @copyright Copyright (c) 2022 Gate-Software Sp. z o.o. (www.gate-software.com). All rights reserved.
 * @author    Gate-Software Dev Team
 * @author    adrian.biedrzycki@gate-software.com
 *
 * @package Adojadoj_Qr
 */
class File extends CoreFile
{
    const IMAGE_PATH = 'qr';

    /**
     * @return string[]
     */
    public function getAllowedExtensions(): array
    {
        return ['png', 'jpg', 'jpeg'];
    }
}
