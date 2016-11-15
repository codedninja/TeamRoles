@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading clearfix">
                        Teams
                        <a class="pull-right btn btn-default btn-sm" href="{{route('teamroles.create')}}">
                            <i class="fa fa-plus"></i> Create team
                        </a>
                    </div>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Role</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($teams as $team)
                                <tr>
                                    <td>{{$team->name}}</td>
                                    <td>
                                        @if(Auth::user()->isOwnerOfTeam($team))
                                            <span class="label label-success">Owner</span>
                                        @else
                                            <span class="label label-primary">{{ Auth::user()->teamRoleFor($team)->name }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if( Auth::user()->currentTeam->getKey() === $team->getKey())
                                            <span class="label label-default">Current team</span>
                                        @endif

                                        @if(Auth::user()->isOwnerOfTeam($team))
                                            <form style="display: inline-block;" action="{{route('teamroles.destroy', $team)}}" method="post">
                                                {!! csrf_field() !!}
                                                <input type="hidden" name="_method" value="DELETE" />
                                        @endif
                                            <div class="btn-group btn-group-xs">
                                                @if(is_null(auth()->user()->currentTeam) || auth()->user()->currentTeam->getKey() !== $team->getKey())
                                                    <a href="{{route('teams.switch', $team)}}" class="btn btn-default">
                                                        <i class="fa fa-sign-in"></i> Switch
                                                    </a>
                                                @endif

                                                <a href="{{route('teamroles.members.show', $team)}}" class="btn btn-default">
                                                    <i class="fa fa-users"></i> Members
                                                </a>

                                                @if(Auth::user()->isOwnerOfTeam($team))

                                                    <a href="{{route('teamroles.edit', $team)}}" class="btn btn-default">
                                                        <i class="fa fa-pencil"></i> Edit
                                                    </a>

                                                    <button class="btn btn-danger"><i class="fa fa-trash-o"></i> Delete</button>
                                                @endif
                                            </div>
                                        @if(Auth::user()->isOwnerOfTeam($team))
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
