<?php

namespace App\Models;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends BaseModel
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'customer_id'
    ];
    protected $table = 'projects';
    /**
     * Get the items for the project.
     */
    public function items(){
        return $this->hasMany( 'App\Models\Item', 'project_id', 'id' );
    }
    public static function showAllProject(){
        $projects = DB::table('projects')
        ->join('customers', 'projects.customer_id', '=', 'customers.id')
        ->select('projects.*','customers.name as customer_name')
        ->orderBy('projects.id', 'DESC')
        ->Paginate(20);
        return $projects;
    }
    public static function getAllProject(){
        $projects = DB::table('projects')->get();
        return $projects;
    }
    public static function showProjectById($id){
        $projects = DB::table('projects')->where('id', $id)->first();
        return $projects;
    }
    /**
     * get project by customer id
     */
    public static function getProjectByCustomerId($id){
        $projects = DB::table('projects')->where('customer_id', $id)
            ->orderBy('updated_at', 'desc')->orderBy('id', 'desc')->get();
        return $projects;
    }

    /**
     * insert project
     */
    public static function insertProject($project){
        DB::table('projects')->insert($project);
    }
    /**
     * edit project
     */
    public static function edit($id,$project){
        DB::table('projects')->where('id',$id)->update($project);
    }
    public static function destroy($id){
        DB::table('projects')->where('id', '=', $id)->delete();
    }
}
