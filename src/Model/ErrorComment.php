<?php


namespace Eslym\ErrorReport\Model;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ErrorComment
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
class ErrorComment extends Model
{
    protected $fillable = [
        'error_id', 'content', 'email'
    ];
}