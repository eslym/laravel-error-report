<?php


namespace Eslym\ErrorReport\Model;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

/**
 * Class ErrorReport
 * @package Model
 *
 * @property string $id
 * @property string $error_id
 * @property string $content
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @mixin Builder
 */
class ErrorReport extends Model
{
    public $incrementing = false;

    protected $fillable = [
        'error_id', 'content'
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function(ErrorReport $report){
            $report->id = Uuid::uuid4()->toString();
        });
    }
}