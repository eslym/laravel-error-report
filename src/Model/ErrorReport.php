<?php


namespace Eslym\ErrorReport\Model;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Ramsey\Uuid\Uuid;

/**
 * Class ErrorReport
 * @package Model
 *
 * @property string $id
 * @property string $class
 * @property string $content
 * @property boolean $is_console
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read ErrorReport[]|Collection $comments
 *
 * @mixin Builder
 */
class ErrorReport extends Model
{
    public $incrementing = false;

    protected $fillable = [
        'class', 'content'
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function(ErrorReport $report){
            $report->id = Uuid::uuid4()->toString();
            $report->is_console = app()->runningInConsole();
        });
    }
}