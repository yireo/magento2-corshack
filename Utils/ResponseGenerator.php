<?php

declare(strict_types=1);

namespace Yireo\CorsHack\Utils;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Response\HttpInterface as HttpResponse;

/**
 * Class ResponseGenerator
 * @package Yireo\CorsHack\Utils
 */
class ResponseGenerator
{
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * HeaderGenerator constructor.
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @param HttpResponse $response
     * @return HttpResponse
     */
    public function modifyResponse(HttpResponse $response): HttpResponse
    {
        $domains = $this->getAccessControlAllowOriginDomains();
        $headers = $this->getAccessControlAllowHeaders();

        //$response->setHeader('X-Yireo-CorsHack', 1); // @todo: Add a setting for this
        $response->setHeader('Access-Control-Allow-Origin', implode(', ', $domains));
        $response->setHeader('Access-Control-Allow-Headers', implode(',', $headers));
        $response->setHeader('Access-Control-Allow-Credentials', 'true');
        $response->setHeader('Access-Control-Allow-Method', 'POST, GET, OPTIONS');
        $response->setHeader('Access-Control-Max-Age', '86400');

        return $response;
    }

    /**
     * @return string
     */
    private function getAccessControlAllowOriginDomains(): array
    {
        $domains = [];

        $storedOrigins = (string)$this->scopeConfig->getValue('corshack/settings/origin');
        $storedOrigins = explode(',', $storedOrigins);
        foreach ($storedOrigins as $storedOrigin) {
            $storedOrigin = trim($storedOrigin);
            if (!empty($storedOrigin)) {
                $domains[] = $storedOrigin;
            }
        }

        $domains = array_unique($domains);

        if (count($domains) > 1) {
            $domains = ['*'];
        }

        return $domains;
    }

    /**
     * @return array
     */
    private function getAccessControlAllowHeaders(): array
    {
        $headers = [];
        //$headers[] = 'Overwrite';
        //$headers[] = 'Destination';
        //$headers[] = 'Depth';
        $headers[] = 'Content-Type';
        //$headers[] = 'User-Agent';
        //$headers[] = 'X-File-Size';
        //$headers[] = 'X-Requested-With';
        //$headers[] = 'If-Modified-Since';
        //$headers[] = 'X-File-Name';
        //$headers[] = 'Cache-Control';
        $headers[] = 'Authorization';

        return $headers;
    }
}
