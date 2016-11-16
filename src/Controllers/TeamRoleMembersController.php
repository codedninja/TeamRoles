<?php

namespace Tehcodedninja\Teamroles\Controllers;

use App\Team;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Tehcodedninja\Teamroles\Models\TeamRole;
use App\Http\Controllers\Controller;

class TeamRoleMembersController extends Controller
{
    function show($id)
    {
        $team = Team::findOrFail($id);
        return view('teamroles::members.list')->with('team', $team);
    }

    function edit($team_id, $user_id)
    {
        $team = Team::findOrFail($team_id);
        $user = User::findOrFail($user_id);
        $roles = TeamRole::where('id', '!=', Config::get('teamroles.default_owner_role'))->get();

        return view('teamroles::members.edit')
            ->with('team', $team)
            ->with('user', $user)
            ->with('roles', $roles);
    }

    function update(Request $request, $team_id, $user_id)
    {
        $team = Team::findOrFail($team_id);
        $user = User::findOrFail($user_id);

        $user->updateTeamRole($request->role, $team);

        return redirect(route('teamroles.members.show', $team_id));
    }
}
