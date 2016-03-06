<?php

namespace ChingShop\User;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * ChingShop\User\Role.
 *
 * @property int $id
 * @property string $name
 * @property string $display_name
 * @property string $description
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Config::get('auth.model')[] $users
 * @property-read \Illuminate\Database\Eloquent\Collection|\Config::get('entrust.permission')[] $perms
 *
 * @method static \Illuminate\Database\Query\Builder|\ChingShop\User\Role whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\ChingShop\User\Role whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\ChingShop\User\Role whereDisplayName($value)
 * @method static \Illuminate\Database\Query\Builder|\ChingShop\User\Role whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\ChingShop\User\Role whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\ChingShop\User\Role whereUpdatedAt($value)
 *
 * @property string $deleted_at
 *
 * @method static \Illuminate\Database\Query\Builder|\ChingShop\User\Role whereDeletedAt($value)
 * @mixin \Eloquent
 */
class Role extends Model
{
    const USER_ASSOCIATION_TABLE = 'role_user';
    const FOREIGN_KEY = 'role_id';

    const STAFF = 'staff';

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'roles';

    /**
     * @param string $roleName
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     *
     * @return Role
     */
    public function mustFindByName(string $roleName): Role
    {
        return $this->where('name', $roleName)->firstOrFail();
    }

    /**
     * @return BelongsToMany
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            self::USER_ASSOCIATION_TABLE,
            self::FOREIGN_KEY,
            User::FOREIGN_KEY
        );
    }
}
