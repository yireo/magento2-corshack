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
     * @var ResponseGenerator
     */
    private $responseGenerator;

    /**
     * ActionPlugin constructor.
     *
     * @param ResponseGenerator $responseGenerator
     */
    public function __construct(
        ResponseGenerator $responseGenerator
    ) {
        $this->responseGenerator = $responseGenerator;
    }

    /**
     * @param Source $source
     * @param $closure
     * @param RequestInterface $request
     *
     * @return HttpResponse
     */
    public function afterDispatch(Source $source, HttpResponse $response)
    {
        $response = $this->responseGenerator->modifyResponse($response);
        return $response;
    }
}
