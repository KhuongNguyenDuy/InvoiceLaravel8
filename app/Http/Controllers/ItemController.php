<?php

namespace App\Http\Controllers;
use App\Models\Item;
use App\Models\Project;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ItemController extends Controller
{

   /**
   * * all item
   * 
  */
  public function index(){

    $items = Item::showAllItem();
    return view('Item.item', ['items' => $items]);
  }

  /**
   * * find item by project id
   * 
  */
 public function findItemByProjectID($id){
   $items = Project::find($id)->items;
   return view('Item.item', ['items' => $items]);
     
  }

  /**
   * * Show form input info item
   * 
  */
  public function formAddItem(){

    $projects = Project::getAllProject();
    return view('Item.add_item',['projects' => $projects]);
  }
   /**
   * function insert customer
   */
  public function addItem(Request $request){

     DB::beginTransaction();
     try {
        $item = array(
            'name' => $request->item_name,
            'price' => $request->item_price,
            'project_id' => $request->project,
           );
         Item::insertItem($item);
         DB::commit();
    }
    catch (Exception $e) {
       DB::rollback();
       return redirect()->back()->withErrors(['success' => $e->getMessage()]);
    }
    return redirect('items')->with('success', 'Thêm Item thành công!'); 
  }


  /**
     * 
     */
    //show edit item
  public function formEditItem($id){
      $items = Item::showItemById($id);
      $projects = Project::getAllProject();
      return view('Item.edit_item',[
        'projects' => $projects,
        'items' => $items
     ]);
  } 


  /**
   * Update info item
   */
  public function editItem(Request $request){
      DB::beginTransaction();
      try {

        $item = array(
          'name' => $request->item_name,
          'price' => $request->item_price,
          'project_id' => $request->project,
         );

         Item::editItem($request->item_id,$item);
         DB::commit();
     }
     catch (Exception $e) {
          DB::rollback();
          return redirect()->back()->withErrors(['success' => $e->getMessage()]);
     }
     return redirect('items')->with('success', 'Cập nhật item thành công!'); 
  } 

  
  /**
   * Delete item
   */
  public function deleteItem($id){
      
      DB::beginTransaction();
      try {
         Item::deleteItem($id);
         DB::commit();
      }
      catch (Exception $e) {

          DB::rollback();
          return redirect()->back()->withErrors(['success' => $e->getMessage()]);
      }
     return redirect('items')->with('success', 'Xoá item thành công!'); 
  } 


}
