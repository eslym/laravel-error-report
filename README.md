# Better Error Reports for Laravel
```bash
composer install eslym/laravel-error-report ^2.0
```
## Usage
1. Migrate database
2. Use the report function
3. Include routes for simple error viewer
4. Schedule cleanup

```php
// app/Exceptions/Handler.php
...
    public function report(Exception $exception)
    {
        if($this->shouldReport($exception)){
            \Eslym\ErrorReport\Facades\Reporter::report($exception);
        }
    }
...
```

```php
// routes/web.php
Route::middleware(['{put your auth middleware here}'])
    ->group(function($router){
        Reporter::routes($router);
    });
```

```php
// app/Console/Kernel.php
$schedule->command('errors:cleanup')
         ->daily();
```