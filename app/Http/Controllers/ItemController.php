<?php

namespace App\Http\Controllers;
use App\Models\Item;
use App\Models\Project;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index(){
     $items = DB::table('items')
          ->join('projects', 'items.project_id', '=', 'projects.id')
          ->select('items.id','items.name','items.price','projects.name as project_id')
          ->orderBy('items.id', 'ASC')
          ->Paginate(10);
        // print_r($items);
    return view('Item.item', ['items' => $items]);
    
 }
 public function findItemByProjectID($id){
     $items = Project::find($id)->items;
     return view('Item.item', ['items' => $items]);
     
  }
}


