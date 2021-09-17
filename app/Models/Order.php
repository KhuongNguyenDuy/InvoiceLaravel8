<?php

namespace App\Models;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
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
    protected $table = 'orders';


    //show all order
    public static function showAllOrder(){
        $orders = DB::table('orders')
        ->join('projects', 'orders.project_id', '=', 'projects.id')
        ->select('orders.*','projects.name as project_name')
        ->orderBy('orders.id', 'DESC')
        ->Paginate(20);
        return $orders;
    }

    //show order by project id
    public static function getOrderByProjectId($id){
        $orders = DB::table('orders')->where('project_id', $id)
            ->orderBy('updated_at', 'desc')->orderBy('id', 'desc')->get();
        return $orders;
    }

    //show order by id
    public static function showOrderById($id){
        $order = DB::table('orders')->where('id', $id)->first();
        return $order;
    }

     //add order
    public static function insertOrder($order){
        DB::table('orders')->insert($order);
    }
    /**
     * update order
     */
    public static function editOrder($id,$order){
        DB::table('orders')->where('id',$id)->update($order);
    }
    /**
     * delete order
     */
    public static function deleteOrder($id){
        DB::table('orders')->where('id', '=', $id)->delete();
    }
    /**
     * check file order exist
     */
    public static function checkFileExist($fileName){
        $result = DB::table('orders')->where('name', '=', $fileName)->first();
        return $result;
    }


}

