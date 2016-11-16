<?php

namespace Tehcodedninja\Teamroles;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class TeamRoleProvider extends ServiceProvider
{

    protected $listen = [
        \Mpociot\Teamwork\Events\UserJoinedTeam::class => [
            Tehcodedninja\Teamroles\Listeners\JoinedTeamListener::class,
        ]
    ];

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([__DIR__.'/Database/migrations/2016_11_13_134303_create_role_for_teams_tables.php' =>
            base_path('database/migrations/2016_11_13_134303_create_role_for_teams_tables.php')
            ], 'migrations');

        $this->publishes([__DIR__.'/Config/teamrole.php' =>
            config_path()
            ], 'config');

        $this->registerBladeExtensions();
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
    }

    public function registerBladeExtensions()
    {
        Blade::directive('teamrole', function ($expression) {
            return "<?php if (Auth::check() && Auth::user()->isTeamRole({$expression})): ?>";
        });

        Blade::directive('endteamrole', function () {
            return "<?php endif; ?>";
        });
    }
}
