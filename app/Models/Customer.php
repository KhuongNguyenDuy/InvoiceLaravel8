<?php

namespace App\Models;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'abbreviate',
        'address',
        'phone',
        'fax',
    ];
    protected $table = 'customers'; 
    /**
     * show list customer
     */
    public static function showAllCustomer(){
        $customers = DB::table('customers')
        ->orderBy('customers.id', 'DESC')
        ->Paginate(15);
        return $customers;
    }
     /**
     * show customer by id 
     */
    public static function showCustomerById($id){
        $customers = DB::table('customers')->where('id',$id)->get();
        return $customers;
    }
     /**
     * insert customer
     */
    public static function insert($customer){
        DB::table('customers')->insert($customer);
    }
    /**
     * edit customer
     */
    public static function edit($id,$customer){
        DB::table('customers')->where('id',$id)->update($customer);
    }
    /**
     * delete customer
     */
    public static function destroy($id){
        DB::table('customers')->where('id', '=', $id)->delete();
    }

}
