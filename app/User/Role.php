<?php

namespace App\User;

use Illuminate\Database\Eloquent\Model;

/**
 * App\User\Role
 *
 * @property integer $id
 * @property string $name
 * @property string $display_name
 * @property string $description
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Config::get('auth.model')[] $users
 * @property-read \Illuminate\Database\Eloquent\Collection|\Config::get('entrust.permission')[] $perms
 * @method static \Illuminate\Database\Query\Builder|\App\User\Role whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User\Role whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User\Role whereDisplayName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User\Role whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User\Role whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User\Role whereUpdatedAt($value)
 */
class Role extends Model
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany('App\User');
    }
}
