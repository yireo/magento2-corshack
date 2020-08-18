<?php

declare(strict_types=1);

namespace Yireo\CorsHack\Utils;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Serialize\SerializerInterface;

/**
 * Class RequestResponseValidator
 * @package Yireo\CorsHack\Utils
 */
class RequestResponseValidator
{
    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * RequestValidator constructor.
     * @param RequestInterface $request
     * @param SerializerInterface $serializer
     */
    public function __construct(
        RequestInterface $request,
        SerializerInterface $serializer
    ) {
        $this->request = $request;
        $this->serializer = $serializer;
    }

    /**
     * @return bool
     */
    public function validate(ResponseInterface $response): bool
    {
        if ($this->request->getMethod() === 'OPTIONS') {
            return true;
        }

        if ($this->request->getMethod() === 'GET') {
            return true;
        }

        $data = $this->serializer->unserialize($response->getBody());
        if (!empty($data) && !empty($data['data']) && empty($data['errors'])) {
            return true;
        }

        return false;
    }
}
