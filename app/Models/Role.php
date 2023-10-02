<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use HasFactory, SoftDeletes;

    const RoleClient = 'Cliente';
    const RoleRenter = 'Inmobiliaria';
    const RoleModerator = 'Moderador';
    const RoleStaff = 'Staff';
    const RoleAdministrator = 'Administrador';

    /**
     * Attributes
     */
    protected $table = 'role';

    protected $primaryKey = 'role_id';

    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
    ];

    /**
     * Assig role to a user by user id and role name.
     *
     * @param $user_id int
     * @param $role_name string
     * @return void
     */
    public static function assignRoleToUser($user_id, $role_name): void
    {
        $role = Role::where('name', '=', $role_name)->first();
        RoleUser::assignRoleToUser($user_id, $role->role_id);
    }

    /**
     * The Users that have this Role.
     *
     * @return BelongsToMany
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'role_user', 'role_id', 'user_id');
    }

    /**
     * The Permissions that belong to the Role.
     *
     * @return BelongsToMany
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'permission_role', 'role_id', 'permission_id');
    }
}
