# Better Error Reports for Laravel
```bash
composer install eslym/laravel-error-report
```
## Usage
1. Migrate database
2. Extends the error handler
3. Include routes for simple error viewer
4. Schedule cleanup

```php
// app/Exceptions/Handler.php
class Handler extends \Eslym\ErrorReport\ErrorHandler
```

```php
// routes/web.php
Route::middleware(['{put your auth middleware here}'])
    ->group(function(){
        ErrReport::routes();
    });
```

```php
// app/Console/Kernel.php
$schedule->command('errors:cleanup')
         ->daily();
```