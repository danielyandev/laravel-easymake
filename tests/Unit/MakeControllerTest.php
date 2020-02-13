<?php

namespace EasyMake\Tests\Unit;

use EasyMake\Commands\MakeControllerCommand;
use EasyMake\Tests\TestCase;

class MakeControllerTest extends TestCase
{
    /**
     * Where to place models created by test
     */
    public const TEST_CONTROLLER_DIR = 'TestControllers';

    /**
     * Created test model namespace
     */
    public const TEST_CONTROLLER_NAMESPACE = '\\App\\Http\\Controllers\\TestControllers\\';

    /**
     * Check if command class exists
     */
    public function testCommandExists()
    {
        $this->assertTrue(class_exists(MakeControllerCommand::class));
    }

    /**
     * Create controller two times
     */
    public function testCreateController()
    {
        $this->artisan('easymake:controller', ['name' => self::TEST_CONTROLLER_DIR . '/TestController'])->expectsOutput('Controller created successfully.');
        $this->artisan('easymake:controller', ['name' => self::TEST_CONTROLLER_DIR . '/TestController'])->expectsOutput('Controller already exists!');
    }

    /**
     * Create resource controller with model
     */
    public function testResourceModelController()
    {
        $this->artisan('easymake:controller', [
            'name' => self::TEST_CONTROLLER_DIR . '/TestResourceModelController',
            '--model' => 'User'
        ])
        ->expectsOutput('Controller created successfully.');

        $controller = self::TEST_CONTROLLER_NAMESPACE . 'TestResourceModelController';

        $this->assertTrue(method_exists(new $controller, 'index'));
        $this->assertTrue(method_exists(new $controller, 'create'));
        $this->assertTrue(method_exists(new $controller, 'store'));
        $this->assertTrue(method_exists(new $controller, 'show'));
        $this->assertTrue(method_exists(new $controller, 'edit'));
        $this->assertTrue(method_exists(new $controller, 'update'));
        $this->assertTrue(method_exists(new $controller, 'destroy'));
    }

    /**
     * Create api controller with model
     */
    public function testApiModelController()
    {
        $this->artisan('easymake:controller', [
            'name' => self::TEST_CONTROLLER_DIR . '/TestApiModelController',
            '--model' => 'User',
            '--api' => true
        ])
            ->expectsOutput('Controller created successfully.');

        $controllerNamespace = self::TEST_CONTROLLER_NAMESPACE . 'TestApiModelController';
        $controller = new $controllerNamespace;

        $this->assertTrue(method_exists($controller, 'index'));
        $this->assertTrue(method_exists($controller, 'store'));
        $this->assertTrue(method_exists($controller, 'show'));
        $this->assertTrue(method_exists($controller, 'update'));
        $this->assertTrue(method_exists($controller, 'destroy'));

        $this->assertFalse(method_exists($controller, 'create'));
        $this->assertFalse(method_exists($controller, 'edit'));

    }
}
