<?php declare(strict_types=1);

/**
 * CorsHack module for Magento 2
 *
 * @package     Yireo_CorsHack
 * @author      Yireo
 * @copyright   Copyright 2022 Yireo (https://www.yireo.com/)
 * @license     Open Source License
 */

namespace Yireo\CorsHack\Test\Integration;

use Magento\GraphQl\Controller\GraphQl;
use PHPUnit\Framework\TestCase;
use Yireo\CorsHack\Plugin\ControllerPlugin;
use Yireo\IntegrationTestHelper\Test\Integration\Traits\AssertInterceptorPluginIsRegistered;

class PluginTest extends TestCase
{
    use AssertInterceptorPluginIsRegistered;
    
    public function testIfPluginWorks()
    {
        $this->assertInterceptorPluginIsRegistered(
            GraphQl::class,
            ControllerPlugin::class,
            'Yireo_CorsHack::addHeadersToResponse'
        );
    }
}
