<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Administrator extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Attributes
     */
    protected $table = 'administrator';

    protected $primaryKey = 'administrator_id';

    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'administrator_id',
        'user_id',
    ];

    /**
     * Get the Administrator with the User.
     */
    public static function findWithUser($administrator_id)
    {
        return User::where('administrator_id', '=', $administrator_id)
            ->join('administrator', 'user.user_id', '=', 'administrator.user_id')
            ->first();
    }

    /**
     * Create a new Administrator.
     *
     * @return \App\Models\Administrator
     */
    public static function createAdministrator($userData): Administrator
    {
        $user = User::createUser($userData);
        $administratorData = $userData;
        $administratorData['user_id'] = $user->user_id;
        $administrator = Administrator::create($administratorData);
        Role::assignRoleToUser($administrator->user_id, Role::RoleAdministrator);
        return $administrator;

    }

    /**
     * Get all administrators with their user data.
     */
    public static function allWithUser()
    {
        return Administrator::select('administrator.*', 'user.name', 'user.email', 'user.profile_image')
            ->join('user', 'user.user_id', '=', 'administrator.user_id')
            ->get();
    }

    /**
     * The user that corresponds to the administrator.
     */
    public function user()
    {
        return $this->hasOne(User::class, 'user_id', 'user_id');
    }

    /**
     * Return current model with its correspondig user.
     */
    public function withUser(): Administrator
    {
        return $this->join('user', 'user.user_id', '=', 'administrator.user_id')->first();
    }

    /**
     * User routes.
     */
    public static function getWebRoutes()
    {
        return [
            ['icon' => 'icon-Home', 'name' => 'Inicio', 'url' => '/dashboard/administrator/home'],
            ['icon' => 'icon-User-information', 'name' => 'Inmobiliarias', 'url' => '/dashboard/administrator/renters'],
            ['icon' => 'icon-User', 'name' => 'Usuarios', 'url' => '/dashboard/administrator/users'],
        ];
    }
}
