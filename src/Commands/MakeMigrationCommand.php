<?php


namespace EasyMake\Commands;


use Illuminate\Database\Console\Migrations\MigrateMakeCommand;
use EasyMake\Classes\MigrationCreator;
use Illuminate\Database\Console\Migrations\TableGuesser;
use Illuminate\Support\Composer;
use Illuminate\Support\Str;

class MakeMigrationCommand extends MigrateMakeCommand
{
    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'easymake:migration {name : The name of the migration}
        {--create= : The table to be created}
        {--table= : The table to migrate}
        {--path= : The location where the migration file should be created}
        {--realpath : Indicate any provided migration file paths are pre-resolved absolute paths}
        {--fullpath : Output the full path of the migration}
        {--columns= : Columns to write to the migration}';

    /**
     * The migration creator instance.
     *
     * @var \Illuminate\Database\Migrations\MigrationCreator
     */
    protected $creator;

    /**
     * The Composer instance.
     *
     * @var \Illuminate\Support\Composer
     */
    protected $composer;

    /**
     * Create a new migration install command instance.
     *
     * @param  \Illuminate\Database\Migrations\MigrationCreator  $creator
     * @param  \Illuminate\Support\Composer  $composer
     * @return void
     */
    public function __construct(MigrationCreator $creator, Composer $composer)
    {
        parent::__construct($creator, $composer);

        $this->creator = $creator;
        $this->composer = $composer;
    }

    /**
     * @throws \Exception
     */
    public function handle()
    {
        // It's possible for the developer to specify the tables to modify in this
        // schema operation. The developer may also specify if this table needs
        // to be freshly created so we can create the appropriate migrations.
        $name = Str::snake(trim($this->input->getArgument('name')));

        $table = $this->input->getOption('table');

        $create = $this->input->getOption('create') ?: false;

        // If no table was given as an option but a create option is given then we
        // will use the "create" option as the table name. This allows the devs
        // to pass a table name into this option as a short-cut for creating.
        if (! $table && is_string($create)) {
            $table = $create;

            $create = true;
        }

        // Next, we will attempt to guess the table name if this the migration has
        // "create" in the name. This will allow us to provide a convenient way
        // of creating migrations that create new tables for the application.
        if (! $table) {
            [$table, $create] = TableGuesser::guess($name);
        }

        $columns = $this->parseColumns();

        // Now we are ready to write the migration out to disk. Once we've written
        // the migration out, we will dump-autoload for the entire framework to
        // make sure that the migrations are registered by the class loaders.
        $this->writeOwnMigration($name, $table, $create, $columns);

        $this->composer->dumpAutoloads();
    }

    /**
     * Write the migration file to disk.
     *
     * @param string $name
     * @param string $table
     * @param bool $create
     * @param $columns
     * @return string
     * @throws \Exception
     */
    protected function writeOwnMigration($name, $table, $create, $columns)
    {
        $file = $this->creator->create(
            $name, $this->getMigrationPath(), $table, $create, $columns
        );

        if (! $this->option('fullpath')) {
            $file = pathinfo($file, PATHINFO_FILENAME);
        }

        $this->line("<info>Created Migration:</info> {$file}");
        return $file;
    }

    /**
     * Column types supported by Laravel
     *
     * @return array
     */
    protected function columnTypes()
    {
        return [
            'bigIncrements',
            'bigInteger',
            'binary',
            'boolean',
            'char',
            'date',
            'dateTime',
            'dateTimeTz',
            'decimal',
            'double',
            'enum',
            'float',
            'geometry',
            'geometryCollection',
            'increments',
            'integer',
            'ipAddress',
            'json',
            'jsonb',
            'lineString',
            'longText',
            'macAddress',
            'mediumIncrements',
            'mediumInteger',
            'mediumText',
            'morphs',
            'uuidMorphs',
            'multiLineString',
            'multiPoint',
            'multiPolygon',
            'nullableMorphs',
            'nullableUuidMorphs',
            'nullableTimestamps',
            'point',
            'polygon',
            'rememberToken',
            'set',
            'smallIncrements',
            'smallInteger',
            'softDeletes',
            'softDeletesTz',
            'string',
            'text',
            'time',
            'timeTz',
            'timestamp',
            'timestampTz',
            'timestamps',
            'timestampsTz',
            'tinyIncrements',
            'tinyInteger',
            'unsignedBigInteger',
            'unsignedDecimal',
            'unsignedInteger',
            'unsignedMediumInteger',
            'unsignedSmallInteger',
            'unsignedTinyInteger',
            'uuid',
            'year',
        ];
    }

    /**
     * Collect array of columns with parameters from string
     *
     * @return array
     */
    protected function parseColumns()
    {
        $columns = $this->option('columns');
        if (!$columns) return [];

        $exploded = explode('|', $columns);
        $columns = [];
        foreach ($exploded as $column_str) {
            $params = explode(',', $column_str);
            $column = [];
            foreach ($params as $key => $param){
                $param = explode('=', $param);

                if (!$key && !in_array($param[0], $this->columnTypes())){
                    $this->error('Specify column type by first parameter. E.g integer=some_name');
                    exit();
                }

                $column[$param[0]] = isset($param[1]) ? explode(':', $param[1]) : null;
            }
            $columns[] = $column;
        }

        return $columns;
    }
}
