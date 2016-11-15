<?php

namespace Tehcodedninja\Teamroles\Controllers;

use Illuminate\Http\Request;
use Tehcodedninja\Teamroles\Models\TeamRole;
use App\Http\Controllers\Controller;

class AdminTeamRoleController extends Controller
{
    
    function index()
    {
        $roles = TeamRole::all();
        return view('teamroles::admin.index')->with('roles', $roles);
    }

    function create()
    {
        return view('teamroles::admin.create');
    }

    function store(Request $request)
    {
        TeamRole::create([
            'name' => $request->name,
            'label' => $request->label
        ]);

        return redirect(route('admin.teamroles.index'));
    }

    function edit($id)
    {
        $team_role = TeamRole::findOrFail($id);

        return view('teamroles::admin.edit')
            ->with('team_role', $team_role);
    }

    function update(Request $request, $id)
    {
        $team_role = TeamRole::findOrFail($id);
        $team_role->name = $request->name;
        $team_role->label = $request->label;
        $team_role->save();

        return redirect(route('admin.teamroles.index'));
    }

    function destroy($id)
    {
        $team_role = TeamRole::findOrFail($id);
        $team_role->delete();

        return redirect(route('admin.teamroles.index'));
    }
}
