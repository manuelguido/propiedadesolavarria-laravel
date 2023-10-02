<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Moderator extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Attributes
     */
    protected $table = 'moderator';

    protected $primaryKey = 'moderator_id';

    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'moderator_id',
        'user_id',
    ];

    /**
     * Get the Moderator with the User.
     *
     * @return \App\Models\User
     */
    public static function findWithUser($moderator_id)
    {
        return User::where('moderator_id', '=', $moderator_id)
            ->join('moderator', 'user.user_id', '=', 'moderator.user_id')
            ->first();
    }

    /**
     * Create a new Moderator.
     *
     * @return \App\Models\Moderator
     */
    public static function createModerator($userData): Moderator
    {
        $user = User::createUser($userData);
        $moderatorData = $userData;
        $moderatorData['user_id'] = $user->user_id;
        $moderator = Moderator::create($moderatorData);
        Role::assignRoleToUser($moderator->user_id, Role::RoleModerator);
        return $moderator;
    }

    /**
     * Get all moderators with their user data.
     *
     * @return \App\Models\Moderator
     */
    public static function allWithUser(): Moderator
    {
        return Moderator::select('moderator.*', 'user.name', 'user.email', 'user.profile_image')
            ->join('user', 'user.user_id', '=', 'moderator.user_id')
            ->get();
    }

    /**
     * The user that corresponds to the administrator.
     *
     * @return \App\Models\User
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
        return $this->join('user', 'user.user_id', '=', 'moderator.user_id')->first();
    }

    /**
     * User routes.
     */
    public static function getWebRoutes()
    {
        return [];
    }
}
