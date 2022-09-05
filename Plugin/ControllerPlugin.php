<?php declare(strict_types=1);
/**
 * CorsHack module for Magento 2
 *
 * @package     Yireo_CorsHack
 * @author      Yireo
 * @copyright   Copyright 2018 Yireo (https://www.yireo.com/)
 * @license     Open Source License
 */

namespace Yireo\CorsHack\Plugin;

use Magento\Framework\App\Response\Http as HttpResponse;
use Magento\Framework\App\Response\HttpInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\GraphQl\Controller\GraphQl as Source;
use Yireo\CorsHack\Utils\RequestResponseValidator;
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
     * @var RequestResponseValidator
     */
    private $requestResponseValidator;

    /**
     * ActionPlugin constructor.
     *
     * @param ResponseGenerator $responseGenerator
     * @param RequestResponseValidator $requestResponseValidator
     */
    public function __construct(
        ResponseGenerator $responseGenerator,
        RequestResponseValidator $requestResponseValidator
    ) {
        $this->responseGenerator = $responseGenerator;
        $this->requestResponseValidator = $requestResponseValidator;
    }

    /**
     * @param Source $source
     * @param HttpResponse $response
     * @return HttpInterface
     */
    public function afterDispatch(Source $source, ResponseInterface $response)
    {
        if ($this->requestResponseValidator->validate($response)) {
            $response->setStatusHeader(200);
        }

        return $this->responseGenerator->modifyResponse($response);
    }
}
