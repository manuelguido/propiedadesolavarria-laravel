<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * Attributes
     */
    protected $table = 'user';

    protected $primaryKey = 'user_id';

    public $timestamps = true;


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'name',
        'email',
        'password',
        'profile_image',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Create a new User.
     *
     * @return \App\Models\User
     */
    public static function createUser($userData): User
    {
        $newData = $userData;
        $newData['password'] = Hash::make($userData['password']);
        return User::create($newData);
    }

    /**
     * The roles that belong to the user.
     *
     * @return BelongsToMany
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_user', 'user_id', 'role_id');
    }

    /**
     * The Client that owns to the User.
     *
     * @return BelongsTo
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'user_id', 'user_id');
    }

    /**
     * The Renter that owns to the User.
     *
     * @return BelongsTo
     */
    public function renter(): BelongsTo
    {
        return $this->belongsTo(Renter::class, 'user_id', 'user_id');
    }

    /**
     * The Moderator that owns to the User.
     *
     * @return BelongsTo
     */
    public function moderator(): BelongsTo
    {
        return $this->belongsTo(Moderator::class, 'user_id', 'user_id');
    }

    /**
     * The Staff that owns to the User.
     *
     * @return BelongsTo
     */
    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'user_id', 'user_id');
    }

    /**
     * The Administrator that owns to the User.
     *
     * @return BelongsTo
     */
    public function administrator(): BelongsTo
    {
        return $this->belongsTo(Administrator::class, 'user_id', 'user_id');
    }

    /**
     * Verify if user has a Role
     *
     * @param string
     * @return bool
     */
    public function hasRole(string $roleToVerify): bool
    {
        return $this->roles->contains('name', $roleToVerify);
    }


    /**
     * Verify if user has a Role
     *
     * @param string
     * @return bool
     */
    public function hasPermission(string $permissionName): bool
    {
        $roles = $this->roles;
        $hasPermission = false;
        foreach ($roles as $role) {
            foreach ($role->permissions as $permission) {
                if ($permissionName == $permission->name) {
                    $hasPermission = true;
                    break 2;
                }
            }
        }
        return $hasPermission;
        // return $this->roles->loadMissing('permissions')->pluck('permissions')->flatten()->contains('name', $permissionName);
    }

    /**
     * Returns true if a user is employee.
     *
     * @return bool
     */
    public function isEmployee(): bool
    {
        $employeeRoles = [Role::RoleAdministrator, Role::RoleStaff, Role::RoleModerator];
        return $this->roles()->whereIn('name', $employeeRoles)->exists();
    }


    /**
     * Returns hashed password for current user.
     *
     * @return string
     */
    public function getHashedPassword(): string
    {
        return DB::table('user')->where('user_id', '=', $this->user_id)->first()->password;
    }

    // /**
    //  * Returns user routes.
    //  */
    // public function getRoutes()
    // {
    //     return $this->roles->pluck('routes')->flatten();
    // }

    /**
     * Returns user web routes.
     */
    public function getWebRoutes()
    {
        $routes = [];
        foreach ($this->roles as $role) {
            switch ($role->name) {
                case Role::RoleAdministrator:
                    $routes[] = Administrator::getWebRoutes();
                    break;
                case Role::RoleClient:
                    $routes[] = Client::getWebRoutes();
                    break;
                case Role::RoleModerator:
                    $routes[] = Moderator::getWebRoutes();
                    break;
                case Role::RoleStaff:
                    $routes[] = Staff::getWebRoutes();
                    break;
                case Role::RoleRenter:
                    $routes[] = Renter::getWebRoutes();
                    break;
            }
        }
        return array_merge(...$routes);
    }


}
