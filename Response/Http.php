<?php declare(strict_types=1);

namespace Yireo\CorsHack\Response;

use Magento\Framework\App\Http\Context;
use Magento\Framework\App\Request\Http as HttpRequest;
use Magento\Framework\Session\Config\ConfigInterface;
use Magento\Framework\Stdlib\Cookie\CookieMetadataFactory;
use Magento\Framework\Stdlib\CookieManagerInterface;
use Magento\Framework\Stdlib\DateTime;
use Yireo\CorsHack\Utils\RequestResponseValidator;
use Yireo\CorsHack\Utils\ResponseGenerator;

class Http extends \Magento\Framework\App\Response\Http
{
    private ResponseGenerator $responseGenerator;
    private RequestResponseValidator $requestResponseValidator;

    public function __construct(
        HttpRequest $request,
        CookieManagerInterface $cookieManager,
        CookieMetadataFactory $cookieMetadataFactory,
        Context $context,
        DateTime $dateTime,
        ResponseGenerator $responseGenerator,
        RequestResponseValidator $requestResponseValidator,
        ConfigInterface $sessionConfig = null
    ) {
        parent::__construct($request, $cookieManager, $cookieMetadataFactory, $context, $dateTime, $sessionConfig);
        $this->responseGenerator = $responseGenerator;
        $this->requestResponseValidator = $requestResponseValidator;
    }

    public function representJson($content)
    {
        if ($this->requestResponseValidator->validate($this)) {
            $this->setStatusHeader(200);
        }

        $this->responseGenerator->modifyResponse($this);

        return parent::representJson($content);
    }
}