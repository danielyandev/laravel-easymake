<?php

namespace EasyMake\Tests\Unit;

use EasyMake\Commands\MakeModelCommand;
use PHPUnit\Framework\TestCase;

class MakeModelTest extends TestCase
{
    /**
     * Check if command exists
     *
     * @return void
     */
    public function testCommandExists()
    {
        $this->assertTrue(class_exists(MakeModelCommand::class));
    }
}
