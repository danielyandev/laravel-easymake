<?php


namespace EasyMake\Commands;


use Illuminate\Database\Console\Seeds\SeederMakeCommand;
use Symfony\Component\Console\Input\InputOption;

class MakeSeederCommand extends SeederMakeCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'easymake:seeder';

    /**
     * @return string
     */
    protected function getStub()
    {
        $name =  $this->option('table') ? 'table' : 'blank';
        return __DIR__ .'/../../stubs/seeder/'. $name .'.stub';
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
                    ->replaceTable($stub)
                    ->replaceClass($stub, $name);
    }

    /**
     * Replace dummy table name with the given table name
     *
     * @param $stub
     * @return $this
     */
    protected function replaceTable(&$stub)
    {
        $table = $this->option('table');
        if ($table){
            $stub = str_replace('DummyTable', $table, $stub);
        }
        return $this;
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        $options = [
            ['table', 't', InputOption::VALUE_REQUIRED, 'Add table name to seeder']
        ];

        return array_merge($options, parent::getOptions());
    }
}
