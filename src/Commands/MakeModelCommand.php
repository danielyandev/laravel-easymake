<?php

namespace EasyMake\Commands;


use Illuminate\Foundation\Console\ModelMakeCommand;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

class MakeModelCommand extends ModelMakeCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'easymake:model';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Eloquent model class';

    /**
     * Columns to pass to migration command.
     *
     * @var string
     */
    protected $migrationColumns = '';

    /**
     * Resource controller option
     *
     * @var boolean
     */
    protected $controllerResource;

    /**
     * Api controller option
     *
     * @var boolean
     */
    protected $controllerApi;

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $migration = $this->option('migration');
        // disable migration creating for parent handle method
        if ($migration) {
            $this->input->setOption('migration', false);
        }
        $controller = $this->option('controller');
        $controllerResource = $this->option('resource');
        $controllerApi = $this->option('api');
        // disable controller creating for parent handle method
        if ($controller || $controllerResource || $controllerApi) {
            $this->input->setOption('controller', false);
            $this->input->setOption('resource', false);
            $this->input->setOption('api', false);
            $this->controllerApi = $controllerApi;
            $this->controllerResource = $controllerResource;
        }

        if (parent::handle() === false && ! $this->option('force')) {
            return;
        }

        if ($this->option('all')) {
            $this->input->setOption('factory', true);
            $this->input->setOption('seed', true);
            $this->input->setOption('migration', true);
            $this->input->setOption('controller', true);
            $this->input->setOption('resource', true);
        }

        if ($this->option('factory')) {
            $this->createFactory();
        }

        if ($migration) {
            $this->createOwnMigration();
        }

        if ($this->option('seed')) {
            $this->createSeeder();
        }

        if ($controller || $controllerResource || $controllerApi) {
            $this->createOwnController();
        }
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__ . '/../../stubs/model/model.stub';
    }

    /**
     * Get method stub
     *
     * @param $name
     * @return string
     */
    protected function getSubStub($name)
    {
        return __DIR__ . '/../../stubs/model/' . $name .'.stub';
    }

    /**
     * Build the class with the given name.
     *
     * @param string $name
     *
     * @return string
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function buildClass($name)
    {
        $stub = $this->files->get($this->getStub());

        return $this->replaceSoftDeletes($stub)
                    ->replaceHasManyRelations($stub)
                    ->replaceHasOneRelations($stub)
                    ->replaceBelongsToRelations($stub)
                    ->replaceBelongsToManyRelations($stub)
                    ->replaceNamespace($stub, $name)
                    ->replaceClass($stub, $name);
    }

    /**
     * Add SoftDelete to the given stub.
     *
     * @param string $stub
     *
     * @return $this
     */
    protected function replaceSoftDeletes(&$stub)
    {
        $traitIncl = $trait = '';

        if ($this->option('softdeletes')) {
            $traitIncl = 'use Illuminate\Database\Eloquent\SoftDeletes;';
            $trait = 'use SoftDeletes;';
        }

        $stub = str_replace('//DummySDTraitInclude', $traitIncl, $stub);
        $stub = str_replace('//DummySDTrait', $trait, $stub);

        return $this;
    }

    /**
     * @param $stub
     * @return $this
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function replaceHasManyRelations(&$stub)
    {
        $subStub = $this->files->get($this->getSubStub('hasmany'));

        $relations = '';
        $option = $this->option('hasMany');

        if ($option) {
            $relations = $this->getRelations($option, $subStub, true);
        }

        $stub = str_replace('//DummyHasManyRelations', $relations, $stub);

        return $this;
    }

    /**
     * @param $stub
     * @return $this
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function replaceHasOneRelations(&$stub)
    {
        $subStub = $this->files->get($this->getSubStub('hasone'));

        $relations = '';
        $option = $this->option('hasOne');

        if ($option) {
            $relations = $this->getRelations($option, $subStub);
        }

        $stub = str_replace('//DummyHasOneRelations', $relations, $stub);

        return $this;
    }

    /**
     * @param $stub
     * @return $this
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function replaceBelongsToRelations(&$stub)
    {
        $subStub = $this->files->get($this->getSubStub('belongsto'));

        $relations = '';
        $option = $this->option('belongsTo');

        if ($option) {
            $relations = $this->getRelations($option, $subStub);
        }

        $stub = str_replace('//DummyBelongsToRelations', $relations, $stub);

        return $this;
    }

    /**
     * @param $stub
     * @return $this
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function replaceBelongsToManyRelations(&$stub)
    {
        $subStub = $this->files->get($this->getSubStub('belongstomany'));

        $relations = '';
        $option = $this->option('belongsToMany');

        if ($option) {
            $relations = $this->getRelations($option, $subStub, true);
        }

        $stub = str_replace('//DummyBelongsToManyRelations', $relations, $stub);

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
            ['softdeletes', 'd', InputOption::VALUE_NONE, 'Add soft-delete field to Model'],
            ['hasMany', null, InputOption::VALUE_REQUIRED, 'Define hasMany relations'],
            ['hasOne', null, InputOption::VALUE_REQUIRED, 'Define hasOne relations'],
            ['belongsTo', null, InputOption::VALUE_REQUIRED, 'Define belongsTo relations'],
            ['belongsToMany', null, InputOption::VALUE_REQUIRED, 'Define belongsToMany relations'],
        ];

        return array_merge($options, parent::getOptions());
    }

    /**
     * Get relation methods
     *
     * @param $option
     * @param $subStub
     * @param bool $plural
     * @return string
     */
    public function getRelations($option, $subStub, $plural = false)
    {
        $relations = '';
        $optionRelations = explode('|', $option);

        foreach ($optionRelations as $relation){
            if (!$relation) continue;
            $relation = trim($relation);

            [$methodName, $params] = $this->parseRelation($relation, $plural);

            $method = $subStub;
            $method = str_replace('DummyMethodName', $methodName, $method);
            $method = str_replace('DummyParams', $params, $method);
            $relations .= $method;

            if ($this->option('belongsTo')){
                if (Str::lower($this->option('belongsTo')) == $methodName){
                    $this->migrationColumns .= $this->migrationColumns ? '|': '';
                    $this->migrationColumns .= 'integer='. $methodName .'_id';
                }
            }
            if ($this->option('softdeletes')){
                $this->migrationColumns .= $this->migrationColumns ? '|': '';
                $this->migrationColumns .= 'softDeletes';
            }
        }

        return $relations;
    }

    /**
     * @param $relation
     * @param $plural
     * @return array
     */
    protected function parseRelation($relation, $plural)
    {
        $relation = explode(',', $relation);
        $className = str_replace($this->getNamespace($relation[0]).'\\', '', $relation[0]);
        $methodName = Str::lower($plural ? Str::plural($className) : $className);

        // get namespace of model is being created
        $namespace = explode('\\', str_replace('/', '\\', $this->argument('name')));
        unset($namespace[count($namespace) - 1]);
        $namespace = $this->rootNamespace() . implode('\\', $namespace);

        $params = '\\' . $this->getNamespace($relation[0]).'\\' . $className . "::class";
        // if created model and its related model are in the same namespace
        // we don't need the full namespace

        if (!$this->getNamespace($relation[0]) || $namespace == $this->getNamespace($relation[0])){
            $params = $className . "::class";
        }

        // add params field
        foreach ($relation as $key => $value) {
            if (!$key) continue;

            $params .= ", '". $value . "'";
        }

        return [$methodName,  $params];
    }

    /**
     * Create a migration file for the model.
     *
     * @return void
     */
    protected function createOwnMigration()
    {
        $table = Str::snake(Str::pluralStudly(class_basename($this->argument('name'))));

        if ($this->option('pivot')) {
            $table = Str::singular($table);
        }

        $params = [
            'name' => "create_{$table}_table",
            '--create' => $table
        ];

        if ($this->migrationColumns){
            $columns = explode('|', $this->migrationColumns);
            $columns = implode('|', array_unique($columns));
            $params['--columns'] = $columns;
        }

        $this->call('easymake:migration', $params);
    }

    /**
     * Create a controller for the model.
     *
     * @return void
     */
    protected function createOwnController()
    {
        $controller = Str::studly(class_basename($this->argument('name')));

        $modelName = $this->qualifyClass($this->getNameInput());

        $this->call('easymake:controller', array_filter([
            'name'  => "{$controller}Controller",
            '--model' => $this->controllerResource || $this->controllerApi ? $modelName : null,
            '--api' => $this->controllerApi,
        ]));
    }
}
