<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Add order and upload file to server
     * Upload to folder: storage
     */
    public function addOrder(Request $request){
        //validate
        Validator::make($request->all(), [
            'orderFile' => 'required|mimes:csv,xlsx,txt,xlx,xls,pdf|max:2048'
        ])->validateWithBag('order');
        //check have file
        if (!$request->hasFile('orderFile')) {
            return redirect('/estimates')->with('success', 'Chưa chọn file cần upload');
        }
        //get path to save
        $path = config('global.order_files_path');
        //get file
        $file = $request->file('orderFile');
        //$filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME); //get file name not include extension
        $request->file('orderFile')->storeAs($path,  $file->getClientOriginalName(),'local'); // set folder to upload and set file name: local:storage->app->download_upload | public: storage->app->public->download_upload
        // insert database
        DB::beginTransaction();
        try {
           $order = array(
            'no' => $request->order_no,
            'name' => $file->getClientOriginalName(),
            'path' => '' ,
            'project_id' => $request->project      
            );
            Order::insertOrder($order);
            DB::commit();
        }
        catch (Exception $e) {
            DB::rollback();
        }        
       return redirect('/estimates')->with('success', 'Thêm Order thành công');
    }
}
