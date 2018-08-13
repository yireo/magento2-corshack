<?php
declare(strict_types=1);

namespace Yireo\CorsHack\Test\Integration;

use Magento\Framework\App\Response\Http;
use Magento\TestFramework\TestCase\AbstractController as ControllerTestCase;

/**
 * Class CheckResponseTest
 * @package Yireo\CorsHack\Test\Integration
 */
class CheckResponseTest extends ControllerTestCase
{
    /**
     * Test whether any response contains proper headers
     */
    public function testIfResponseContainsCrossOriginHeaders()
    {
        $this->dispatch('/');

        /** @var Http $response */
        $response = $this->getResponse();
        $this->assertSame(200, $response->getHttpResponseCode());

        $foundAccessControlAllowOrigin = false;
        $foundAccessControlAllowHeaders = false;

        $headers = $response->getHeaders();
        foreach ($headers as $header) {
            if ($header->getFieldName() === 'Access-Control-Allow-Origin') {
                $foundAccessControlAllowOrigin = true;
            }

            if ($header->getFieldName() === 'Access-Control-Allow-Headers') {
                $foundAccessControlAllowHeaders = true;
            }
        }

        $this->assertTrue($foundAccessControlAllowOrigin);
        $this->assertTrue($foundAccessControlAllowHeaders);
    }
}