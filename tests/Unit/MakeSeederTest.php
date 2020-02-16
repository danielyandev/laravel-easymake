<?php


namespace EasyMake\Tests\Unit;


use EasyMake\Commands\MakeSeederCommand;
use EasyMake\Tests\TestCase;

class MakeSeederTest extends TestCase
{
    /**
     * Where to place seeder created by test
     */
    protected const TEST_SEEDER_DIR = '';

    /**
     * Check if command class exists
     */
    public function testCommandExists()
    {
        $this->assertTrue(class_exists(MakeSeederCommand::class));
    }

    /**
     * Create seeder two times
     */
    public function testCreateSeeder()
    {
        $this->artisan('easymake:seeder', ['name' => self::TEST_SEEDER_DIR . '/TestTableSeeder'])->expectsOutput('Seeder created successfully.');
        $this->artisan('easymake:seeder', ['name' => self::TEST_SEEDER_DIR . '/TestTableSeeder'])->expectsOutput('Seeder already exists!');
    }

    /**
     * Create seeder with specified table name
     */
    public function testCreateSeederWithTable()
    {
        $this->artisan('easymake:seeder',[
            'name' => self::TEST_SEEDER_DIR . '/TestTableSeederWithTable',
            '--table' => 'test_table'
        ])
        ->expectsOutput('Seeder created successfully.');
    }
}
