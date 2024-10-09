<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Consultations;
use App\Models\Appointment;
use App\Models\Client;
use App\Policies\ConsultationPolicy;
use App\Policies\AppointmentPolicy;
use App\Policies\ClientPolicy;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(Appointment::class, AppointmentPolicy::class);
        Gate::policy(Client::class, ClientPolicy::class);
        Gate::policy(Consultations::class, ConsultationPolicy::class);
        
    }
}
