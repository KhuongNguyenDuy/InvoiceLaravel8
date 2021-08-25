<?php

namespace App\Http\Controllers;
use App\Models\Estimate;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;


class EstimateController extends Controller
{
    /**
     * show list estimate
     */
    public function index(){
        $estimates = Estimate::showAllEstimate();
        return view('Estimate.estimate') -> with('estimates',$estimates);
    }

    /**
     * Download file to my computer
     */
    public function downloadEstimate($id){

        $estimate = Estimate::showEstimateById($id);
        
        if( $estimate != ""){

            $fileName =  $estimate->name;

            $filePath = storage_path()."/app/download_upload/".$fileName;

            return response()->download($filePath);

        }
        
    }

    /**
     * show form add estimate
     */
    public function formAddEstimate(){
        return view('Estimate.add_estimate');
    }

    /**
     * Add Estimate and upload file to server
     * Upload to folder: public/download
     */
    public function addEstimate(Request $request){

        $request->validate([
            'file' => 'required|mimes:csv,xlsx,txt,xlx,xls,pdf|max:2048'
            ]);

        if (!$request->hasFile('file')) {

            return redirect('/file-upload')->with('success', 'Chưa chọn file cần upload');
        }

        $file = $request->file('file'); //get file

        //$filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME); get file name not include extension

        $request->file('file')->storeAs('download_upload',  $file->getClientOriginalName(),'local'); // set folder to upload and set file name: local:storage->app->download_upload | public: storage->app->public->download_upload

        // insert database
        DB::beginTransaction();
        try {
           $estimate = array(
               'name' => $file->getClientOriginalName(),
               'path' => ''           
            );
            Estimate::insert($estimate);
            DB::commit();
        }
        catch (Exception $e) {
            DB::rollback();
        }
        
        return redirect('/estimates')->with('success', 'Thêm Estimate thành công');
    }

    /**
     * 
     */
}



