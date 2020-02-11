### Model
###### Parameters are optional and you most likely won't use them if you follow laravel standards
##### Note: you can specify as many relation models with their parameters as you want, separated with `|` character

##### Make model with soft deletes
```
php artisan easymake:model MyModel --softdeletes
```

##### Make model with hasOne relations
```
php artisan easymake:model MyModel --hasOne="OtherModel,foreignKey,localKey"
```

##### Make model with hasMany relations
```
php artisan easymake:model MyModel --hasMany="OtherModel,foreignKey,localKey"
```

##### Make model with belongsTo relations
```
php artisan easymake:model MyModel --belongsTo="OtherModel,foreignKey,ownerKey,relation"
```

##### Make model with belongsTo relations
```
php artisan easymake:model MyModel --belongsToMany="OtherModel,table,foreignPivotKey,relatedPivotKey,parentKey,relatedKey,relation"
```

##### If you specify -m option to create migration of model with --belongsTo option, it'll also write relation columns. For example:
```
php artisan easymake:model Phone --belongsTo="User" -m
```

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

##### Multiple relations example
```
php artisan easymake:model Message --belongsTo="User|Conversation"
```
