<?php

namespace EasyMake\Tests\Unit;

use EasyMake\Commands\MakeModelCommand;
use EasyMake\Tests\TestCase;

class MakeModelTest extends TestCase
{
    /**
     * Where to place models created by test
     */
    public const TEST_MODEL_DIR = 'TestModels';

    /**
     * Created test model namespace
     */
    public const TEST_MODEL_NAMESPACE = '\\App\\TestModels\\';

    /**
     * Check if command class exists
     */
    public function testCommandExists()
    {
        $this->assertTrue(class_exists(MakeModelCommand::class));
    }

    /**
     * Create model two times
     */
    public function testCreateModel()
    {
        $this->artisan('easymake:model', ['name' => self::TEST_MODEL_DIR . '/Test'])->expectsOutput('Model created successfully.');
        $this->artisan('easymake:model', ['name' => self::TEST_MODEL_DIR . '/Test'])->expectsOutput('Model already exists!');
    }

    /**
     * Create model with SoftDeletes trait using
     */
    public function testHasSoftDeletes()
    {
        $this->artisan('easymake:model', [
            'name' => self::TEST_MODEL_DIR . '/TestHasSoftDeletes',
            '--softdeletes' => true
        ])
        ->expectsOutput('Model created successfully.');

        $model = self::TEST_MODEL_NAMESPACE . 'TestHasSoftDeletes';
        $traits = class_uses(new $model);
        $this->assertTrue(in_array(\Illuminate\Database\Eloquent\SoftDeletes::class, $traits));
    }

    /**
     * Create model with hasOne relation
     */
    public function testHasOneRelation()
    {
        $this->artisan('easymake:model', [
            'name' => self::TEST_MODEL_DIR . '/TestHasOne',
            '--hasOne' => 'App\User'
        ])
        ->expectsOutput('Model created successfully.');

        $model = self::TEST_MODEL_NAMESPACE . 'TestHasOne';
        $this->assertTrue(method_exists(new $model, 'user'));
    }

    /**
     * Create model with hasMany relation
     */
    public function testHasManyRelation()
    {
        $this->artisan('easymake:model', [
            'name' => self::TEST_MODEL_DIR . '/TestHasMany',
            '--hasMany' => 'App\User'
        ])
        ->expectsOutput('Model created successfully.');

        $model = self::TEST_MODEL_NAMESPACE . 'TestHasMany';
        $this->assertTrue(method_exists(new $model, 'users'));
    }

    /**
     * Create model with belongsTo relation
     */
    public function testBelongsToRelation()
    {
        $this->artisan('easymake:model', [
            'name' => self::TEST_MODEL_DIR . '/TestBelongsTo',
            '--belongsTo' => 'App\User'
        ])
        ->expectsOutput('Model created successfully.');

        $model = self::TEST_MODEL_NAMESPACE . 'TestBelongsTo';
        $this->assertTrue(method_exists(new $model, 'user'));
    }

    /**
     * Create model with belongsToMany relation
     */
    public function testBelongsToManyRelation()
    {
        $this->artisan('easymake:model', [
            'name' => self::TEST_MODEL_DIR . '/TestBelongsToMany',
            '--belongsToMany' => 'App\User'
        ])
        ->expectsOutput('Model created successfully.');

        $model = self::TEST_MODEL_NAMESPACE . 'TestBelongsToMany';
        $this->assertTrue(method_exists(new $model, 'users'));
    }

    /**
     * Create model with all relations
     */
    public function testModelWithAllRelations()
    {
        $this->artisan('easymake:model', [
            'name' => self::TEST_MODEL_DIR . '/TestHasAllRelations',
            '--hasOne' => 'TestHasOne',
            '--hasMany' => 'TestHasMany',
            '--belongsTo' => 'TestBelongsTo',
            '--belongsToMany' => 'TestBelongsToMany',
        ])
        ->expectsOutput('Model created successfully.');

        $model = self::TEST_MODEL_NAMESPACE . 'TestHasAllRelations';
        $this->assertTrue(method_exists(new $model, 'testhasone'));
        $this->assertTrue(method_exists(new $model, 'testhasmanies'));
        $this->assertTrue(method_exists(new $model, 'testbelongsto'));
        $this->assertTrue(method_exists(new $model, 'testbelongstomanies'));
    }
}
