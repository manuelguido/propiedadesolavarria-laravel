<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Staff extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Attributes
     */
    protected $table = 'staff';

    protected $primaryKey = 'staff_id';

    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'staff_id',
        'user_id',
    ];

    /**
     * Get the Staff with the User.
     *
     * @return \App\Models\User
     */
    public static function findWithUser($staff_id): User
    {
        return User::where('staff_id', '=', $staff_id)
            ->join('staff', 'user.user_id', '=', 'staff.user_id')
            ->first();
    }

    /**
     * Create a new Staff.
     *
     * @return \App\Models\Staff
     */
    public static function createStaff($userData): Staff
    {
        $user = User::createUser($userData);
        $staffData = $userData;
        $staffData['user_id'] = $user->user_id;
        $staff = Staff::create($staffData);
        Role::assignRoleToUser($staff->user_id, Role::RoleStaff);
        return $staff;
    }

    /**
     * Get all Staff with their User data.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function allWithUser(): Collection
    {
        return Staff::select('staff.*', 'user.name', 'user.email', 'user.profile_image')
            ->join('user', 'user.user_id', '=', 'staff.user_id')
            ->get();
    }

    /**
     * The user that corresponds to the administrator.
     *
     * @return HasOne
     */
    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'user_id', 'user_id');
    }

    /**
     * Return current model with its correspondig user.
     *
     * @return HasOne
     */
    public function withUser()
    {
        return $this->join('user', 'user.user_id', '=', 'staff.user_id')->first();
    }

    /**
     * User routes.
     */
    public static function getWebRoutes()
    {
        return [];
    }
}
