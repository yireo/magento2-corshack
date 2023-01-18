<?php declare(strict_types=1);

/**
 * CorsHack module for Magento 2
 *
 * @package     Yireo_CorsHack
 * @author      Yireo
 * @copyright   Copyright 2023 Yireo (https://www.yireo.com/)
 * @license     Open Source License
 */

namespace Yireo\CorsHack\Test\Integration;

use Laminas\Http\Headers;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\ResponseInterface;
use Magento\GraphQl\Controller\GraphQl;
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
     * @magentoAppArea graphql
     * @magentoDbIsolation disabled
     */
    public function testIfResponseContainsCrossOriginHeaders()
    {
        $this->assertCorsHackOrigin('*');
        $response = $this->sendGraphQlRequest();

        /** @var Headers $headers */
        $headers = $response->getHeaders();
        $headersDump = var_export($this->getHeadersAsStringArray($headers), true);
        $this->assertSame(200, $response->getHttpResponseCode(), $headersDump);
        $this->assertHttpHeaderContains($headers, 'Access-Control-Allow-Origin');
        $this->assertHttpHeaderContains($headers, 'Access-Control-Allow-Headers');
    }


    /**
     * Test whether any response contains proper headers
     *
     * @magentoConfigFixture default/corshack/settings/origin *
     * @magentoAppArea graphql
     * @magentoDbIsolation disabled
     * @magentoCache full_page enabled
     */
    public function testIfResponseContainsCrossOriginHeadersWithFpcEnabled()
    {
        $this->assertCorsHackOrigin('*');
        $response = $this->sendGraphQlRequest();

        $this->assertSame(200, $response->getHttpResponseCode());
        $headers = $response->getHeaders();
        $this->assertHttpHeaderContains($headers, 'Access-Control-Allow-Origin');
        $this->assertHttpHeaderContains($headers, 'Access-Control-Allow-Headers');

        // Redo the same request but now with FPC already warmed up
        // @todo: Double-check to see if caching headers are present
        $this->dispatch('/graphql');
        $response = $this->getResponse();
        $this->assertSame(200, $response->getHttpResponseCode());
        $headers = $response->getHeaders();
        $this->assertHttpHeaderContains($headers, 'Access-Control-Allow-Origin');
        $this->assertHttpHeaderContains($headers, 'Access-Control-Allow-Headers');
    }

    private function sendGraphQlRequest(): ResponseInterface
    {
        $request = $this->getRequest();

        $headers = ObjectManager::getInstance()->create(Headers::class);
        $headers->addHeaderLine('Content-Type', 'application/json');
        $request->setHeaders($headers);

        $query = $this->getQuery();
        $data = ['query' => $query];
        $content = json_encode($data);
        $request->setContent($content);
        $request->setMethod('POST');
        $request->setPathInfo('/graphql');

        $graphql = ObjectManager::getInstance()->get(GraphQl::class);
        return $graphql->dispatch($request);
    }

    /**
     * @param string $expected
     * @return void
     */
    private function assertCorsHackOrigin(string $expected)
    {
        /** @var ScopeConfigInterface $scopeConfig */
        $scopeConfig = ObjectManager::getInstance()->get(ScopeConfigInterface::class);
        $this->assertSame($expected, $scopeConfig->getValue('corshack/settings/origin'));
    }

    /**
     * @param Headers $headers
     * @param string $expectedHeader
     * @return void
     */
    private function assertHttpHeaderContains(Headers $headers, string $expectedHeader)
    {
        $foundHeader = false;
        $headersAsString = [];
        foreach ($headers as $header) {
            $headersAsString[] = $header->getFieldName();
            if ($header->getFieldName() === $expectedHeader) {
                $foundHeader = true;
            }
        }

        $this->assertTrue($headersAsString > 0);
        $msg = $header . ' not found: ' . var_export($headersAsString, true);
        $this->assertTrue($foundHeader, $msg);
    }

    /**
     * @param Headers $headers
     * @return array
     */
    private function getHeadersAsStringArray(Headers $headers): array
    {
        $headersAsString = [];
        foreach ($headers as $header) {
            $headersAsString[] = $header->getFieldName() . ': ' . $header->getFieldValue();
        }

        return $headersAsString;
    }

    /**
     * @return string
     */
    private function getQuery(): string
    {
        return <<<END
query IntrospectionQuery {
  __schema {
    mutationType {
      fields {
        name
      }
    }
  }
}
END;
    }
}
