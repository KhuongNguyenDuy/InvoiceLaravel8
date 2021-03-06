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
        return view('Invoice.invoice_list', ['invoices' => $invoices]);
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
        $orderNo = '';
        if($customerInvoice->order_id != null){
            $ord = Order::showOrderById($customerInvoice->order_id);
            $orderNo = $ord->no;
        }
        return view('Invoice.invoice_detail',
        [
            'customerInvoice' => $customerInvoice,
            'invoiceCart' => $invoiceCart,
            'orderNo' => $orderNo
        ]);
     }
     /**
      * get table customer, project, estimate, item
      * show in to form add invoice
     */
     public function formAddInvoice(Request $request){
         $customers = Customer::showAllCustomer();
         return view('Invoice.add_invoice',[
            'customers' => $customers
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
            $invoice = new Invoice();
            $invoice->create_date = $request->ngaytao;
            $invoice->status = false;
            $invoice->total = (float)str_replace(",", "", $request->total_amount);
            $invoice->expire_date = $request->hantt;
            $invoice->estimate_id = $request->estimate;
            $invoice->order_id = $request->order;
            $invoice->customer_id = $request->khachhang;
            $invoice->tax_rate = $request->tax_rate;
            $invoice->save();

            // After saving or creating a new model that uses auto-incrementing IDs, you may retrieve the ID by accessing the object's id attribute
            $invoiceID = $invoice->id;

            // insert invoice detail
            $arrayPrice = $request->price;
            $arrayQty = $request->qty;
            $arrayProduct= $request->product;
            $arrayTotal = $request->total;
            $count = count($arrayPrice);
            $arrayItemId = array();
            for ($i = 0; $i < $count; $i++) {
                $item = new Item();
                $item->name = $arrayProduct[$i];
                $item->price = (float)str_replace(",", "", $arrayPrice[$i]);
                $item->project_id = $request->project;
                $item->save();
                $itemId = $item->id;
                array_push($arrayItemId,$itemId);
            }
            for ($index = 0; $index < count($arrayItemId); $index++) {
                $invoiceDetail = array(
                    'invoice_id' => $invoiceID,
                    'item_id' => $arrayItemId[$index],
                    'quantity' => $arrayQty[$index],
                    'price' => (float)str_replace(",", "", $arrayPrice[$index]),
                    'amount' => (float)str_replace(",", "", $arrayTotal[$index])
                );
                InvoiceItem::insertInvoiceItem($invoiceDetail);
            }
            DB::commit();
        }catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->withErrors(['success' => $e->getMessage()]);
        }
        return redirect('invoices')->with('success', 'Th??m ho?? ????n th??nh c??ng!');
    }

    /**
     * find customer info by id customer->useless because spec change
     */
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
            $invoice = Invoice::find($request->invId);
            $invoice->update([
                'status' => $status
            ]);           
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
       $orderNo = '';
       if($customerInvoice->order_id != null){
           $ord = Order::showOrderById($customerInvoice->order_id);
           $orderNo = $ord->no;
       }

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
       $worksheet->getCell('E10')->setValue($customerInvoice->customer_name . ' ??????');
       $worksheet->getCell('E11')->setValue($customerInvoice->customer_address);
       $worksheet->getCell('E12')->setValue($customerInvoice->customer_phone);
       $worksheet->getCell('H12')->setValue($customerInvoice->customer_fax);
       $worksheet->setCellValueExplicit('E13',$customerInvoice->estimate_no, DataType::TYPE_STRING);
       $worksheet->getStyle('E13')->getNumberFormat()->setFormatCode("00000000000");
       $worksheet->setCellValueExplicit('H13',$orderNo, DataType::TYPE_STRING);
       $worksheet->getStyle('H13')->getNumberFormat()->setFormatCode("00000000000");
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
           $worksheet->setCellValue('H' . $rows, ($cart[$i]->quantity).'???');
           $worksheet->setCellValue('I' . $rows, $cart[$i]->price);
           $worksheet->setCellValue('J' . $rows, $cart[$i]->amount);
           $rows++;
       }

       $worksheet->setCellValue('C' . $rows, $i+1);
       $subTax = $subTotal * $tax / 100;
       $worksheet->setCellValue('J'.(21 + $count),$subTotal);
       $worksheet->setCellValue('C'.(22 + $count), '?????????(' . $tax . '%)');
       $worksheet->setCellValue('J'.(22 + $count),$subTax);
       $worksheet->setCellValue('J'.(23 + $count),($subTax+$subTotal));
       $expireDate = new DateTime($customerInvoice->expire_date);
       $worksheet->getCell('E'.(29 + $count))->setValue($expireDate->format('Y/m/d'));

       $excelFileName = 'Invoice_' . $projectName . '_' . $itemName . '_' . $date->format('Ymd') . '.xlsx';
       $savedFilePath = config('global.invoice_files_path') . $excelFileName;
       $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
       $writer->save($savedFilePath);

       // Save file path to DB
       Invoice::saveInvoiceFilePath($invoice_id, $savedFilePath);

       return response()->download($savedFilePath);
    }

    /**
     * Path to download invoice file
     * /download-invoice/{id}
     */
    public function downloadInvoice($invoiceId) {
        $invoice = Invoice::find($invoiceId);
        if(!$invoice) {
            abort(404);
        }

        $filePath = $invoice->file_path;
        if(!$filePath) {
            abort(404);
        }

        return response()->download($filePath);
    }

     /**
     *
     */
    //show edit invoice
    public function formEditInvoice($invoice_id){
        $customerInvoice = Invoice::showCustomerInvoice($invoice_id);
        $invoiceCart = Invoice::showInvoiceCart($invoice_id);

        $customers = Customer::showAllCustomer();
        $projects = Project::getProjectByCustomerId($customerInvoice->customer_id);
        $estimates = Estimate::getEstimateByProjectId($invoiceCart[0]->project_id);
        $orders = Order::getOrderByProjectId($invoiceCart[0]->project_id);
        $items = Item::showItemByProjectId($invoiceCart[0]->project_id);

        return view('Invoice.edit_invoice',[
           'customers' => $customers,
           'projects' => $projects,
           'estimates' => $estimates,
           'orders' => $orders,
           'items' => $items,
           'customerInvoice' => $customerInvoice,
           'invoiceCart' => $invoiceCart
        ]);
    }
    /**
     * Update info invoice
     */
    public function editInvoice(Request $request){
        DB::beginTransaction();
        $invoice = Invoice::find($request->invoice_id);
        try {
            $invoice->update([
                'create_date' => $request->ngaytao,
                'total' => (float)str_replace(",", "", $request->total_amount),
                'expire_date' => $request->hantt,
                'estimate_id' => $request->estimate,
                'order_id' => $request->order,
                'customer_id' => $request->khachhang,
                'tax_rate' => $request->tax_rate
            ]);
            $arrayPrice = $request->price;
            $arrayQty = $request->qty;
            $arrayItemId = $request->id;
            $arrayProduct= $request->product;
            $arrayTotal = $request->total;
            $count = count($arrayPrice);
            for ($i = 0; $i < $count; $i++) {
                $item = Item::find($arrayItemId[$i]);
                $item->update([
                    'name'  =>  $arrayProduct[$i],
                    'price'  => (float)str_replace(",", "", $arrayPrice[$i]),
                    'project_id'  => $request->project
                ]);
            }
            for ($index = 0; $index < count($arrayItemId); $index++) {
                $invoiceDetail = array(
                    'quantity' => $arrayQty[$index],
                    'price' => (float)str_replace(",", "", $arrayPrice[$index]),
                    'amount' => (float)str_replace(",", "", $arrayTotal[$index])
                );
                InvoiceItem::updateInvoiceItem($request->invoice_id,$arrayItemId[$index],$invoiceDetail);//update invoice item
            }
            DB::commit();
        }
        catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->withErrors(['success' => $e->getMessage()]);
        }
        return redirect('invoices')->with('success', 'S???a ho?? ????n th??nh c??ng!');
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
       return redirect('invoices')->with('success', 'Xo?? h??a ????n th??nh c??ng!');
    }
     /**
     * ajax get item when change select project
     */
    public function getItem(Request $request){
        $project_id = $request->id;
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
