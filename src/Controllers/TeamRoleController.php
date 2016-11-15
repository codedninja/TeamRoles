<?php

namespace Tehcodedninja\Teamroles\Controllers;

use App\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class TeamRoleController extends Controller
{

    function index()
    {
        $teams = Auth::user()->teams;
        return view('teamroles::index')->with('teams', $teams);
    }   

    function create()
    {
        return view('teamroles::create');
    }

    function store(Request $request)
    {
        $team = Team::create([
            'name' => $request->name,
            'owner_id' => $request->user()->id
        ]);
        
        $request->user()->attachTeam($team);
        $request->user()->attachTeamRole(1, $team); // Attach User as the owner of the group

        return redirect(route('teamroles.index'));
    }

    function edit($id)
    {
        $team = Team::findOrFail($id);

        if (! Auth::user()->isOwnerOfTeam($team)) {
            abort(403);
        }

        return view('teamroles::edit')->with('team', $team);
    }

    function update(Request $request, $id)
    {
        $team = Team::findOrFail($id);

        if (! Auth::user()->isOwnerOfTeam($team)) {
            abort(403);
        }

        $team->name = $request->name;
        $team->save();

        return redirect(route('teamroles.index'));
    }

    function destroy($id)
    {
        $team = Team::findOrFail($id);

        if (! Auth::user()->isOwnerOfTeam($team)) {
            abort(403);
        }

        $team->delete();

        User::where('current_team_id', $id)
                    ->update(['current_team_id' => null]);

        return redirect(route('teamroles.index'));
    }
}
