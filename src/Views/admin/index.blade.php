@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading clearfix">
                        Team Roles
                        <a class="pull-right btn btn-default btn-sm" href="{{ route('admin.teamroles.create') }}">
                            <i class="fa fa-plus"></i> Create Role
                        </a>
                    </div>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Label</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($roles as $role)
                                <tr>
                                    <td>{{ $role->name }}</td>
                                    <td>{{ $role->label }}</td>
                                    <td>
                                        <form style="display: inline-block;" action="{{route('admin.teamroles.destroy', $role)}}" method="post">
                                            {!! csrf_field() !!}
                                            <input type="hidden" name="_method" value="DELETE" />
                                            <div class="btn-group btn-group-xs">
                                                <a href="{{route('admin.teamroles.edit', $role)}}" class="btn btn-info">Edit</a>
                                                <button class="btn btn-danger">Delete</button>
                                            </div>
                                        </form>
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
