# Team Roles for mpociot/teamwork

## Installation

This package is very easy to setup and install.

### Composer

Pull this package in through Composer (file `composer.json`).

```js
{
    "require": {
        "tehcodedninja/teamroles": "1.1.0"
    }
}
```
The run `composer install` or `composer update`.
Alternatively you can run `composer require tehcodedninja/teamroles` to install this package.

### Service Provider

Add the package to your application service providers in `config/app.php` file.

```php
'providers' => [
    
    /*
     * Laravel Framework Service Providers...
     */
    Illuminate\Foundation\Providers\ArtisanServiceProvider::class,
    Illuminate\Auth\AuthServiceProvider::class,
    ...
    
    /**
     * Third Party Service Providers...
     */
    Tehcodedninja\Teamroles\TeamRoleProvider::class,
],
```

### Config File And Migrations

Publish the package config file and migrations to your application. Run these commands inside your terminal.

    php artisan vendor:publish --provider="Tehcodedninja\Teamroles\TeamRoleProvider" --tag=config
    php artisan vendor:publish --provider="Tehcodedninja\Teamroles\TeamRoleProvider" --tag=migrations

And also run migrations.

    php artisan migrate

### Models

#### User

Add the `UsedByUsers` trait to your existing User model:

```php
<?php namespace App;

use Mpociot\Teamwork\Traits\UserHasTeams;
use Tehcodedninja\Teamroles\Traits\UsedByUsers;

class User extends Model {
    use UserHasTeams;
    use UsedByUsers {                                                                       // Add these lines starting here
        UsedByUsers::isOwnerOfTeam insteadof UserHasTeams;     // 
    }                                                                                                     // Till here
}
```

This will enable the relation with `TeamRoles` and add the following methods `teamRoles()`, `teamRoleFor($team)` `isOwnerOfTeam($team)`, `CurrentTeamRole()`, `isTeamRole($team_role)`, `updateTeamRole($team_role, $team)` within your `User` model.

Don't forget to dump composer autoload

```bash
composer dump-autoload
```	
### Scaffolding

The easiest way to give your new Laravel project Team Role abilities is by adding the demo application service provider in `config/app.php` file.

```php
'providers' => [
    
    /*
     * Laravel Framework Service Providers...
     */
    Illuminate\Foundation\Providers\ArtisanServiceProvider::class,
    Illuminate\Auth\AuthServiceProvider::class,
    ...
    
    /**
     * Third Party Service Providers...
     */
    Tehcodedninja\Teamroles\TeamRoleDemoProvider::class,
],
```

Adding this service provider links all the Models/Views/Controllers to you application without moving anything.

The demo provides you everything like TeamWork does but with the ability to assign roles to people for each team:

* Team listing
* Team creation / editing / deletion
* Invite new members to teams
* Team Role creation / editing / deletion
* Change members' role

To get started, take a look at the new installed `/teamroles` and `/admin/teamroles` in your project.
