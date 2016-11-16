<?php

namespace Tehcodedninja\Teamroles\Listeners;

use Mpociot\Teamwork\Events\UserJoinedTeam;

class JoinedTeamListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  UserJoinedTeam  $event
     * @return void
     */
    public function handle(UserJoinedTeam $event)
    {
        $user = $event->getUser();
        $team_id = $event->getTeamId();

        // Do something with the user and team ID.
        $user->updateTeamRole(\Config::get( 'teamroles.default_owner_role'), $team_id)
    }
}