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
        foreach ($domains as $domain) {
            $response->setHeader('Access-Control-Allow-Origin', $domain);
        }

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
        $domains[] = 'http://localhost';
        $domains[] = 'http://localhost:3000';

        $storedOrigins = (string) $this->scopeConfig->getValue('corshack/settings/origin');
        $storedOrigins = explode(',', $storedOrigins);
        foreach ($storedOrigins as $storedOrigin) {
            $storedOrigin = trim($storedOrigin);
            if (!empty($storedOrigin)) {
                $domains[] = $storedOrigin;
            }
        }

        $domains = array_unique($domains);

        // If the wildcard is here, we can remove all other URLs
        if (in_array('*', $domains)) {
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
