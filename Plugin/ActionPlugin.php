<?php
declare(strict_types=1);

namespace Yireo\CorsHack\Plugin;

use Magento\Framework\App\Request\Http;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\App\Action\Action as Source;
use Magento\Framework\App\Response\Http as Response;

/**
 * Class ActionPlugin
 * @package Yireo\CorsHack\Plugin
 */
class ActionPlugin
{
    /**
     * @var ResponseInterface
     */
    private $response;

    /**
     * ActionPlugin constructor.
     * @param Response $response
     */
    public function __construct(
        Response $response
    ) {
        $this->response = $response;
    }

    /**
     * @param Source $source
     * @param $closure
     * @param RequestInterface $request
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
     * @todo Make a configuration option for this
     * @return string
     */
    private function getAccessControlAllowOrigin()
    {
        return 'http://localhost:3000';
    }

    /**
     * @todo Make a configuration option for this
     * @return string
     */
    private function getAccessControlAllowHeaders()
    {
        return 'Content-Type';
    }
}