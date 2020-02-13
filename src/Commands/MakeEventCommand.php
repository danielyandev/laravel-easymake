<?php


namespace EasyMake\Commands;


use Illuminate\Foundation\Console\EventMakeCommand;
use Symfony\Component\Console\Input\InputOption;

class MakeEventCommand extends EventMakeCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'easymake:event';

    /**
     * Default channel class
     *
     * @var string
     */
    protected $defaultChannel = 'PrivateChannel';

    /**
     * Default channel name
     *
     * @var string
     */
    protected $defaultChannelName = 'channel-name';

    /**
     * Channel types available for creation
     *
     * @var array
     */
    protected $availableChannels = ['private', 'presence', 'channel'];

    /**
     * Created event default implementation class
     *
     * @var string
     */
    protected $defaultImplements = '';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__.'/../../stubs/event/event.stub';
    }

    /**
     * Build the class with the given name.
     *
     * @param  string  $name
     * @return string
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function buildClass($name)
    {
        $stub = $this->files->get($this->getStub());

        return $this->replaceNamespace($stub, $name)
                    ->replaceChannelClass($stub)
                    ->replaceChannelName($stub)
                    ->replaceImplements($stub)
                    ->replaceClass($stub, $name);
    }

    /**
     * @param $stub
     * @return $this
     */
    protected function replaceChannelClass(&$stub)
    {
        $name = strtolower($this->option('channel'));

        if ($name){
            if (!in_array($name, $this->availableChannels)){
                $this->error('channel option must be one of these - '. implode(', ', $this->availableChannels));
            }

            switch ($name){
                case 'channel':
                    $name = 'Channel';
                    break;
                case 'private':
                    $name = 'PrivateChannel';
                    break;
                case 'presence':
                    $name = 'PresenceChannel';
                    break;
            }
        }else{
            $name = $this->defaultChannel;
        }

        $stub = str_replace('DummyChannelClass', $name, $stub);
        return $this;
    }

    /**
     * @param $stub
     * @return $this
     */
    protected function replaceChannelName(&$stub)
    {
        $name = $this->option('channelName') ?? $this->defaultChannelName;
        $stub = str_replace('DummyChannelName', $name, $stub);
        return $this;
    }

    /**
     * @param $stub
     * @return $this
     */
    protected function replaceImplements(&$stub)
    {
        $name = $this->defaultImplements;
        if ($this->option('shb')){
            $name = 'implements ShouldBroadcast';
        }

        $stub = str_replace('DummyImplements', $name, $stub);
        return $this;
    }

    /**
     * @return array
     */
    protected function getOptions()
    {
        $options = [
            ['channel', null, InputOption::VALUE_REQUIRED, 'Define channel type'],
            ['channelName', null, InputOption::VALUE_REQUIRED, 'Set channel name'],
            ['shb', null, InputOption::VALUE_NONE, 'implements ShouldBroadcast'],
        ];

        return array_merge($options, parent::getOptions());
    }
}
