## Making Laravel artisan make: command easier

## Getting started

#### Install package
`composer require danielyandev/laravel-easymake`

## Some examples

#### Make model with migration and controller
###### This will create a model with defined relation, a migration with defined columns and a resource controller with defined methods. All you need is just to populate migration with extra columns and add view names to return
`php artisan easymake:model Book --belongsTo="User" --softdeletes -mcr`

#### Make model with soft deletes
`php artisan easymake:model MyModel --softdeletes`

##### or
`php artisan easymake:model MyModel -d`

#### Make model with hasOne relations
`php artisan easymake:model MyModel --hasOne="Model1|Model2|...|ModelN"`

##### With other parameters
`php artisan easymake:model MyModel --hasOne="Model1,foreignKey1,localKey1|...|ModelN,foreignKeyN,localKeyN"`

##### With namespace
`php artisan easymake:model MyModel --hasOne="App\User|App\Models\SomeModel"`

## All available commands

##### Read the docs on gitbook.io [here](https://rub1994-13.gitbook.io/easymake/) or check docs folder in this repository

- [Model](https://github.com/danielyandev/laravel-easymake/blob/master/docs/usage/model.md)
- [Migration](https://github.com/danielyandev/laravel-easymake/blob/master/docs/usage/migration.md)
