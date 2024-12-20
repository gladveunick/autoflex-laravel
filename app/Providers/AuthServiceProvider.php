<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\User;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot()
    {
        $this->registerPolicies();

        // Définir la politique 'admin' pour vérifier si l'utilisateur a un rôle d'administrateur
        Gate::define('admin', function (User $user) {
            return $user->role === 'admin'; // Vérifie si l'utilisateur a le rôle 'admin'
        });


        // Définir la politique 'proprietaire' pour vérifier si l'utilisateur a un rôle de propriétaire
        Gate::define('proprietaire', function (User $user) {
            return $user->role === 'proprietaire';
        });
    }
}
