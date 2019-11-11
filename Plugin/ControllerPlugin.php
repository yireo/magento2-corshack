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

use Magento\Framework\App\Request\Http;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\Response\Http as HttpResponse;
use Magento\Framework\App\ResponseInterface;
use Magento\GraphQl\Controller\GraphQl as Source;
use Magento\Framework\App\Response\Http as Response;
use Yireo\CorsHack\Utils\ResponseGenerator;

/**
 * Class ControllerPlugin
 *
 * @package Yireo\CorsHack\Plugin
 */
class ControllerPlugin
{
    /**
     * @var ResponseInterface
     */
    private $response;

    /**
     * @var ResponseGenerator
     */
    private $responseGenerator;

    /**
     * ActionPlugin constructor.
     *
     * @param Response $response
     * @param ResponseGenerator $responseGenerator
     */
    public function __construct(
        Response $response,
        ResponseGenerator $responseGenerator
    ) {
        $this->response = $response;
        $this->responseGenerator = $responseGenerator;
    }

    /**
     * @param Source $source
     * @param $closure
     * @param RequestInterface $request
     *
     * @return HttpResponse
     */
    public function aroundDispatch(Source $source, $closure, RequestInterface $request)
    {
        /** @var $request Http */
        if ($request->isOptions()) {
            $this->response = $this->responseGenerator->modifyResponse($this->response);
            $this->response->setHttpResponseCode(200);
            return $this->response;
        }

        /** @var HttpResponse $response */
        $response = $closure($request);
        $response = $this->responseGenerator->modifyResponse($response);

        return $response;
    }
}
