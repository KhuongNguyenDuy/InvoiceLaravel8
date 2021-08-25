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

    /**
     * Get list item
     */

    public static function showAllItem(){
        $items = DB::table('items')
        ->join('projects', 'items.project_id', '=', 'projects.id')
        ->select('items.id','items.name','items.price','projects.name as project_id')
        ->orderBy('items.id', 'DESC')
        ->Paginate(20);
        return $items;
    }

    /**
     * get item by project id
     */
    public static function showItemByProjectId($id){
        $items = DB::table('items')->where('project_id','=',$id)->get();
        return $items;
    }

    /**
     * show customer by id 
     */
    public static function showItemById($id){
        $item = DB::table('items')->where('id',$id)->first();
        return $item;
    }

    /**
     * insert item 
     */
    public static function insertItem($item){
        DB::table('items')->insert($item);
    }

    /**
     * edit item
     */
    public static function editItem($id,$item){
        DB::table('items')->where('id',$id)->update($item);
    }

    /**
     * DELETE  item
     *
     */
    public static function deleteItem($id){
        DB::table('items')->where('id', $id)->delete();
    }
    
}


