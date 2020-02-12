<?php

namespace EasyMake\Tests\Unit;

use PHPUnit\Framework\TestCase;

class MakeModelTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testCommandExists()
    {
        $exists = class_exists(\EasyMake\Commands\MakeModelCommand::class, false);
        $this->assertTrue($exists);
    }
}
