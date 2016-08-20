<?php

namespace ChingShop\Modules\User\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * ChingShop\Modules\User\Domain\Role.
 *
 * @property int $id
 * @property string $name
 * @property string $display_name
 * @property string $description
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection $users
 * @property-read \Illuminate\Database\Eloquent\Collection $perms
 * @property string $deleted_at
 *
 * @mixin \Eloquent
 */
class Role extends Model
{
    use SoftDeletes;

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
