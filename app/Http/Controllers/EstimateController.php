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
        return view('Estimate.estimate_list')->with('estimates',$estimates);
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
        $fileName = $file->getClientOriginalName();
        // insert database
        DB::beginTransaction();
        $result = Estimate::checkFileExist($fileName);
        if($result){
            return redirect('/estimates')->with('fail', 'Thêm Estimate thất bại! File đã tồn tại.');
        }
        $estimate = new Estimate();
        try {
            $estimate->no = $request->est_no;
            $estimate->name = $fileName;
            $estimate->path = '';
            $estimate->project_id = $request->project;
            $estimate->save();
            DB::commit();
        }
        catch (Exception $e){
            DB::rollback();
        }
        return redirect('/estimates')->with('success', 'Thêm Estimate thành công');
    }

    /**
     * show form edit estimate
     */
    public function formEditEstimate($id){
        $estimate = Estimate::showEstimateById($id);
        $projects = Project::getAllProject();
        return view('Estimate.edit_estimate',[
            'projects' => $projects,
            'estimate' => $estimate
        ]);
    }

     /**
     * edit Estimate and change up file to server
     * Upload to folder: storage
     */
    public function editEstimate(Request $request){
        $estimate = Estimate::find($request->estimate_id);
        $file = $request->file('estFile');
        //if do not change file
        if(!$file){
            DB::beginTransaction();           
            try {
                $estimate->update([
                    'no' => $request->est_no,
                    'name' => $request->estimate_name,
                    'path' => '',
                    'project_id' => $request->project
                ]);
                DB::commit();
            }
            catch (Exception $e){
                DB::rollback();
            }
            return redirect('/estimates')->with('success', 'Sửa Estimate thành công');
        }else{
            Validator::make($request->all(), [
                'estFile' => 'mimes:csv,xlsx,txt,xlx,xls,pdf|max:2048'
            ])->validateWithBag('estimate');
            $fileOrigin = $request->estimate_name;
            $newFile = $file->getClientOriginalName();
            $result = Estimate::checkFileExist($newFile);
            $checkFileName = strcmp($fileOrigin,$newFile);
            //if file input not duplication
            if($checkFileName == 0 || $result == null){
                DB::beginTransaction();
                try {
                    //get path to save
                    $path = config('global.estimate_files_path');
                    Storage::delete('/'.$path.'/'.$request->estimate_name);
                    $request->file('estFile')->storeAs($path, $file->getClientOriginalName(),'local'); // set folder to upload and set file name: local:storage->app->download_upload | public: storage->app->public->download_upload                    
                    // insert database
                    $estimate->update([
                        'no' => $request->est_no,
                        'name' => $newFile,
                        'path' => '',
                        'project_id' => $request->project
                    ]);
                    DB::commit();
                }
                catch (Exception $e) {
                    DB::rollback();
                }
                return redirect('/estimates')->with('success', 'Sửa Estimate thành công');
            }else{
                return redirect('/estimates')->with('fail', 'Sửa Estimate thất bại! File đã tồn tại.');
            }
        }
    }

    /**
     * Delete Estimate
     */
    public function deleteEstimate($id){
        //get path to save
        $path = config('global.estimate_files_path');
        $fileName = Estimate::showEstimateById($id);
        DB::beginTransaction();
        try {
            Storage::delete('/'.$path.'/'.$fileName->name);
            Estimate::deleteEstimate($id);
            DB::commit();
        }
        catch (Exception $e) {
            DB::rollback();
        }
       return redirect('/estimates')->with('success', 'Xoá Estimate thành công!');
    }

     /**
     * Download file to my computer
     */
    public function downloadEstimate($id){
        $estimate = Estimate::showEstimateById($id);
        if( $estimate != ""){
            $path = config('global.estimate_files_path');
            $fileName =  $estimate->name;
            $filePath = storage_path()."/app/".$path."/".$fileName;
            return response()->download($filePath);
        }
    }
}
