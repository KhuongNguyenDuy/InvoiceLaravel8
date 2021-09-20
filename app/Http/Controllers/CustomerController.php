<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{

    /**
     * Get list customer
     */
    public function index(){
        $customers = Customer::showAllCustomer();
        return view('Customer.customer_list')->with('customers',$customers);
    }

    /**
     * Show form input info customer to add
     */
    public function formAddCustomer(){
        return view('Customer.add_customer');
    }

    /**
     * function insert customer
     */
    public function addCustomer(Request $request){
        DB::beginTransaction();
        $customer = new Customer();
        try {
            $customer->name = $request->customer_name;
            $customer->abbreviate = $request->abbreviate_name;
            $customer->address = $request->address;
            $customer->phone = $request->phone_number;
            $customer->fax = $request->fax_number;
            $customer->director_name = $request->director_name;
            $customer->establish_date = $request->establish_date;
            $customer->capital = $request->capital;
            $customer->employee_num = $request->employee_num;
            $customer->save();
            DB::commit();
        }
        catch (Exception $e) {
            DB::rollback();
        }
        return redirect('customers')->with('success', 'Thêm khách hàng thành công!');
    }
    /**
     *
     */
    //show edit customer
    public function formEditCustomer($id){
        $customers = Customer::showCustomerById($id);
        return view('Customer.edit_customer')->with('customers',$customers);
    }


    /**
     * Update info customer
     */
    public function editCustomer(Request $request){       
        DB::beginTransaction();
        $customer = Customer::find($request->customer_id);
        try {
            $customer->update([
                'name' => $request->customer_name,
                'address' => $request->address,
                'abbreviate' => $request->abbreviate_name,
                'phone' => $request->phone_number,
                'fax' => $request->fax_number,
                'director_name' => $request->director_name,
                'establish_date' => $request->establish_date,
                'capital' => $request->capital,
                'employee_num' => $request->employee_num
            ]);
            DB::commit();
        }
        catch (Exception $e) {
            DB::rollback();
        }
        return redirect('customers')->with('success', 'Cập nhật khách hàng thành công!');
    }


    /**
     * Delete customer
     */
    public function deleteCustomer($id){

        DB::beginTransaction();
        try {
           Customer::destroy($id);
           DB::commit();
        }
        catch (Exception $e) {
            DB::rollback();
        }
       return redirect('customers')->with('success', 'Xoá khách hàng thành công!');
    }

}
