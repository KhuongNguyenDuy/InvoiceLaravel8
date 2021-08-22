<?php
namespace App\Http\Controllers;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Customer;
use App\Models\Project;
use App\Models\Estimate;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\IOFactory;
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
         $projects = Project::showProjectById($request->project);
         $estimates = Estimate::showAllEstimate();
         $items = Item::showItemByProjectId($request->project);
        
         return view('Invoice.add_invoice',[
            'customers' => $customers,
            'projects' => $projects,
            'items' => $items,
            'estimates' => $estimates
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
                'customer_id' => $request->khachhang
            );
            //get id of invoice inserted
            $invoiceID = Invoice::insert($invoice);
            DB::commit();
            }
            catch (Exception $e) {
                DB::rollback();
            }
            if($invoiceID > 0){ //if insert success -> insert invoice detail
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
                        $invoiceDetail = array(
                            'invoice_id' => $invoiceID,
                            'item_id' => $key,
                            'quantity' => $value['qty'],
                            'price' => $value['price'],
                            'amount' => $value['total']
                        );
                        InvoiceItem::insert($invoiceDetail);
                    }
                }
                return redirect('invoices')->with('success', 'Thêm hoá đơn thành công!'); //return redirect()->back()->with('success', 'Thêm hoá đơn thành công!');
            }
        }

    //  public function getItemByProjectId($id){
    //     $items = Item::showItemByProjectId($id);
    //     return view('admin.add_invoice', ['items' => $items]);
    //  }
    /**
     * find customer info by id customer
     */
    public function getCustomer(Request $request){
        $customers = Customer::showCustomerById($request->id);
        return response()->json(['success'=>true,'info' => $customers]);
     }
  
     /**
      * 
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
        }
        return redirect('invoices');
    }
     
     /**
      * export file excel: import exist file excel-> map data
      */
     public function exportInvoice($invoice_id,$type){
        
        $customerInvoice = Invoice::showCustomerInvoice($invoice_id);
        $cart = Invoice::showInvoiceCart($invoice_id);

         //get invoie detail from model
        $count = $cart->count(); 
        $date = new DateTime($customerInvoice->create_date);
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load('TrainingProject.xlsx');

        //remove worksheet Input
        $sheetIndex = $spreadsheet->getIndex(
            $spreadsheet->getSheetByName('Input')
        );
        $spreadsheet->removeSheetByIndex($sheetIndex);

        //set active sheet Invoice
        $spreadsheet->setActiveSheetIndexByName('Invoice');
        $worksheet = $spreadsheet->getActiveSheet();
        $worksheet->getCell('E9')->setValue($date->format('Y/m/d'));
        $worksheet->getCell('E10')->setValue($customerInvoice->customer_name);
        $worksheet->getCell('E11')->setValue($customerInvoice->customer_address);
        $worksheet->getCell('E12')->setValue($customerInvoice->customer_phone);
        $worksheet->getCell('H12')->setValue($customerInvoice->customer_fax);
        $worksheet->setCellValueExplicit('E13',$customerInvoice->estimate_name,\PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
        $worksheet->getStyle('E13')->getNumberFormat()->setFormatCode("00000000000");
        $worksheet->getCell('E15')->setValue($cart[0]->project_name);

        //table content list item
        if($count > 1){
            $worksheet->insertNewRowBefore(20, $count-1); //insert $count-1 row before row 20
        }

        $rows = 19;
        $subTotal = 0;
        $tax = config('global.tax'); //call file global- get tax

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
        $worksheet->setCellValue('J'.(22+$count),$subTotal);
        $worksheet->setCellValue('J'.(23+$count),$subTax);
        $worksheet->setCellValue('J'.(24+$count),($subTax+$subTotal));        
        $expireDate = new DateTime($customerInvoice->expire_date);
        $worksheet->getCell('E'.(30+$count))->setValue($expireDate->format('Y/m/d'));
        $worksheet->setCellValueExplicit('E'.(34+$count),"21410410265442",\PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);

        $fileName =  $customerInvoice->estimate_name;
        if($type == 'xlsx'){
            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
            /* Here there will be some code where you create $spreadsheet */
            // redirect output to client browser
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="'.$fileName.'.xlsx"');
            header('Cache-Control: max-age=0');
            
        }else if($type == 'xls'){
            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xls');
            /* Here there will be some code where you create $spreadsheet */
            // redirect output to client browser
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="'.$fileName.'.xls"');
            header('Cache-Control: max-age=0');
        }
        $writer->save('php://output');
        exit;
     }

     /**
     * 
     */
    //show edit invoice
    public function formEditInvoice($id){
        
    } 
    /**
     * Update info customer
     */
    public function editInvoice(Request $request){
    } 
    /**
     * Delete customer
     */
    public function deleteInvoice($id){

    }
}
?>
