<?php

namespace App\Providers;

use Carbon\CarbonImmutable;
use CWSPS154\UsersRolesPermissions\Filament\Clusters\UserManager\Resources\UserResource;
use CWSPS154\UsersRolesPermissions\UsersRolesPermissionsPlugin;
use Filament\Panel;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if (!class_exists('Filament\Infolists\Infolist')) {
            class_alias('Filament\Schemas\Schema', 'Filament\Infolists\Infolist');
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureDefaults();
    }

    // public function panel(Panel $panel): Panel
    // {
    //     return $panel
    //         ->plugins([
    //             UsersRolesPermissionsPlugin::make()
    //                 ->setUserResource(UserResource::class), // optional if you have a custom UserResource
    //         ]);
    // }

    public function panel(Panel $panel): Panel
    {
        return $panel
            ->plugins([
                UsersRolesPermissionsPlugin::make(),
            ]);
    }


    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(
            fn(): ?Password => app()->isProduction()
                ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
                : null
        );
    }
}
