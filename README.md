## Make Laravel 5.\*, 6.\* classes Easier

#### Note: you can use this version ony if you follow Laravel architecture standards, if you use other folders for models, controllers and etc. you have to wait the next versions 

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

##### you can also specify other parameters
`php artisan easymake:model MyModel --hasOne="Model1,foreignKey,localKey"`

`php artisan easymake:model MyModel --hasOne="Model1,foreignKey1,localKey1|...|ModelN,foreignKeyN,localKeyN"`

## All available commands

#### Model
###### Parameters are optional and you most likely won't use them if you follow laravel standards
##### Note: you can specify as many  relation models as you want, separated with `|` character
- `php artisan easymake:model MyModel --hasOne="OtherModel,foreignKey,localKey"`
- `php artisan easymake:model MyModel --hasMany="OtherModel,foreignKey,localKey"`
- `php artisan easymake:model MyModel --belongsTo="OtherModel,foreignKey,ownerKey,relation"`
- `php artisan easymake:model MyModel --belongsToMany="OtherModel,table,foreignPivotKey,relatedPivotKey,ParentKey,RelatedKey,relation"`
