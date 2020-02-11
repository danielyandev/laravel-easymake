### Migration
##### Note: you just use this command as usual make:controller command, but if you create api or resource controller with --model option, it will be with predefined methods, all you'll need is just to add view names to return

#### Make new resource controller
```
php artisan easymake:controller TestController --resource --model=Test
```

Controller's store method
```php
public function store(Request $request)
{
    $rules = [

    ];

    $this->validate($request, $rules);

    $test = new Test();
    $test->fill($request->except('_token'));
    $test->save();

    return back()->with('success', 'New Test created successfully');
}
```

#### Make new api controller
```
php artisan easymake:controller TestController --api --model=Test
```

Controller's store method
```php
public function store(Request $request)
{
    $rules = [

    ];

    $validator = Validator::make($request->all(), $rules);
    if($validator->fails()){
        return response()->json([
            'success' => false,
            'errors' => $validator->errors()
        ], 400);
    }

    $test = new Test();
    $test->fill($request->except('_token'));
    $test->save();

    return response()->json([
       'success' => true,
       'test' => $test
    ]);
}
```
