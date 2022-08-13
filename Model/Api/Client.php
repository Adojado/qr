<?php

declare(strict_types=1);

namespace Adojado\Qr\Model\Api;

use Adojado\Qr\Model\Config;
use Adojado\Qr\Model\Config\Backend\File;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem;
use Magento\Framework\HTTP\Client\Curl;

class Client
{

    const API_HEADERS = [
        'Content-Type' => 'application/json',
        'Accept' => 'application/json'
    ];

    const API_URL = 'https://www.de-vis-software.ro/qrcodeme.aspx';

    /**
     * @var Curl
     * */
    private $curl;

    /**
     * @var Config
     * */
    private $config;

    /**
     * @var Filesystem
     * */
    private $fileSystem;

    public function __construct(Curl $curl, Config $config, Filesystem $filesystem)
    {
        $this->curl = $curl;
        $this->config = $config;
        $this->fileSystem = $filesystem;
    }

    public function call(string $value)
    {
        $this->setHeaders($value);

        $params = [
            'plainText' => $value,
            'color1' => strtolower($this->config->getCodeColor()),
            'color2' => strtolower($this->config->getBackgroundColor())
        ];

        if ($this->config->getLogo() != '') {
            $params['logo'] = base64_encode(
                file_get_contents(
                    $this->fileSystem->getDirectoryRead(DirectoryList::MEDIA)->getAbsolutePath() .
                    File::IMAGE_PATH . '/' .
                    $this->config->getLogo()
                )
            );
        }

        $this->curl->post(self::API_URL, json_encode($params));

        return is_array(json_decode($this->curl->getBody(), true)) ?
            json_decode($this->curl->getBody(), true) :
            [];
    }

    /**
     * @param string $value
     */
    private function setHeaders(string $value): void
    {
        $this->curl->addHeader('Authorization', 'Basic ' . $this->config->getAuthorization());

        foreach (self::API_HEADERS as $header => $value) {
            $this->curl->addHeader($header, $value);
        }
    }
}
