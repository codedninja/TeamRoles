<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoleForTeamsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Roles Table
        Schema::create('team_roles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('label')->nullable();
            $table->timestamps();
        });

        // Add default roles
        Tehcodedninja\Teamroles\Models\TeamRole::create(['name'=>'Owner', 'label'=>'owner']);
        Tehcodedninja\Teamroles\Models\TeamRole::create(['name'=>'Admin', 'label'=>'admin']);
        Tehcodedninja\Teamroles\Models\TeamRole::create(['name'=>'Member', 'label'=>'member']);

        // Team Role User Table
        Schema::create('team_role_user', function (Blueprint $table) {
            $table->integer('team_role_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->integer('team_id')->unsigned();
            // $table->foreign('team_role_id')
            //       ->references('id')
            //       ->on('team_roles');
                  // ->onDelete('cascade');
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
            $table->foreign('team_id')
                  ->references('id')
                  ->on('teams')
                  ->onDelete('cascade');
            $table->primary(['user_id', 'team_id']);
        });

        // Add team owner's to role table and give default role
        App\Team::all()->each(function($team){
            $users = $team->users;

            foreach($users as $user)
            {
                // Check if owner
                if($user->isOwnerOfTeam($team))
                {
                    // Add Owner role to team's Owner
                    $user->attachTeamRole(Config::get( 'teamrole.default_owner_role'), $team->id);
                }
                else
                {
                    // Add default role to rest of the team
                    $user->attachTeamRole(Config::get( 'teamrole.default_team_role'), $team->id);
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::dropIfExists('team_role_user');
        Schema::dropIfExists('team_roles');
    }
}
