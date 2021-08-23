<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Project;
use DB;

class ProjectController extends Controller
{
       /**
     * Show form input info project to add
     */
    public function formAddProject(){
        return view('Project.add_project');
    } 
    /**
     * function insert project
     */
    public function addProject(Request $request){
        DB::beginTransaction();
        try {
           $project = array(
               'name' => $request->project_name,
        );
           Project::insert($project);
           DB::commit();
       }
       catch (Exception $e) {
               DB::rollback();
       }
       return redirect('projects')->with('success', 'Thêm dự án thành công!'); 
    }
    /**
     * 
     */
    //show edit project
    public function formEditProject($id){
        $projects = Project::showProjectById($id);
        return view('Project.edit_project')->with('projects',$projects);; 
    }
    /**
     * Update info project
     */
    public function editProject(Request $request){
        DB::beginTransaction();
        try {
           $project = array(
               'name' => $request->project_name,
            );
           Project::edit($request->project_id,$project);
           DB::commit();
       }
       catch (Exception $e) {
               DB::rollback();
       }
       return redirect('projects')->with('success', 'Cập nhật dự án thành công!'); 
    }  
    /**
     * Delete project
     */
    public function deleteProject($id){
        DB::beginTransaction();
        try {
           Project::destroy($id);
           DB::commit();
        }
        catch (Exception $e) {
            DB::rollback();
            }
       return redirect('projects')->with('success', 'Xoá dự án thành công!'); 
    } 
    /**
     * show project
     */
    public function index(){
        $projects = Project::paginate(5);
        //return view('admin.project',['projects' => $projects]);
        return view('Project.project') -> with('projects',$projects);
 
        
    }

    public function show(){
        $projects = Project::all();
        return view('Project.get_project',['projects' => $projects]);
    }

}
