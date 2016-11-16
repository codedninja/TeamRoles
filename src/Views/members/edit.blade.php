@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Edit team role of <strong>{{ $user-> name }}</strong> for team {{$team->name}}</div>
                    <div class="panel-body">
                        <form class="form-horizontal" method="post" action="{{route('teamroles.members.update', ['team_id'=>$team->id, 'user_id'=>$user->id])}}">
                            <input type="hidden" name="_method" value="PUT" />
                            {!! csrf_field() !!}

                            <div class="form-group{{ $errors->has('role') ? ' has-error' : '' }}">
                                <label class="col-md-4 control-label">Role</label>

                                <div class="col-md-6">
                                    <select name="role" class="form-control" value="{{ old('role', $team->role) }}">
                                        @foreach($roles as $role)
                                            <option value="{{$role->id}}">{{$role->name}}</option>
                                        @endforeach
                                    </select>
                                    {{-- <input type="text" class="form-control" name="role" value="{{ old('role', $team->role) }}"> --}}

                                    @if ($errors->has('role'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('role') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>


                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa fa-btn fa-save"></i>Save
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
