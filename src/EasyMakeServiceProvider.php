<?php

namespace EasyMake;

use EasyMake\Commands\MakeModelCommand;
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
        $this->commands([
            MakeModelCommand::class
        ]);
    }
}
