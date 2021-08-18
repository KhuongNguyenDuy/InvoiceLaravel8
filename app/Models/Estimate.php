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
        'name',
        'path',
    ];
    protected $table = 'estimates';

    //show estimate by id
    public static function showEstimateById($id){
        $estimate = DB::table('estimates')->where('id',$id)->first();
        return $estimate;
    }
    //show all estimate
    public static function showAllEstimate(){
        $estimates = DB::table('estimates')->get();
        return $estimates;
    }

     //add estimate
    public static function insert($estimate){
        DB::table('estimates')->insert($estimate);
    }
}
