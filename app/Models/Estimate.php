<?php

namespace App\Models;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estimate extends Model
{
    use HasFactory;
     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'no',
        'name',
        'path',
        'project_id'
    ];
    protected $table = 'estimates';

    //show estimate by id
    public static function showEstimateById($id){
        $estimate = DB::table('estimates')->where('id',$id)->first();
        return $estimate;
    }
    //show estimate by project id
    public static function getEstimateByProjectId($id){
        $estimate = DB::table('estimates')->where('project_id',$id)->get();
        return $estimate;
    }
    //show all estimate
    public static function showAllEstimate(){
        $estimates = DB::table('estimates')
        ->join('projects', 'estimates.project_id', '=', 'projects.id')
        ->select('estimates.*','projects.name as project_name')
        ->orderBy('estimates.id', 'DESC')
        ->Paginate(20);
        return $estimates;
    }    
     //add estimate
    public static function insert($estimate){
        DB::table('estimates')->insert($estimate);
    }
    /**
     * update estimate
     */
    public static function editEstimate($id,$estimate){
        DB::table('estimates')->where('id',$id)->update($estimate);
    }
    /**
     * delete estimate
     */
    public static function deleteEstimate($id){
        DB::table('estimates')->where('id', '=', $id)->delete();
    }



    

}
