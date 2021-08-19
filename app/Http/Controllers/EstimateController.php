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
        $estimates = Estimate::paginate(5);
        //return view('admin.project',['projects' => $projects]);
        return view('Estimate.estimate') -> with('estimates',$estimates);
    }

    /**
     * Download file to my computer
     */
    public function downloadEstimate($id){
        $estimate = Estimate::showEstimateById($id);
        if( $estimate != ""){
            $estimateName =  $estimate->name;
            $filePath = public_path()."/download/".$estimateName;
            $headers = ['Content-Type: application/pdf'];
            $fileName = $estimateName;
            return response()->download($filePath, $fileName, $headers);
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
        $path = $file->move('download', $file->getClientOriginalName()); // set folder to upload and set file name

        // insert database
        DB::beginTransaction();
        try {
           $estimate = array(
               'name' => $file->getClientOriginalName(),
               'path' => "download/".$file->getClientOriginalName(),
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



