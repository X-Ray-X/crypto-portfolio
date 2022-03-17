<?php

namespace App\Models;

use App\Traits\UuidAsPrimaryKey;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Laravel\Lumen\Auth\Authorizable;
use Sofa\Eloquence\Eloquence;
use Sofa\Eloquence\Mappable;

/**
 * App\Models\User
 *
 * @mixin Builder
 * @property string $id
 * @property string $username
 * @property string $password
 * @property string $email
 * @property string|null $phone
 * @property string|null $first_name
 * @property string|null $last_name
 * @property int $is_active
 * @property int $is_admin
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read string|null $mapping_for
 * @method static \Sofa\Eloquence\Builder|User aggregate($function, array $columns = [])
 * @method static \Sofa\Eloquence\Builder|User avg($column)
 * @method static \Sofa\Eloquence\Builder|User callParent($method, array $args)
 * @method static \Sofa\Eloquence\Builder|User count($columns = '*')
 * @method static \Database\Factories\UserFactory factory(...$parameters)
 * @method static \Sofa\Eloquence\Builder|User getLikeOperator()
 * @method static \Sofa\Eloquence\Builder|User joinRelations($relations, $type = 'inner')
 * @method static \Sofa\Eloquence\Builder|User leftJoinRelations($relations)
 * @method static \Sofa\Eloquence\Builder|User lists($column, $key = null)
 * @method static \Sofa\Eloquence\Builder|User max($column)
 * @method static \Sofa\Eloquence\Builder|User min($column)
 * @method static \Sofa\Eloquence\Builder|User newModelQuery()
 * @method static \Sofa\Eloquence\Builder|User newQuery()
 * @method static \Sofa\Eloquence\Builder|User orWhereBetween($column, array $values)
 * @method static \Sofa\Eloquence\Builder|User orWhereIn($column, $values)
 * @method static \Sofa\Eloquence\Builder|User orWhereNotBetween($column, array $values)
 * @method static \Sofa\Eloquence\Builder|User orWhereNotIn($column, $values)
 * @method static \Sofa\Eloquence\Builder|User orWhereNotNull($column)
 * @method static \Sofa\Eloquence\Builder|User orWhereNull($column)
 * @method static \Sofa\Eloquence\Builder|User orderBy($column, $direction = 'asc')
 * @method static \Sofa\Eloquence\Builder|User prefixColumnsForJoin()
 * @method static \Sofa\Eloquence\Builder|User query()
 * @method static \Sofa\Eloquence\Builder|User rightJoinRelations($relations)
 * @method static \Sofa\Eloquence\Builder|User search($query, $columns = null, $fulltext = true, $threshold = null)
 * @method static \Sofa\Eloquence\Builder|User select($columns = [])
 * @method static \Sofa\Eloquence\Builder|User setJoinerFactory(\Sofa\Eloquence\Contracts\Relations\JoinerFactory $factory)
 * @method static \Sofa\Eloquence\Builder|User setParserFactory(\Sofa\Eloquence\Contracts\Searchable\ParserFactory $factory)
 * @method static \Sofa\Eloquence\Builder|User sum($column)
 * @method static \Sofa\Eloquence\Builder|User whereBetween($column, array $values, $boolean = 'and', $not = false)
 * @method static \Sofa\Eloquence\Builder|User whereCreatedAt($value)
 * @method static \Sofa\Eloquence\Builder|User whereDate($column, $operator, $value, $boolean = 'and')
 * @method static \Sofa\Eloquence\Builder|User whereDay($column, $operator, $value, $boolean = 'and')
 * @method static \Sofa\Eloquence\Builder|User whereEmail($value)
 * @method static \Sofa\Eloquence\Builder|User whereExists(\Closure $callback, $boolean = 'and', $not = false)
 * @method static \Sofa\Eloquence\Builder|User whereFirstName($value)
 * @method static \Sofa\Eloquence\Builder|User whereId($value)
 * @method static \Sofa\Eloquence\Builder|User whereIn($column, $values, $boolean = 'and', $not = false)
 * @method static \Sofa\Eloquence\Builder|User whereIsActive($value)
 * @method static \Sofa\Eloquence\Builder|User whereIsAdmin($value)
 * @method static \Sofa\Eloquence\Builder|User whereLastName($value)
 * @method static \Sofa\Eloquence\Builder|User whereMonth($column, $operator, $value, $boolean = 'and')
 * @method static \Sofa\Eloquence\Builder|User whereNotBetween($column, array $values, $boolean = 'and')
 * @method static \Sofa\Eloquence\Builder|User whereNotIn($column, $values, $boolean = 'and')
 * @method static \Sofa\Eloquence\Builder|User whereNotNull($column, $boolean = 'and')
 * @method static \Sofa\Eloquence\Builder|User whereNull($column, $boolean = 'and', $not = false)
 * @method static \Sofa\Eloquence\Builder|User wherePassword($value)
 * @method static \Sofa\Eloquence\Builder|User wherePhone($value)
 * @method static \Sofa\Eloquence\Builder|User whereUpdatedAt($value)
 * @method static \Sofa\Eloquence\Builder|User whereUsername($value)
 * @method static \Sofa\Eloquence\Builder|User whereYear($column, $operator, $value, $boolean = 'and')
 * @property-read \App\Models\UserApiKey|null $auth
 */
class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable;
    use Authorizable;
    use HasFactory;
    use UuidAsPrimaryKey;
    use Eloquence;
    use Mappable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'email', 'password', 'phoneNumber', 'firstName', 'lastName',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    /**
     *  The attributes that require name mapping for compatibility between request fields and database columns.
     *
     * @var string[]
     */
    protected $maps = [
        'phoneNumber' => 'phone',
        'firstName' => 'first_name',
        'lastName' => 'last_name'
    ];

    /**
     * Get the API Key of the user.
     */
    public function auth(): HasOne
    {
        return $this->hasOne(UserApiKey::class, 'user_id', 'id');
    }
}
