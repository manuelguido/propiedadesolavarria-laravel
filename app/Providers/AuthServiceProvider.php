<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
            \App\Models\Administrator::class => \App\Policies\AdministratorPolicy::class,
            \App\Models\AntiquityType::class => \App\Policies\AntiquityTypePolicy::class,
            \App\Models\Client::class => \App\Policies\ClientPolicy::class,
            \App\Models\Currency::class => \App\Policies\CurrencyPolicy::class,
            \App\Models\FavouriteCollection::class => \App\Policies\FavouriteCollectionPolicy::class,
            \App\Models\FavouritePost::class => \App\Policies\FavouritePostPolicy::class,
            \App\Models\Moderator::class => \App\Policies\ModeratorPolicy::class,
            \App\Models\Post::class => \App\Policies\PostPolicy::class,
            \App\Models\Property::class => \App\Policies\PropertyPolicy::class,
            \App\Models\PropertyType::class => \App\Policies\PropertyTypePolicy::class,
            \App\Models\RentalType::class => \App\Policies\RentalTypePolicy::class,
            \App\Models\Renter::class => \App\Policies\RenterPolicy::class,
            \App\Models\Staff::class => \App\Policies\StaffPolicy::class,
            \App\Models\SurfaceMeasurementType::class => \App\Policies\SurfaceMeasurementTypePolicy::class,
            \App\Models\SurfaceType::class => \App\Policies\SurfaceTypePolicy::class,
            \App\Models\User::class => \App\Policies\UserPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
    }
}
