<?php namespace Tehcodedninja\Teamroles\Traits;

/**
 * This file is part of Teamroles,
 *
 * @license MIT
 * @package Teamroles
 */

trait TeamHasTeamRoles
{
    public function userRoles()
    {
        return $this->belongsToMany('App\User', 'team_role_user')->withPivot('team_role_id');
    }
}