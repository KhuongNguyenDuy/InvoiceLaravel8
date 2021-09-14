<?php

namespace App\Models;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;
     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'create_date',
        'status',
        'total',
        'expire_date',
        'estimate_id',
        'order_id',
        'customer_id',
        'tax_rate'
    ];

    protected $table = 'invoices';
    
    public static function showCustomerInvoice($id){

        //get info customer and estimate
        $customerInvoice = DB::table('invoices')
              ->join('customers', 'invoices.customer_id', '=', 'customers.id')
              ->join('estimates', 'invoices.estimate_id', '=', 'estimates.id')              
              ->select(
                    'invoices.*',
                    'customers.id as customer_id',
                    'customers.name as customer_name',
                    'customers.address as customer_address',
                    'customers.phone as customer_phone',
                    'customers.fax as customer_fax',
                    'estimates.name as estimate_name',
                    'estimates.no as estimate_no'             
                     )
              ->where('invoices.id','=',$id)
              ->first();
            return $customerInvoice;
    }

    public static function showInvoiceCart($id){
        // get info invoice details
        $invoiceCart =  DB::table('invoices')
            ->join('invoice_item', 'invoices.id', '=', 'invoice_item.invoice_id')
            ->join('items', 'items.id', '=', 'invoice_item.item_id')
            ->join('projects', 'items.project_id', '=', 'projects.id')
            ->select(
                'items.id as item_id',
                'items.name as item_name',
                'items.price',
                'projects.name as project_name',
                'projects.id as project_id',
                'invoice_item.quantity',
                'invoice_item.amount'
                )
            ->where('invoices.id','=',$id)
            ->get();
            return $invoiceCart;
    }
    /**
     * SHOW all invoice
     *
     */
    public static function showAllInvoice(){
        $invoices = DB::table('invoices')
              ->join('customers', 'invoices.customer_id', '=', 'customers.id')
              ->join('estimates', 'invoices.estimate_id', '=', 'estimates.id')
              ->select('invoices.*','estimates.no as estimate_no','customers.name as customer_name','customers.address as customer_address','customers.phone as customer_phone')
              ->orderBy('invoices.id', 'DESC')
              ->Paginate(20);
        return $invoices;
    }
    /**
     * INSERT invoice
     *
     */
    public static function insertInvoice($invoice){
        $invoiceID = DB::table('invoices')->insertGetId($invoice);
        return $invoiceID;
    }

     /**
     * UPDATE invoice
     *
     */
    public static function updateInvoice($invoice_id,$content){
        $result = DB::table('invoices')->where('id', $invoice_id)->update($content);
        return $result;
    }

    /**
     * UPDATE status invoice
     *
     */
    public static function updateStatus($id,$status){
        $invoiceID = DB::table('invoices')->where('id', $id)->update(['status' => $status]);
        return $invoiceID;
    }
    /**
     * DELETE  invoice
     *
     */
    public static function deleteInvoice($id){
        DB::table('invoices')->where('id', $id)->delete();
    }

}
