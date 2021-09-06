<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Customer;
use DB;

class ProjectController extends Controller
{
      /**
     * show project
     */
    public function index(){
        $projects = Project::showAllProject();
        return view('Project.project') -> with('projects',$projects);        
    }

    public function show(){
        $projects = Project::all();
        return view('Project.get_project',['projects' => $projects]);
    }
       /**
     * Show form input info project to add
     */
    public function formAddProject(){
        $customers = Customer::showAllCustomer();
        return view('Project.add_project')->with('customers',$customers);
    } 
    /**
     * function insert project
     */
    public function addProject(Request $request){
        DB::beginTransaction();
        try {
           $project = array(
               'name' => $request->project_name,
               'customer_id' => $request->customer               
        );
           Project::insertProject($project);
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
        $customers = Customer::showAllCustomer();
        return view('Project.edit_project',[
            'projects' => $projects,
            'customers' => $customers
        ]);       
    }
    /**
     * Update info project
     */
    public function editProject(Request $request){
        DB::beginTransaction();
        try {
           $project = array(
               'name' => $request->project_name,
               'customer_id' => $request->customer  
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

}
