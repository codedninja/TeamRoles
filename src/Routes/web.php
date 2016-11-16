<?php

Route::group(['prefix' => 'admin/teamroles', 'middleware'=>'auth'], function() {
    // Admin of all team roles
    Route::get('/', 'AdminTeamRoleController@index')->name('admin.teamroles.index');
    Route::get('create', 'AdminTeamRoleController@create')->name('admin.teamroles.create');
    Route::post('create', 'AdminTeamRoleController@store')->name('admin.teamroles.store');
    Route::get('{id}/edit', 'AdminTeamRoleController@edit')->name('admin.teamroles.edit');
    Route::put('{id}/edit', 'AdminTeamRoleController@update')->name('admin.teamroles.update');
    Route::delete('{id}/destroy', 'AdminTeamRoleController@destroy')->name('admin.teamroles.destroy');
});

Route::group(['prefix' => 'teamroles', 'middleware'=>'auth'], function() {
    // Team Routes
    Route::get('/', 'TeamRoleController@index')->name('teamroles.index');
    Route::get('/create', 'TeamRoleController@create')->name('teamroles.create');
    Route::post('/create', 'TeamRoleController@store')->name('teamroles.store');
    Route::get('{id}/edit', 'TeamRoleController@edit')->name('teamroles.edit');
    Route::put('{id}/edit', 'TeamRoleController@update')->name('teamroles.update');
    Route::put('{id}/destroy', 'TeamRoleController@destroy')->name('teamroles.destroy');

    // Member Routes
    Route::get('{id}/members', 'TeamRoleMembersController@show')->name('teamroles.members.show');
    Route::get('{team_id}/members/{user_id}/edit', 'TeamRoleMembersController@edit')->name('teamroles.members.edit');
    Route::put('{team_id}/members/{user_id}/edit', 'TeamRoleMembersController@update')->name('teamroles.members.update');
});