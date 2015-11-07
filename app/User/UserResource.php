<?php

namespace ChingShop\User;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

/**
 * Class UserResource
 *
 * @package ChingShop\User
 * @property integer $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $remember_token
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\ChingShop\User\RoleResource[] $roles
 * @method static \Illuminate\Database\Query\Builder|\ChingShop\User\UserResource whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\ChingShop\User\UserResource whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\ChingShop\User\UserResource whereEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\ChingShop\User\UserResource wherePassword($value)
 * @method static \Illuminate\Database\Query\Builder|\ChingShop\User\UserResource whereRememberToken($value)
 * @method static \Illuminate\Database\Query\Builder|\ChingShop\User\UserResource whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\ChingShop\User\UserResource whereUpdatedAt($value)
 */
class UserResource extends Model implements AuthenticatableContract,
                                    AuthorizableContract,
                                    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword;

    /** @var \Illuminate\Database\Eloquent\Collection */
    public $roles;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'password'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany('ChingShop\User\RoleResource');
    }
}
