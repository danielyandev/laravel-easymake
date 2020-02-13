<?php

namespace EasyMake\Tests\Unit;

use EasyMake\Commands\MakeEventCommand;
use EasyMake\Tests\TestCase;

class MakeEventTest extends TestCase
{
    /**
     * Where to place models created by test
     */
    public const TEST_EVENT_DIR = 'TestEvents';

    /**
     * Created test model namespace
     */
    public const TEST_EVENT_NAMESPACE = '\\App\\Events\\TestEvents\\';

    /**
     * Check if command class exists
     */
    public function testCommandExists()
    {
        $this->assertTrue(class_exists(MakeEventCommand::class));
    }

    /**
     * Create event with all options
     */
    public function testCreateEvent()
    {
        $this->artisan('easymake:event', [
            'name' => self::TEST_EVENT_DIR . '/TestEvent',
            '--channel' => 'presence',
            '--channelName' => 'easy-channel',
        ])
        ->expectsOutput('Event created successfully.');
    }

    /**
     * Test if created event implements ShouldBroadcast interface
     */
    public function testImplements()
    {
        $this->artisan('easymake:event', [
            'name' => self::TEST_EVENT_DIR . '/TestEventImplements',
            '--shb' => true
        ])
        ->expectsOutput('Event created successfully.');

        $event = self::TEST_EVENT_NAMESPACE . 'TestEventImplements';
        $implements = class_implements(new $event);
        $this->assertTrue(in_array(\Illuminate\Contracts\Broadcasting\ShouldBroadcast::class, $implements));
        $this->assertCount(1, $implements);
    }
}
