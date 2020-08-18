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
        $response->setHeader('X-Yireo-CorsHack', 1);

        $domains = $this->getAccessControlAllowOriginDomains();
        $response->setHeader('Access-Control-Allow-Origin', implode(', ', $domains));

        $headers = $this->getAccessControlAllowHeaders();
        $response->setHeader('Access-Control-Allow-Headers', implode(',', $headers), true);
        $response->setHeader('Access-Control-Allow-Credentials', 'true');

        return $response;
    }

    /**
     * @return string
     */
    private function getAccessControlAllowOriginDomains(): array
    {
        $domains = [];

        $storedOrigins = (string) $this->scopeConfig->getValue('corshack/settings/origin');
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
        $headers[] = 'Content-Type';
        $headers[] = 'Authorization';

        return $headers;
    }
}
