<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Project;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{

    /**
     * show list order
     */
    public function index(){
        $orders = Order::showAllOrder();
        return view('Order.order_list') -> with('orders',$orders);
    }
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
            return redirect('/orders')->with('fail', 'Chưa chọn file cần upload');
        }
        //get path to save
        $path = config('global.order_files_path');
        //get file
        $file = $request->file('orderFile');
        $request->file('orderFile')->storeAs($path,  $file->getClientOriginalName(),'local'); // set folder to upload and set file name: local:storage->app->download_upload | public: storage->app->public->download_upload
        $fileName = $file->getClientOriginalName();
        // insert database
        DB::beginTransaction();
        try {
           $order = array(
            'no' => $request->order_no,
            'name' => $fileName,
            'amount' => $request->amount,
            'path' => '' ,
            'project_id' => $request->project
            );
            $result = Order::checkFileExist($fileName);
            if($result){
                return redirect('/orders')->with('fail', 'Thêm Order thất bại! File đã tồn tại.');
            }
            Order::insertOrder($order);
            DB::commit();
        }
        catch (Exception $e) {
            DB::rollback();
        }
       return redirect('/orders')->with('success', 'Thêm Order thành công');
    }
    /**
     * show form edit order
     */
    public function formEditOrder($id){
        $order = Order::showOrderById($id);
        $projects = Project::getAllProject();
        return view('Order.edit_order',[
            'projects' => $projects,
            'order' => $order
        ]);
    }
     /**
     * edit order and change up file to server
     * Upload to folder: storage
     */
    public function editOrder(Request $request){
        $file = $request->file('orderFile');
        //if do not change file
        if(!$file){
            DB::beginTransaction();
            try {
               $order = array(
                   'no' => $request->order_no,
                   'name' => $request->order_name,
                   'amount' => $request->amount,
                   'path' => '',
                   'project_id' => $request->project
                );
                Order::editOrder($request->order_id,$order);
                DB::commit();
            }
            catch (Exception $e) {
                DB::rollback();
            }
            return redirect('/orders')->with('success', 'Sửa Order thành công');
        }else{
            Validator::make($request->all(), [
                'orderFile' => 'mimes:csv,xlsx,txt,xlx,xls,pdf|max:2048'
            ])->validateWithBag('order');
            $fileOrigin = $request->order_name;
            $newFile = $file->getClientOriginalName();
            $result = Order::checkFileExist($newFile);
            $checkFileName = strcmp($fileOrigin,$newFile);
            //if file input not duplication
            if($checkFileName == 0 || $result == null){
                DB::beginTransaction();
                try {
                    //get path to save
                    $path = config('global.order_files_path');
                    Storage::delete('/'.$path.'/'.$request->order_name);
                    $request->file('orderFile')->storeAs($path, $file->getClientOriginalName(),'local'); // set folder to upload and set file name: local:storage->app->download_upload | public: storage->app->public->download_upload
                    // insert database
                    $order = array(
                       'no' => $request->order_no,
                       'name' => $newFile,
                        'amount' => $request->amount,
                        'path' => '',
                       'project_id' => $request->project
                    );
                    Order::editOrder($request->order_id,$order);
                    DB::commit();
                }
                catch (Exception $e) {
                    DB::rollback();
                }
                return redirect('/orders')->with('success', 'Sửa Order thành công');
            }else{
                return redirect('/orders')->with('fail', 'Sửa Order thất bại! File đã tồn tại.');
            }
        }
    }
    /**
     * Delete order
     */
    public function deleteOrder($id){
        //get path to save
        $path = config('global.order_files_path');
        $fileName = Order::showOrderById($id);
        DB::beginTransaction();
        try {
            Storage::delete('/'.$path.'/'.$fileName->name);
            Order::deleteOrder($id);
            DB::commit();
        }
        catch (Exception $e) {
            DB::rollback();
        }
       return redirect('/orders')->with('success', 'Xoá Order thành công!');
    }

    /**
     * Download file to my computer
     */
    public function downloadOrder($id){
        $order = Order::showOrderById($id);
        if( $order != ""){
            $path = config('global.order_files_path');
            $fileName =  $order->name;
            $filePath = storage_path()."/app/".$path."/".$fileName;
            return response()->download($filePath);
        }
    }
}
