<?php

namespace App\Models;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Invoice;
use App\Models\InvoiceItem;

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
        $itemId = DB::table('items')->insertGetId($item);
        return $itemId;
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
        // pp:1
        // $invoices = DB::table('invoices')
        // ->whereIn('invoices.id',function ($query) use ($id){
        //                                   $query->select('invoice_item.invoice_id')
        //                                       ->from('items')
        //                                       ->join('invoice_item', 'invoice_item.item_id', '=', 'items.id')
        //                                       ->where('items.id',$id);
        //                                     })
        
        // ->get();
        // DB::table('items')->where('id', $id)->delete();
        // foreach ($invoices as $invoice) {
        //     Invoice::deleteInvoice($invoice->id);
        //     InvoiceItem::deleteInvoiceItem($invoice->id);
        // }
        //pp: 2
       
        $invoices = DB::table('items')
        ->join('invoice_item', 'invoice_item.item_id', '=', 'items.id')
        ->select('invoice_item.invoice_id')
        ->where('items.id',$id)
        ->get();
        foreach ($invoices as $invoice) {
            Invoice::deleteInvoice($invoice->invoice_id);
            InvoiceItem::deleteInvoiceItem($invoice->invoice_id);
        }
        DB::table('items')->where('id', $id)->delete();
    }
    
}


