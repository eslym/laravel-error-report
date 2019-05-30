<?php


namespace Eslym\ErrorReport\Model;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Ramsey\Uuid\Uuid;

/**
 * Class ErrorRecord
 * @package Model
 *
 * @property string $id
 * @property string $class
 * @property string $site
 * @property string $content
 * @property boolean $is_console
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read ErrorReport[]|Collection $reports
 *
 * @mixin Builder
 */
class ErrorRecord extends Model
{
    public $incrementing = false;

    protected $fillable = [
        'class', 'hash', 'site', 'is_console'
    ];

    protected $casts = [
        'is_console' => 'boolean'
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function(ErrorRecord $record){
            $record->id = Uuid::uuid4()->toString();
        });
    }

    public function reports(){
        return $this->hasMany(ErrorReport::class, 'error_id', 'id');
    }

    public function comments(){
        return $this->hasMany(ErrorComment::class, 'error_id', 'id');
    }
}