<?php

namespace EasyMake;

use EasyMake\Commands\MakeControllerCommand;
use EasyMake\Commands\MakeEventCommand;
use EasyMake\Commands\MakeMigrationCommand;
use EasyMake\Commands\MakeModelCommand;
use EasyMake\Commands\MakeSeederCommand;
use Illuminate\Support\ServiceProvider;

class EasyMakeServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->registerCommands();
    }

    /**
     * Register commands
     */
    private function registerCommands()
    {
        if ($this->app->runningInConsole()){
            $this->commands([
                MakeModelCommand::class,
                MakeMigrationCommand::class,
                MakeControllerCommand::class,
                MakeEventCommand::class,
                MakeSeederCommand::class,
            ]);
        }
    }
}
