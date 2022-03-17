<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\UserApiKey
 *
 * @property string $user_id
 * @property string $api_key
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|UserApiKey newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserApiKey newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserApiKey query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserApiKey whereApiKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserApiKey whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserApiKey whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserApiKey whereUserId($value)
 * @mixin \Eloquent
 */
class UserApiKey extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_api_keys';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'api_key',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'api_key',
    ];

    /**
     * Get the primary key for the model.
     *
     * @return string
     */
    public function getKeyName(): string
    {
        return 'user_id';
    }

    /**
     * Get the user that owns the API Key.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
