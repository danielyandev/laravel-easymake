<?php

namespace EasyMake\Classes;

use Illuminate\Support\Str;

class MigrationCreator extends \Illuminate\Database\Migrations\MigrationCreator
{
    /**
     * Create a new migration at the given path.
     *
     * @param string $name
     * @param string $path
     * @param string|null $table
     * @param bool $create
     * @param array $columns
     * @return string
     */
    public function create($name, $path, $table = null, $create = false, $columns = [])
    {
        $this->ensureMigrationDoesntAlreadyExist($name, $path);

        // First we will get the stub file for the migration, which serves as a type
        // of template for the migration. Once we have those we will populate the
        // various place-holders, save the file, and run the post create event.
        $stub = $this->getStub($table, $create);

        $this->files->put(
            $path = $this->getPath($name, $path),
            $this->populateOwnStub($name, $stub, $table, $columns)
        );

        // Next, we will fire any hooks that are supposed to fire after a migration is
        // created. Once that is done we'll be ready to return the full path to the
        // migration file so it can be used however it's needed by the developer.
        $this->firePostCreateHooks($table);

        return $path;
    }

    /**
     * @return string
     */
    public function stubPath()
    {
        return __DIR__ . '/../../stubs/migration';
    }

    /**
     * @param string $name
     * @param string $stub
     * @param string|null $table
     * @param $columns
     * @return mixed|string
     */
    protected function populateOwnStub($name, $stub, $table, $columns)
    {
        $stub = str_replace('DummyClass', $this->getClassName($name), $stub);

        // Here we will replace the table place-holders with the table specified by
        // the developer, which is useful for quickly creating a tables creation
        // or update migration from the console instead of typing it manually.
        if (! is_null($table)) {
            $stub = str_replace('DummyTable', $table, $stub);
        }

        [$up, $down] = $this->generateColumns($columns);
        $stub = str_replace('//DummyColumnsUp', $up, $stub);
        $stub = str_replace('//DummyColumnsDown', $down, $stub);

        return $stub;
    }

    /**
     * Generate columns for up and down methods
     *
     * @param $columns
     * @return array
     */
    protected function generateColumns($columns)
    {
        $up = '';
        $down = '';
        $tabs = "\t\t\t";

        if ($columns){
            $down = '$table->dropColumn([';
            $downColumns = '';
            $softDeletes = false;
            foreach ($columns as $column) {
                $row = '$table';
                $loop = 0;
                foreach ($column as $method => $params) {
                    if (Str::lower($method) == 'softdeletes'){
                        $softDeletes = true;
                    }
                    $params_row = '';
                    if ($params){
                        foreach ($params as $param) {
                            $quote = in_array($param, ['null', 'false', 'true']) ? "" : "'";
                            $params_row .= $params_row ? ', ' : '';
                            $params_row .= $quote . $param . $quote;
                        }

                    }

                    $row .= '->' . $method . '('. $params_row .')';

                    if (!$loop){
                        $downColumns .= $downColumns ? ', ' : '';
                        $downColumns .= "'". $params[0] ."'";
                    }
                    $loop++;
                }
                $row .= ';'. PHP_EOL;
                $up .= $tabs . $row;
            }
            $down .= $downColumns;
            $down .= ']);';

            // if only softdeletes are dropped
            // so we don't need this empty line
            if ($down == '$table->dropColumn([\'\']);'){
                $down = '';
            }

            if ($softDeletes){
                $sd = '$this->dropSoftDeletes();'. PHP_EOL;
                $down = $sd . $tabs . $down;
            }
        }

        return [$up, $down];
    }

}
