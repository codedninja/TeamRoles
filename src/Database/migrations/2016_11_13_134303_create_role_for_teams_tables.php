<?php

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

        // Add team role id to team user table
        Schema::table(\Config::get( 'teamwork.team_user_table' ), function (Blueprint $table) {
            $table->integer('team_role_id')->unsigned()->default(\Config::get( 'teamroles.default_team_role'));
        });

        // Add team owner's to role table and give default role
        App\Team::all()->each(function($team){
            $users = $team->users;

            foreach($users as $user)
            {
                // Legacy isOwnerOfTeam($team)
                $team_model   = Config::get( 'teamwork.team_model' );
                $team_key_name = ( new $team_model() )->getKeyName();
                $isOwnerOfTeam = ( ( new $team_model )
                    ->where( "owner_id", "=", $user->getKey() )
                    ->where( $team_key_name, "=", $team->id )->first()
                ) ? true : false;

                // Check if owner
                if($isOwnerOfTeam)
                {
                    // Add Owner role to team's Owner
                    $user->changeTeamRole(\Config::get( 'teamroles.default_owner_role'), $team->id);
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
        Schema::table(\Config::get( 'teamwork.team_user_table' ), function ($table) {
            $table->dropColumn('team_role_id');
        });
        Schema::dropIfExists('team_roles');
    }
}