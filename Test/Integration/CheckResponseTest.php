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

namespace Yireo\CorsHack\Test\Integration;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Response\Http;
use Magento\TestFramework\ObjectManager;
use Magento\TestFramework\TestCase\AbstractController as ControllerTestCase;

/**
 * Class CheckResponseTest
 * @package Yireo\CorsHack\Test\Integration
 */
class CheckResponseTest extends ControllerTestCase
{
    /**
     * Test whether any response contains proper headers
     *
     * @magentoConfigFixture default/corshack/settings/origin *
     */
    public function testIfResponseContainsCrossOriginHeaders()
    {
        /** @var ScopeConfigInterface $scopeConfig */
        $scopeConfig = ObjectManager::getInstance()->get(ScopeConfigInterface::class);
        $this->assertSame('*', $scopeConfig->getValue('corshack/settings/origin'));

        $this->dispatch('/graphql');

        /** @var Http $response */
        $response = $this->getResponse();
        $this->assertSame(200, $response->getHttpResponseCode());

        $foundAccessControlAllowOrigin = false;
        $foundAccessControlAllowHeaders = false;

        /** @var \Laminas\Http\Headers $headers */
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
