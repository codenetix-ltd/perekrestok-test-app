<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use SoftDeletes;

    /**
     * @var array
     */
    protected $dates = ['fired_at', 'deleted_at'];

    /**
     * @var array
     */
    protected $fillable = ['is_viewed', 'is_hidden', 'type', 'message', 'link', 'fired_at', 'user_id', 'is_sent'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'is_viewed' => 'boolean',
        'is_hidden' => 'boolean'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
