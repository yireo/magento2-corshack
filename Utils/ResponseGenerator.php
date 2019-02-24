<?php
declare(strict_types=1);

namespace Yireo\CorsHack\Utils;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Response\Http;

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
     * @param Http $response
     * @return Http
     */
    public function modifyResponse(Http $response)
    {
        $response->setHeader('Access-Control-Allow-Origin', $this->getAccessControlAllowOrigin(), true);
        $response->setHeader('Access-Control-Allow-Headers', $this->getAccessControlAllowHeaders(), true);
        return $response;
    }

    /**
     * @return string
     */
    private function getAccessControlAllowOrigin(): string
    {
        $allowOrigin = [];
        $allowOrigin[] = 'http://localhost';
        $allowOrigin[] = 'http://localhost:3000';

        $storedOrigins = (string) $this->scopeConfig->getValue('corshack/settings/origin');
        $storedOrigins = explode(',', $storedOrigins);
        foreach ($storedOrigins as $storedOrigin) {
            $storedOrigin = trim($storedOrigin);
            if (!empty($storedOrigin)) {
                $allowOrigin[] = $storedOrigin;
            }
        }

        $allowOrigin = array_unique($allowOrigin);

        // If the wildcard is here, we can remove all other URLs
        if (in_array('*', $allowOrigin)) {
            $allowOrigin = ['*'];
        }

        return implode(', ', $allowOrigin);
    }

    /**
     * @return string
     */
    private function getAccessControlAllowHeaders(): string
    {
        return 'Content-Type';
    }
}
