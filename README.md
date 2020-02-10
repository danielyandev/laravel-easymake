## Make Laravel 5.\*, 6.\* classes Easier

#### Note: you can use this version ony if you follow Laravel architecture codex, if you use other folders for models, controllers and etc. you have to wait the next versions 

## Get started

#### Install package
<code>composer require danielyandev/laravel-easymake</code>

## Usage

#### Make model with soft deletes
<code>php artisan easymake:model MyModel --softdeletes</code>

##### or
<code>php artisan easymake:model MyModel -d</code>

#### Make model with hasOne relations
<code>php artisan easymake:model MyModel --hasOne="Model1|Model2|...|ModelN"</code>

##### you can also specify other parameters
<code>php artisan easymake:model MyModel --hasOne="Model1,foreignKey,localKey"</code>

<code>php artisan easymake:model MyModel --hasOne="Model1,foreignKey1,localKey1|...|ModelN,foreignKeyN,localKeyN"</code>

## All available commands

soon..
