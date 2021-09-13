<?php
namespace App\Http\Controllers;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Customer;
use App\Models\Project;
use App\Models\Estimate;
use App\Models\Item;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
// use PhpOffice\PhpSpreadsheet\Spreadsheet;
// use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
// use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use DateTime;
class InvoiceController extends Controller
{
    /**
     * Get all invoice
     */
    public function index(){
        $invoices = Invoice::showAllInvoice();
        return view('Invoice.invoice', ['invoices' => $invoices]);
     }
     /**
      * show project to choose for invoice
      */
     public function getProject(){
        $projects = Project::all();
        return view('Invoice.get_project',['projects' => $projects]);
     }
     /**
     * Show invoice detail by invoice_id
     */
     public function invoiceDetail($invoice_id){
        $customerInvoice = Invoice::showCustomerInvoice($invoice_id);
        $invoiceCart = Invoice::showInvoiceCart($invoice_id);
        return view('Invoice.invoice_detail',
        [
            'customerInvoice' => $customerInvoice,
            'invoiceCart' => $invoiceCart
        ]);
     }
     /**
      * get table customer, project, estimate, item
      * show in to form add invoice
     */
     public function formAddInvoice(Request $request){
         $customers = Customer::showAllCustomer();
         //$projects = Project::getAllProject();
         //$estimates = Estimate::showAllEstimate();
         //$orders = Order::showAllOrder();

         return view('Invoice.add_invoice',[
            'customers' => $customers
            //'projects' => $projects,
            //'orders' => $orders,
            //'estimates' => $estimates
        ]);
     }
     /**
      * get table customer, project, estimate, item
      * show in to form add invoice
     */
    public function addInvoice(Request $request){
        $invoiceID = 0; // 0: insert fail. >0: insert success
        DB::beginTransaction();
        try {
           $invoice = array(
               'create_date' => $request->ngaytao,
               'status' => false,
               'total' => (float)str_replace(",", "", $request->total_amount),
               'expire_date' => $request->hantt,
               'estimate_id' => $request->estimate,
               'order_id' => $request->order,
               'customer_id' => $request->khachhang,
               'tax_rate' => $request->tax_rate
              );
            //get id of invoice inserted
            $invoiceID = Invoice::insertInvoice($invoice);
            if($invoiceID > 0){ //if insert success -> insert invoice detail
                $arrayPrice = $request->price;
                $arrayQty = $request->qty;
                $arrayProduct= $request->product;
                $arrayTotal = $request->total;
                $count = count($arrayPrice);
                $arrayItemId = array();
                for ($i = 0; $i < $count; $i++) {
                    $item = array(                   
                        'name'  =>  $arrayProduct[$i],
                        'price'  => (float)str_replace(",", "", $arrayPrice[$i]),
                        'project_id'  => $request->project
                       );                                         
                    $itemId = Item::insertItem($item);
                    array_push($arrayItemId,$itemId);
                }
          
            
               
                // foreach ($array_id as $id => $key) { //convert to array with key and value
                //     $result[$key] = array(
                //         'price'  => (float)str_replace(",", "", $array_price[$id]),
                //         'qty' => $array_qty[$id],
                //         'total'  => (float)str_replace(",", "", $array_total[$id])
                //     );
                // }
                // foreach($result as $key => $value){
                //     if($value['qty'] > 0){
                //         $invoiceDetail = array(
                //             'invoice_id' => $invoiceID,
                //             'item_id' => $key,
                //             'quantity' => $value['qty'],
                //             'price' => $value['price'],
                //             'amount' => $value['total']
                //         );
                //         InvoiceItem::insertInvoiceItem($invoiceDetail);
                //     }
                // }
            }
            DB::commit();
        }catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->withErrors(['success' => $e->getMessage()]);
        }
        return redirect('invoices')->with('success', 'Thêm hoá đơn thành công!');
    }

    //  public function getItemByProjectId($id){
    //     $items = Item::showItemByProjectId($id);
    //     return view('admin.add_invoice', ['items' => $items]);
    //  }
    /**
     * find customer info by id customer->useless because spec change
     */
    // public function getCustomer(Request $request){
    //     $customers = Customer::showCustomerById($request->id);
    //     return response()->json(['success'=>true,'info' => $customers]);
    // }
    public function getInfoCustomer(Request $request){
        //$customers = Customer::showCustomerById($request->id);
        $projects = Project::getProjectByCustomerId($request->id);       
        return response()->json([
            'success'=>true,
            'projects' => $projects
        ]);
    }

     /**
      * update status invoice
      */
    public function updateStatus(Request $request){
        if($request->stat == 0){
            $status = 1;
        }else if($request->stat == 1){
            $status = 0;
        }
        DB::beginTransaction();
        try {
            Invoice::updateStatus($request->invId,$status);
            DB::commit();
        }
        catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->withErrors(['success' => $e->getMessage()]);
        }
        return redirect('invoices');
    }

     /**
      * export file excel: import exist file excel-> map data
      */
     public function exportInvoice($invoice_id){

        $customerInvoice = Invoice::showCustomerInvoice($invoice_id);
        $cart = Invoice::showInvoiceCart($invoice_id);

        $templateFile = resource_path('assets/templates/invoice.xlsx');

         //get invoie detail from model
        $count = $cart->count();
        $date = new DateTime($customerInvoice->create_date);
        $spreadsheet = IOFactory::load($templateFile);

        //set active sheet Invoice
        $spreadsheet->setActiveSheetIndex(0);
        $worksheet = $spreadsheet->getActiveSheet();

        $projectName = $cart[0]->project_name;
        $itemName = $cart[0]->item_name;
        $worksheet->getCell('E9')->setValue($date->format('Y/m/d'));
        $worksheet->getCell('E10')->setValue($customerInvoice->customer_name . ' 御中');
        $worksheet->getCell('E11')->setValue($customerInvoice->customer_address);
        $worksheet->getCell('E12')->setValue($customerInvoice->customer_phone);
        $worksheet->getCell('H12')->setValue($customerInvoice->customer_fax);
        $worksheet->setCellValueExplicit('E13',$customerInvoice->estimate_id, DataType::TYPE_STRING);
        $worksheet->getStyle('E13')->getNumberFormat()->setFormatCode("00000000000");
        $worksheet->getCell('E15')->setValue($projectName);

        //table content list item
        if($count > 1){
            $worksheet->insertNewRowBefore(20, $count-1); //insert $count-1 row before row 20
        }

        $rows = 18;
        $subTotal = 0;
        $tax = $customerInvoice->tax_rate;

        for ( $i = 0; $i < $count; $i++ ) {
            $subTotal += $cart[$i]->amount;
            $worksheet->mergeCells('D'.$rows.':G'.$rows);
            $worksheet->setCellValue('C' . $rows, $i+1);
            $worksheet->setCellValue('D' . $rows, $cart[$i]->item_name);
            $worksheet->setCellValue('H' . $rows, ($cart[$i]->quantity).'式');
            $worksheet->setCellValue('I' . $rows, $cart[$i]->price);
            $worksheet->setCellValue('J' . $rows, $cart[$i]->amount);
            $rows++;
        }

        $worksheet->setCellValue('C' . $rows, $i+1);
        $subTax = $subTotal * $tax / 100;
        $worksheet->setCellValue('J'.(21 + $count),$subTotal);
        $worksheet->setCellValue('C'.(22 + $count), '消費税(' . $tax . '%)');
        $worksheet->setCellValue('J'.(22 + $count),$subTax);
        $worksheet->setCellValue('J'.(23 + $count),($subTax+$subTotal));
        $expireDate = new DateTime($customerInvoice->expire_date);
        $worksheet->getCell('E'.(29 + $count))->setValue($expireDate->format('Y/m/d'));

        $excelFileName = 'Invoice_' . $projectName . '_' . $itemName . '_' . $date->format('Ymd') . '.xlsx';
        $savedFilePath = config('global.invoice_files_path') . $excelFileName;
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save($savedFilePath);

        return response()->download($savedFilePath);
     }

     /**
     *
     */
    //show edit invoice
    public function formEditInvoice($invoice_id){
        $customerInvoice = Invoice::showCustomerInvoice($invoice_id);
        $invoiceCart = Invoice::showInvoiceCart($invoice_id);

        $customers = Customer::showAllCustomer();
        $projects = Project::getAllProject();
        $estimates = Estimate::showAllEstimate();
        $items = Item::showItemByProjectId($invoiceCart[0]->project_id);

        return view('Invoice.edit_invoice',[
           'customers' => $customers,
           'projects' => $projects,
           'estimates' => $estimates,
           'items' => $items,
           'customerInvoice' => $customerInvoice,
           'invoiceCart' => $invoiceCart
        ]);
    }
    /**
     * Update info invoice
     */
    public function editInvoice(Request $request){
        $res = 0; //if == 0 -> update fail
        DB::beginTransaction();
        try {
            $invoice = array(
                'create_date' => $request->ngaytao,
                'total' => (float)str_replace(",", "", $request->total_amount),
                'expire_date' => $request->hantt,
                'estimate_id' => $request->estimate,
                'customer_id' => $request->khachhang
            );
            $res = Invoice::updateInvoice($request->invoice_id, $invoice);
            if($res > 0){
                $array_price = $request->price;
                $array_qty = $request->qty;
                $array_id = $request->id;
                $array_total = $request->total;
                foreach ($array_id as $id => $key) { //convert to array with key and value
                    $result[$key] = array(
                        'price'  => (float)str_replace(",", "", $array_price[$id]),
                        'qty' => $array_qty[$id],
                        'total'  => (float)str_replace(",", "", $array_total[$id])
                    );
                }
                foreach($result as $key => $value){
                    if($value['qty'] > 0){
                        $content = array(
                            'quantity' => $value['qty'],
                            'price' => $value['price'],
                            'amount' => $value['total']
                        );
                        InvoiceItem::updateInvoiceItem($request->invoice_id,$key,$content);
                    }
                }
            }
            DB::commit();
        }
        catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->withErrors(['success' => $e->getMessage()]);
        }
        return redirect('invoices')->with('success', 'Sửa hoá đơn thành công!');
    }
    /**
     * Delete invoice
     */
    public function deleteInvoice($id){

        DB::beginTransaction();

        try {
            Invoice::deleteInvoice($id);
            InvoiceItem::deleteInvoiceItem($id);
            DB::commit();
        }
        catch (Exception $e) {

            DB::rollback();
            return redirect()->back()->withErrors(['success' => $e->getMessage()]);
        }
       return redirect('invoices')->with('success', 'Xoá hóa đơn thành công!');
    }
     /**
     * ajax get item when change select project
     */
    public function getItem(Request $request){
        $project_id = $request->id;
        // $items = Item::showItemByProjectId($project_id);
        // $data = '';
        // foreach($items as $item){
        //     $price_item = number_format($item->price);
        //     $data.='<tr>';
        //         $data.='<input type="hidden" name="id[]" value="'.$item->id.'">';
        //         $data.='<td class="text-left">'.$item->name.'</td>';
        //         $data.= '<td><input type="text" name="price[]" class="form-control price number-right" value="'.$price_item.'" readonly/></td>';
        //         $data.= '<td><input type="number" id="" name="qty[]" value="1" class="form-control qty number-right" min="0" max="500"/></td>';
        //         $data.='<td><input type="text" name="total[]"  id="" value="'.$price_item.'" class="form-control total number-right" style=" margin-left: 40px;" readonly/></td>';
        //     $data.='</tr>';
        // }
              
        $estimates = Estimate::getEstimateByProjectId($project_id);
        $orders = Order::getOrderByProjectId($project_id);
        return response()->json([
            //'data' => $data,
            'estimates' => $estimates,
            'orders' => $orders
        ]);
    }

}
?>
