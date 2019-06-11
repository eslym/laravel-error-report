# Better Error Reports for Laravel
**Why this package exists?**
Sometimes errors happens on production but default error report does not log the environment variables and etc.
It is hard to track the error with limited information but its too heavy for server to store **all** information.
This package will detect repeated errors and store only limited sample for each error.

## Installation
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