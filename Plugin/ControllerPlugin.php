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

use Magento\Framework\App\Response\Http as HttpResponse;
use Magento\Framework\App\Response\HttpInterface;
use Magento\GraphQl\Controller\GraphQl as Source;
use Yireo\CorsHack\Utils\ResponseGenerator;
use Laminas\Http\Request;

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
     * @var Request
     */
    private $request;

    /**
     * ActionPlugin constructor.
     *
     * @param ResponseGenerator $responseGenerator
     * @param Request $request
     */
    public function __construct(
        ResponseGenerator $responseGenerator,
        Request $request
    ) {
        $this->responseGenerator = $responseGenerator;
        $this->request = $request;
    }

    /**
     * @param Source $source
     * @param HttpResponse $response
     * @return HttpInterface
     */
    public function afterDispatch(Source $source, HttpResponse $response)
    {
        if ($this->spoofStatusHeaderIgnorantly($response)) {
            $response->setStatusHeader(200);
        }

        return $this->responseGenerator->modifyResponse($response);
    }

    /**
     * @param HttpResponse $response
     * @return bool
     */
    private function spoofStatusHeaderIgnorantly(HttpResponse $response): bool
    {
        if ($this->request->getMethod() === 'OPTIONS') {
            return true;
        }

        if ($this->request->getMethod() === 'GET') {
            return true;
        }

        $data = json_decode($response->getBody(), true);
        if (!empty($data) && !empty($data['data']) && empty($data['errors'])) {
            return true;
        }

        return false;
    }
}
