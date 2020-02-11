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

- `php artisan easymake:migration add_sold_column_and_soft_deletes_to_items_table --columns="softDeletes|boolean=sold,default=false"`

Output

```php
public function up()
{
    Schema::table('items', function (Blueprint $table) {
        $table->softDeletes();
        $table->boolean('sold')->default(false);

        //
    });
}

public function down()
{
    Schema::table('items', function (Blueprint $table) {
        $table->dropSoftDeletes();
        $table->dropColumn(['sold']);
        //
    });
}
```
