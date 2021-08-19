<?php

namespace App\Models;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'invoice_id', 'item_id', 'quantity', 'price', 'amount',
    ];

    protected $table = 'invoice_item';
    protected $primaryKey = ['invoice_id', 'item_id'];
    public $incrementing = false;

    //insert invoice detail
    public static function insert($invoiceItem){
        DB::table('invoice_item')->insert($invoiceItem);
    }


}
