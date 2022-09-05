<?php declare(strict_types=1);

/**
 * CorsHack module for Magento 2
 *
 * @package     Yireo_CorsHack
 * @author      Yireo
 * @copyright   Copyright 2018 Yireo (https://www.yireo.com/)
 * @license     Open Source License
 */

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
     * @magentoAppArea frontend
     */
    public function testIfResponseContainsCrossOriginHeaders()
    {
        /** @var ScopeConfigInterface $scopeConfig */
        $scopeConfig = ObjectManager::getInstance()->get(ScopeConfigInterface::class);
        $this->assertSame('*', $scopeConfig->getValue('corshack/settings/origin'));
        
        $query = <<<END
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
        
        $this->getRequest()->setParams(['query' => $query]);
        $this->dispatch('/graphql');
        
        /** @var Http $response */
        $response = $this->getResponse();
        $this->assertSame(200, $response->getHttpResponseCode());
        
        $foundAccessControlAllowOrigin = false;
        $foundAccessControlAllowHeaders = false;
        
        /** @var \Laminas\Http\Headers $headers */
        $headers = $response->getHeaders();
        $headersAsString = [];
        foreach ($headers as $header) {
            $headersAsString[] = $header->getFieldName();
            if ($header->getFieldName() === 'Access-Control-Allow-Origin') {
                $foundAccessControlAllowOrigin = true;
            }
            
            if ($header->getFieldName() === 'Access-Control-Allow-Headers') {
                $foundAccessControlAllowHeaders = true;
            }
        }
        
        $this->assertTrue($headersAsString > 0);
        $msg = 'Access-Control-Allow-Origin not found: ' . var_export($headersAsString, true);
        $this->assertTrue($foundAccessControlAllowOrigin, $msg);
        $msg = 'Access-Control-Allow-Headers not found: ' . var_export($headersAsString, true) ;
        $this->assertTrue($foundAccessControlAllowHeaders, $msg);
    }
}
