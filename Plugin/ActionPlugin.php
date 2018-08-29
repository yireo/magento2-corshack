<?php
/**
 * CorsHack module for Magento 2
 *
 * @package     Yireo_CorsHack
 * @author      Yireo
 * @copyright   Copyright 2018 Yireo (https://www.yireo.com/)
 * @license     Open Source License
 */

declare(strict_types=1);

namespace Yireo\CorsHack\Plugin;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Request\Http;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\App\Action\Action as Source;
use Magento\Framework\App\Response\Http as Response;

/**
 * Class ActionPlugin
 *
 * @package Yireo\CorsHack\Plugin
 */
class ActionPlugin
{
    /**
     * @var ResponseInterface
     */
    private $response;

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * ActionPlugin constructor.
     *
     * @param Response $response
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        Response $response,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->response = $response;
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @param Source $source
     * @param $closure
     * @param RequestInterface $request
     *
     * @return Response
     */
    public function aroundDispatch(Source $source, $closure, RequestInterface $request)
    {
        /** @var $request Http */
        if ($request->isOptions()) {
            $this->response->setHttpResponseCode(200);
            return $this->response;
        }

        /** @var Response $response */
        $response = $closure($request);
        $response->setHeader('Access-Control-Allow-Origin', $this->getAccessControlAllowOrigin(), true);
        $response->setHeader('Access-Control-Allow-Headers', $this->getAccessControlAllowHeaders(), true);

        return $response;
    }

    /**
     * @return string
     */
    private function getAccessControlAllowOrigin()
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


        return implode(', ', $allowOrigin);
    }

    /**
     * @return string
     */
    private function getAccessControlAllowHeaders()
    {
        return 'Content-Type';
    }
}
