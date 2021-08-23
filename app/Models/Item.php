<?php

namespace App\Models;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;
     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'price',
        'project_id',
    ];
    protected $table = 'items'; //set table item for model item

    /**
     * Get the project that owns the item.
     */
    public function project(){
        return $this->belongsTo('app/Models/Project');
    }
    public static function showAllItem(){
        $items = DB::table('items')->get();
        return $items;
    }
    public static function showItemByProjectId($id){
        $items = DB::table('items')->where('project_id','=',$id)->get();
        return $items;
    }
}


