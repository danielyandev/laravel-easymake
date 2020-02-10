## Make Laravel 5.\*, 6.\* classes Easier

## Get started

#### Install package
`composer require danielyandev/laravel-easymake`

## Usage

#### Make model with soft deletes
`php artisan easymake:model MyModel --softdeletes`

##### or
`php artisan easymake:model MyModel -d`

#### Make model with hasOne relations
`php artisan easymake:model MyModel --hasOne="Model1|Model2|...|ModelN"`

##### With other parameters
`php artisan easymake:model MyModel --hasOne="Model1,foreignKey1,localKey1"`

`php artisan easymake:model MyModel --hasOne="Model1,foreignKey1,localKey1|...|ModelN,foreignKeyN,localKeyN"`

##### With namespace
`php artisan easymake:model MyModel --hasOne="App\User|App\Models\SomeModel"`

## All available commands

### Model
###### Parameters are optional and you most likely won't use them if you follow laravel standards
##### Note: you can specify as many relation models as you want, separated with `|` character
- `php artisan easymake:model MyModel --hasOne="OtherModel,foreignKey,localKey"`
- `php artisan easymake:model MyModel --hasMany="OtherModel,foreignKey,localKey"`
- `php artisan easymake:model MyModel --belongsTo="OtherModel,foreignKey,ownerKey,relation"`
- `php artisan easymake:model MyModel --belongsToMany="OtherModel,table,foreignPivotKey,relatedPivotKey,parentKey,relatedKey,relation"`

##### If you specify -m option to create migration of model with --belongsTo option, it'll also write relation columns. For example:
`php artisan easymake:model Phone --belongsTo="User" -m`

Output
```php
// migration file

public function up()
{
    Schema::create('phones', function (Blueprint $table) {
        $table->bigIncrements('id');
        $table->integer('user_id'); <-- table will have this column to create relation

        $table->timestamps();
    });
}

// model file
public function user()
{
    return $this->belongsTo(User::class);
}
```

### Migration
##### Note: you can specify as many columns as you want, separated with `|` character, parameters are separated with `:` character
- `php artisan easymake:migration create_items_table --columns="string=name:100,default=null,nullable|text=description,nullable"`

Output

```php
public function up()
{
    Schema::create('items', function (Blueprint $table) {
        $table->bigIncrements('id');
        $table->string('name', '100')->default(null)->nullable();
        $table->text('description')->nullable();

        $table->timestamps();
    });
}
```
