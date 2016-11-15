<?php

namespace Tehcodedninja\Teamroles\Models;

use Illuminate\Database\Eloquent\Model;

class TeamRole extends Model
{
    protected $table = 'team_roles';
    
    protected $fillable = [ 'name', 'label' ];

    public function users()
    {
        return $this->belongsToMany('App\User');
    }
}
