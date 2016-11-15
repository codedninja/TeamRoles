<?php namespace Tehcodedninja\Teamroles\Traits;

/**
 * This file is part of Teamroles,
 *
 * @license MIT
 * @package Teamroles
 */

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Config;
use Mpociot\Teamwork\Exceptions\UserNotInTeamException;

trait UsedByUsers
{
    /**
     * Many-to-Many relations with the user model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function teamRoles()
    {
        return $this->belongsToMany('Tehcodedninja\Teamroles\Models\TeamRole')->withPivot('team_id');
    }

    /**
     * Get the user's role for a team
     *
     * @param mixed $team
     * @return \Tehcodedninja\Teamroles\TeamRole
     */
    public function teamRoleFor($team)
    {
        $team_id = $this->retrieveTeamId($team);
        return $this->teamRoles()->wherePivot('team_id', $team_id)->first();
    }

    /**
     * Get the user's role for a team
     *
     * @param mixed $team
     * @return boolean
     */
    public function isOwnerOfTeam($team)
    {
        $role = $this->teamRoleFor($team);

        if($role->id === Config::get('teamroles.default_owner_role'))
        {
            return true;
        }

        return false;
    }

    /**
     * Get the user's role for the current team
     *
     * @return \Tehcodedninja\Teamroles\TeamRole
     */
    public function getCurrentTeamRoleAttribute()
    {
        return $this->teamRoles()->wherePivot('team_id', $this->currentTeam->id)->first();
    }

    /**
     * Check if user has the right role for current team
     *
     * @param string $role
     * @return boolean
     */
    public function isTeamRole($role)
    {
        if ($this->currentTeamRole->name === $role) {
            return true;
        }
        return false;
    }

    /**
     * Remove an user's role from a team
     *
     * @param mixed $team
     * @return \App\User
     */
    public function detachTeamRole($team_id)
    {
        $team_id = $this->retrieveTeamId($team_id);

        // No get so get current team
        if($team_id === null)
        {
            $team_id = $this->currentTeam->id;
        }

        // Get Current Role ID
        $team_model   = Config::get( 'teamwork.team_model' );
        $team = ( new $team_model() )->find($team_id);

        $role_id = $team->userRoles()->wherePivot('user_id', $this->id)->first()->pivot->team_role_id;

        $this->teamRoles()->wherePivot('team_id', $team_id)->detach($role_id);

        if($this->relationLoaded('teamRoles'))
        {
            $this->load('teamRoles');            
        }

        return $this;
    }

    /**
     * Add a role for a team to a user
     *
     * @param integer $team_role_id
     * @param mixed $team_id
     * @return \App\User
     */
    public function attachTeamRole($team_role_id, $team_id = null)
    {
        $team_id = $this->retrieveTeamId($team_id);

        // No get so get current team
        if($team_id === null)
        {
            $team_id = $this->currentTeam->id;
        }

        $team_model   = Config::get( 'teamwork.team_model' );
        $team = ( new $team_model() )->find($team_id);

        // Check if user is in the team
        if(!$team->users->contains( $this->id ))
        {
            $exception = new UserNotInTeamException();
            $exception->setTeam( $team->name );
            throw $exception;
        }

        // Check if user has role
        $currentTeamRole = $this->teamRoles()->wherePivot('team_id', $team_id)->first();
        if($currentTeamRole)
        {
            // Remove current person's role
            $this->detachTeamRole($team_id);
        }

        // Attach person to
        $this->teamRoles()->attach($team_role_id, ['team_id'=>$team_id]);

        if($this->relationLoaded('teamRoles'))
        {
            $this->load('teamRoles');            
        }

        return $this;
    }
}