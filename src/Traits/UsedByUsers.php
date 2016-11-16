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
use Tehcodedninja\Teamroles\Models\TeamRole;

trait UsedByUsers
{
    /**
     * Many-to-Many relations with the user model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function teamRoles()
    {
        return $this->belongsToMany('Tehcodedninja\Teamroles\Models\TeamRole', \Config::get( 'teamwork.team_user_table' ))->withPivot('team_id');
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
     * Update user's role in a team
     *
     * @param integer $team_role
     * @param mixed $team
     * @return \App\User
     */
    public function updateTeamRole($team_role, $team = null)
    {
        // Get Team Model instance
        $team_id = $this->retrieveTeamId($team);

        // No team provider so get currentTeam
        if($team_id === null)
        {
            $team_id = $this->currentTeam->id;
        }

        $team_model   = Config::get( 'teamwork.team_model' );
        $team = ( new $team_model() )->find($team_id);

        // Check if in in team
        if(!$team->users->contains( $this->id ))
        {
            $exception = new UserNotInTeamException();
            $exception->setTeam( $team->name );
            throw $exception;
        }

        // Get TeamRole Model instance
        $team_role = TeamRole::find($team_role);

        // Save TeamRole to pivot
        $team->users()->updateExistingPivot($this->id, ['team_role_id'=>$team_role->id]);

        return $this;
    }
}