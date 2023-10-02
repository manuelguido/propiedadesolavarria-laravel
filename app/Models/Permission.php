<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Permission extends Model
{
    use HasFactory, SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | Administrator Permissions
    |--------------------------------------------------------------------------
    */
    const AdministratorIndex = 'admin_index';
    const AdministratorShow = 'admin_show';
    const AdministratorStore = 'admin_store';
    const AdministratorUpdate = 'admin_update';
    const AdministratorDelete = 'admin_delete';
    const AdministratorRestore = 'admin_restore';
    const AdministratorForceDelete = 'admin_f_delete';

    /*
    |--------------------------------------------------------------------------
    | Antiquity Type Permissions
    |--------------------------------------------------------------------------
    */
    const AntiquityTypeShow = 'antiquity_type_show';
    const AntiquityTypeStore = 'antiquity_type_store';
    const AntiquityTypeUpdate = 'antiquity_type_update';
    const AntiquityTypeDelete = 'antiquity_type_delete';
    const AntiquityTypeRestore = 'antiquity_type_restore';
    const AntiquityTypeForceDelete = 'antiquity_type_f_delete';

    /*
    |--------------------------------------------------------------------------
    | Client Permissions
    |--------------------------------------------------------------------------
    */
    const ClientIndex = 'client_index';
    const ClientShow = 'client_show';
    const ClientDelete = 'client_delete';
    const ClientRestore = 'client_restore';
    const ClientForceDelete = 'client_f_delete';

    /*
    |--------------------------------------------------------------------------
    | Favourite Collection Permissions
    |--------------------------------------------------------------------------
    */
    const FavouriteCollectionIndex = 'fc_index';
    const FavouriteCollectionShow = 'fc_show';
    const FavouriteCollectionStore = 'fc_store';
    const FavouriteCollectionUpdate = 'fc_update';
    const FavouriteCollectionForceDelete = 'fc_f_delete';

    /*
    |--------------------------------------------------------------------------
    | Favourite Post Permissions
    |--------------------------------------------------------------------------
    */
    const FavouritePostIndex = 'fp_index';
    const FavouritePostShow = 'fp_show';
    const FavouritePostStore = 'fp_store';
    const FavouritePostForceDelete = 'fp_f_delete';

    /*
    |--------------------------------------------------------------------------
    | Moderator Permissions
    |--------------------------------------------------------------------------
    */
    const ModeratorIndex = 'moderator_index';
    const ModeratorShow = 'moderator_show';
    const ModeratorStore = 'moderator_store';
    const ModeratorUpdate = 'moderator_update';
    const ModeratorDelete = 'moderator_delete';
    const ModeratorRestore = 'moderator_restore';
    const ModeratorForceDelete = 'moderator_f_delete';

    /*
    |--------------------------------------------------------------------------
    | Post Permissions
    |--------------------------------------------------------------------------
    */
    const PostIndex = 'post_index';
    const PostShow = 'post_show';
    const PostStore = 'post_store';
    const PostUpdate = 'post_update';
    const PostDelete = 'post_delete';
    const PostRestore = 'post_restore';
    const PostForceDelete = 'post_f_delete';

    /*
    |--------------------------------------------------------------------------
    | Property Permissions
    |--------------------------------------------------------------------------
    */
    const PropertyIndex = 'property_index';
    const PropertyShow = 'property_show';
    const PropertyStore = 'property_store';
    const PropertyUpdate = 'property_update';
    const PropertyDelete = 'property_delete';
    const PropertyRestore = 'property_restore';
    const PropertyForceDelete = 'property_f_delete';

    /*
    |--------------------------------------------------------------------------
    | Property Type Permissions
    |--------------------------------------------------------------------------
    */
    const PropertyTypeStore = 'property_type_store';
    const PropertyTypeUpdate = 'property_type_update';
    const PropertyTypeForceDelete = 'property_type_f_delete';

    /*
    |--------------------------------------------------------------------------
    | Rental Type Permissions
    |--------------------------------------------------------------------------
    */
    const RentalTypeStore = 'rental_type_store';
    const RentalTypeUpdate = 'rental_type_update';
    const RentalTypeForceDelete = 'rental_type_f_delete';

    /*
    |--------------------------------------------------------------------------
    | Renter Permissions
    |--------------------------------------------------------------------------
    */
    const RenterIndex = 'renter_index';
    const RenterShow = 'renter_show';
    const RenterStore = 'renter_store';
    const RenterUpdate = 'renter_update';
    const RenterDelete = 'renter_delete';
    const RenterRestore = 'renter_restore';
    const RenterForceDelete = 'renter_f_delete';

    /*
    |--------------------------------------------------------------------------
    | Staff Permissions
    |--------------------------------------------------------------------------
    */
    const StaffIndex = 'staff_index';
    const StaffShow = 'staff_show';
    const StaffStore = 'staff_store';
    const StaffUpdate = 'staff_update';
    const StaffDelete = 'staff_delete';
    const StaffRestore = 'staff_restore';
    const StaffForceDelete = 'staff_f_delete';

    /*
    |--------------------------------------------------------------------------
    | Surface Measurement Type Permissions
    |--------------------------------------------------------------------------
    */
    const SurfaceMeasurementTypeStore = 'smt_store';
    const SurfaceMeasurementTypeUpdate = 'smt_update';
    const SurfaceMeasurementTypeForceDelete = 'smt_f_delete';

    /**
     * Attributes
     */
    protected $table = 'permission';

    protected $primaryKey = 'permission_id';
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
     * The Roles that belong to the Permission.
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'permission_role', 'permission_id', 'role_id');
    }
}
