<?php

namespace App\Models;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];
    protected $table = 'projects';
    /**
     * Get the items for the project.
     */
    public function items(){
        return $this->hasMany( 'App\Models\Item', 'project_id', 'id' );
    }
    public static function showAllProject(){
        $projects = DB::table('projects')->get();
        return $projects;
    }
    public static function showProjectById($id){
        $project = DB::table('projects')->where('id','=',$id)->first();
        return $project;
    }
}
