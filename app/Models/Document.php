<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;


/**
 * App\Models\Document
 * @property int $id
 * @property int $user_id
 * @property string $title
 * @property string $file_name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @mixin mixed
 */
class Document extends Model
{
    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'user_id', 'title', 'file_name'
    ];
}
