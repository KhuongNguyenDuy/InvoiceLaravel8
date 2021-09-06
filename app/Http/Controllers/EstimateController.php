<?php

namespace App\Http\Controllers;
use App\Models\Estimate;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Order;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;


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
            $filePath = storage_path()."/app/estimations/".$fileName;
            return response()->download($filePath);
        }        
    }

    /**
     * show form add estimate
     */
    public function formAddEstimate(){
        $projects = Project::getAllProject();
        return view('Estimate.add_estimate')->with('projects',$projects);
    }

    /**
     * Add Estimate and upload file to server
     * Upload to folder: storage
     */
    public function addEstimate(Request $request){
        //validate
        Validator::make($request->all(), [
            'estFile' => 'required|mimes:csv,xlsx,txt,xlx,xls,pdf|max:2048'
        ])->validateWithBag('estimate');
        //check have file
        if (!$request->hasFile('estFile')) {
            return redirect('/estimates')->with('success', 'Chưa chọn file cần upload');
        }
        //get path to save
        $path = config('global.estimate_files_path');
        //get file
        $file = $request->file('estFile');
        //$filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME); //get file name not include extension
        $request->file('estFile')->storeAs($path,  $file->getClientOriginalName(),'local'); // set folder to upload and set file name: local:storage->app->download_upload | public: storage->app->public->download_upload
        // insert database
        DB::beginTransaction();
        try {
           $estimate = array(
            'no' => $request->est_no,
            'name' => $file->getClientOriginalName(),
            'path' => '' ,
            'project_id' => $request->project      
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
     * show form edit estimate
     */
    public function formEditEstimate($id){
        $estimates = Estimate::showEstimateById($id); 
        return view('Estimate.edit_estimate')->with('estimates',$estimates);
    }

     /**
     * edit Estimate and change up file to server
     * Upload to folder: storage
     */
    public function editEstimate(Request $request){
        $request->validate([
            'file' => 'required|mimes:csv,xlsx,txt,xlx,xls,pdf|max:2048'
        ]);
        if (!$request->hasFile('file')) {
            return redirect('/estimates')->with('success', 'Chưa chọn file cần upload');
        }
        
        Storage::delete('/download_upload/'.$request->estimate_name);
        $file = $request->file('file'); 
        $request->file('file')->storeAs('download_upload',  $file->getClientOriginalName(),'local'); // set folder to upload and set file name: local:storage->app->download_upload | public: storage->app->public->download_upload
 
         // insert database
        DB::beginTransaction();
        try {
           $estimate = array(
               'name' => $file->getClientOriginalName(),
               'path' => ''           
            );
            Estimate::editEstimate($request->estimate_id,$estimate);
            DB::commit();
        }
        catch (Exception $e) {
            DB::rollback();
        }
         
        return redirect('/estimates')->with('success', 'Sửa Estimate thành công');

    }
    
    /**
     * Delete Estimate
     */
    public function deleteEstimate($id){
        
        $fileName = Estimate::showEstimateById($id);
        DB::beginTransaction();
        try {
            Storage::delete('/download_upload/'.$fileName->name);
            Estimate::deleteEstimate($id);
            DB::commit();
        }
        catch (Exception $e) {

            DB::rollback();
        }
       return redirect('/estimates')->with('success', 'Xoá Estimate thành công!'); 
    }


    



}



