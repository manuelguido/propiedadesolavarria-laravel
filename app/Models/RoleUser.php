<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class RoleUser extends Pivot
{
    use HasFactory;

    protected $table = 'role_user';

    public $timestamps = false;

    protected $fillable = [
        'role_id',
        'user_id',
    ];

    /**
     * Assig role to a user by id.
     *
     * @param $user_id int
     * @param $role_id int
     * @return void
     */
    public static function assignRoleToUser($user_id, $role_id): void
    {
        RoleUser::create([
            'user_id' => $user_id,
            'role_id' => $role_id,
        ]);
    }
}
