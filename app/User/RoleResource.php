<?php

namespace ChingShop\User;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * ChingShop\User\Role
 *
 * @property integer $id
 * @property string $name
 * @property string $display_name
 * @property string $description
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Config::get('auth.model')[] $users
 * @property-read \Illuminate\Database\Eloquent\Collection|\Config::get('entrust.permission')[] $perms
 * @method static \Illuminate\Database\Query\Builder|\ChingShop\User\RoleResource whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\ChingShop\User\RoleResource whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\ChingShop\User\RoleResource whereDisplayName($value)
 * @method static \Illuminate\Database\Query\Builder|\ChingShop\User\RoleResource whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\ChingShop\User\RoleResource whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\ChingShop\User\RoleResource whereUpdatedAt($value)
 */
class RoleResource extends Model
{
    const USER_ASSOCIATION_TABLE = 'role_user';
    const FOREIGN_KEY = 'role_id';

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'roles';

    /**
     * @param string $roleName
     * @return RoleResource
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function mustFindByName(string $roleName): RoleResource
    {
        return $this->where('name', $roleName)->firstOrFail();
    }

    /**
     * @return BelongsToMany
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(
            UserResource::class,
            self::USER_ASSOCIATION_TABLE,
            self::FOREIGN_KEY,
            UserResource::FOREIGN_KEY
        );
    }
}
