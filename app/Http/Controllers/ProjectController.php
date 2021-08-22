<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Project;

class ProjectController extends Controller
{
    public function index(){
        $projects = Project::paginate(5);
        //return view('admin.project',['projects' => $projects]);
        return view('Project.project') -> with('projects',$projects);
    }
    public function destroy($id){
        $projects = Project::where('id',$id)->delete();
        return view('Project.project',['projects' => $projects]);
    }
    public function show(){
        $projects = Project::all();
        return view('Project.get_project',['projects' => $projects]);
    }

}
