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

    /**
     * add order
     */
    public static function insertOrder($order){
        DB::table('orders')->insert($order);
    }

    //show all order
    public static function showAllOrder(){
        $orders = DB::table('orders')
        ->join('projects', 'orders.project_id', '=', 'projects.id')
        ->select('orders.*','projects.name as project_name')
        ->orderBy('orders.id', 'DESC')
        ->Paginate(20);
        return $orders;
    }


}

