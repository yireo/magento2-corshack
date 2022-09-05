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

use PHPUnit\Framework\TestCase;
use Yireo\IntegrationTestHelper\Test\Integration\Traits\AssertModuleIsEnabled;
use Yireo\IntegrationTestHelper\Test\Integration\Traits\AssertModuleIsRegistered;
use Yireo\IntegrationTestHelper\Test\Integration\Traits\AssertModuleIsRegisteredForReal;

class ModuleTest extends TestCase
{
    use AssertModuleIsEnabled;
    use AssertModuleIsRegistered;
    use AssertModuleIsRegisteredForReal;
    
    public function testIfModuleWorks()
    {
        $this->assertModuleIsEnabled('Yireo_CorsHack');
        $this->assertModuleIsRegistered('Yireo_CorsHack');
        $this->assertModuleIsRegisteredForReal('Yireo_CorsHack');
    }
}