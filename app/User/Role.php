<?php

namespace ChingShop\User;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Query\Builder;

/**
 * ChingShop\User\Role.
 *
 * @property int $id
 * @property string $name
 * @property string $display_name
 * @property string $description
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read Collection|\Config::get('auth.model')[] $users
 * @property-read Collection|\Config::get('entrust.permission')[] $perms
 *
 * @method static Builder|Role whereId($value)
 * @method static Builder|Role whereName($value)
 * @method static Builder|Role whereDisplayName($value)
 * @method static Builder|Role whereDescription($value)
 * @method static Builder|Role whereCreatedAt($value)
 * @method static Builder|Role whereUpdatedAt($value)
 *
 * @property string $deleted_at
 *
 * @method static Builder|Role whereDeletedAt($value)
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
