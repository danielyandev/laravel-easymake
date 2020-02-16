### Seeder
##### Note: you can use the command without --table option as you usually use make:seeder command

#### Make new seeder with table name
```
php artisan easymake:seeder BooksTableSeeder --table="books"
```

#### or
```
php artisan easymake:seeder BooksTableSeeder -t books
```

Output

```php
public function run()
{
    DB::table('books')->insert([

    ]);
}
```
