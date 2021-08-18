@extends('layout')
@section('title', 'Home page')
@section('title-detail', 'Thêm hoá đơn.....')
@section('library')
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker.css" rel="stylesheet">
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/js/bootstrap-datepicker.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js" integrity="sha256-+C0A5Ilqmu4QcSPxrlGpaZxJ04VjsRjKu+G82kl5UJk=" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.bootstrap3.min.css" integrity="sha256-ze/OEYGcFbPRmvCnrSeKbRTtjG4vGLHXgOqsyLFTRjg=" crossorigin="anonymous" />
@endsection

@section('content')
<?php
    //fuction get last day of next month
    $date = new DateTime(date('Y-m-d'));
    $date->modify('last day of next month');
?>
<div style="font-size: 0.8rem; margin:20px;">
    <!--form submit request add invoice-->
    <form action='/add-invoice' method="post" name="form_add_invoice" onsubmit="return validateForm()">
         @csrf
         <!--row create_at invoice -->
        <div class="form-row">
            <div class="form-group col-md-1">
                <label for="ngaytao">Ngày tạo :</label>
            </div>
            <div class="form-group col-md-5">
                <input class="date form-control" name="ngaytao" id="ngaytao" value="<?php echo date("Y/m/d"); ?>" type="text" >                          
            </div>
            <div class="form-group col-md-1">
                 <label for="hantt">Hạn thanh toán :</label>
            </div>
            <div class="form-group col-md-5">               
                <input class="date form-control" id="hantt" name="hantt" type="text" value="<?php echo $date->format('Y/m/d');?>" >             
                <!--#add datepicker-->
                <script type="text/javascript">
                    $('.date').datepicker({  
                    format: 'yyyy/mm/dd'
                    });  
                </script> 
                <!--#daetpicker end-->
            </div>
        </div>
        <!--row info phone customer-->
        <div class="form-row">
            <div class="form-group col-md-1">
                <label for="khachang">Khách hàng :</label>
            </div>
            <div class="form-group col-md-5">                
                <select class="form-control customer-option" id="select-state" name="khachhang" placeholder="Chọn khách hàng..." required oninvalid="this.setCustomValidity('Xin vui lòng chọn khách hàng')" oninput="this.setCustomValidity('')">
                <option value="">Chọn khách hàng..</option>
                    @foreach($customers as $c)
                        <option value="{{$c->id}}">{{$c->name}}</option>
                    @endforeach   
                </select>           
            </div>
            <div class="form-group col-md-1">
                <label for="sdt">Điện thoại :</label>
            </div>
            <div class="form-group col-md-5">
                <input class="date form-control" type="text" value="" id="sdt" name="txtsdt" disabled>             
            </div>
        </div>
        <!--row address, fax customwr-->
        <div class="form-row">
             <div class="form-group col-md-1">
                 <label for="diachi">Địa chỉ :</label>
            </div>
            <div class="form-group col-md-5">
                <input class="form-control" value="" type="text" id="diachi" disabled>                          
            </div>
            <div class="form-group col-md-1">
                <label for="fax">Fax:</label>
            </div>
            <div class="form-group col-md-5">
                <input class="form-control" type="text" value="" id="fax" disabled >             
            </div>
        </div>
        <!--row estimate id, project name-->
        <div class="form-row">
            <div class="form-group col-md-1">
                <label for="project">Project :</label>     
            </div>
            <div class="form-group col-md-5">
                <input type="text" class="form-control" value="{{$projects->name}}" disabled>
                <input type="hidden" id="projectId" name="projectId" value="{{$projects->id}}">
            </div>
            <div class="form-group col-md-1">
                <label for="estimate">Estimate :</label>     
            </div>
            <div class="form-group col-md-5">
                <select class="form-control" id="estimate" name="estimate" required oninvalid="this.setCustomValidity('Xin vui lòng chọn estimate')" oninput="this.setCustomValidity('')">
                <option value="" selected disabled>Chọn estimate..</option>
                    @foreach($estimates as $e)
                        <option value="{{$e->id}}">{{$e->name}}</option>
                    @endforeach   
                </select>                   
            </div>
        </div>
        <!--table show item-->
        <div style="width:85%; margin:auto;" >
            <table class="table table-striped table-bordered" id="tab_logic">
                <thead>
                    <tr>
                        <th class="text-center">Tên sản phẩm</th>
                        <th class="text-center">Đơn giá</th>
                        <th class="text-center">Số lượng</th>
                        <th class="text-center">Thành tiền</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $item)
                    <tr>
                        <input type="hidden" name="id[]" value="<?php echo $item->id;?>">
                        <td class="text-left">{{$item->name}}</td>                
                        <td><input type="text" name="price[]" class="form-control price number-right" value="<?php echo number_format($item->price);?>" readonly/></td>
                        <td><input type="number" id="" name="qty[]"  class="form-control qty number-right" min="0" max="500"/></td>
                        <td><input type="text" name="total[]"  id="" class="form-control total number-right" style=" margin-left: 40px;" readonly/></td>
                    </tr>
                    @endforeach
                    <tr>
                        <td></td>                
                        <td></td>
                        <td></td>
                        <td>
                            <table class="table table-bordered table-hover" id="tab_logic_total">
                                <tbody>
                                    <tr>
                                        <th class="number-right">Tổng phụ :</th>
                                        <td><input type="text" name='sub_total' placeholder='0' class="form-control number-right" id="sub_total" readonly/></td>
                                    </tr>
                                    <tr>
                                        <th class="number-right">Thuế (%) :</th>
                                        <td>
                                            <input type="text" readonly class="form-control number-right" id="tax" value=<?php echo config('global.tax');?>>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="number-right">Tổng thuế :</th>
                                        <td><input type="text" name='tax_amount' id="tax_amount" placeholder='0' class="form-control number-right" readonly/></td>
                                    </tr>
                                    <tr>
                                        <th class="number-right">Tổng cộng :</th>
                                        <td><input type="text" name='total_amount' id="total_amount" value="0" placeholder='0' class="form-control cart_total number-right" readonly/></td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>    
        <!--table show tax and total-->
        <!-- <div class="row clearfix" style="margin-top:20px">
            <div class="pull-right col-md-5"></div>
            <div class="pull-right col-md-6">
                <table class="table table-bordered table-hover" id="tab_logic_total">
                    <tbody>
                        <tr>
                            <th class="number-right">Tổng phụ</th>
                            <td><input type="text" name='sub_total' placeholder='0' class="form-control" id="sub_total" readonly/></td>
                        </tr>
                    <tr>
                        <th class="number-right">Thuế (%)</th>
                        <td><div class="input-group mb-2 mb-sm-0">
                            <input type="text" readonly class="form-control" id="tax" value=<?php echo config('global.tax');?>>
                        </div></td>
                    </tr>
                    <tr>
                        <th class="number-right">Tổng thuế</th>
                        <td><input type="text" name='tax_amount' id="tax_amount" placeholder='0' class="form-control" readonly/></td>
                    </tr>
                    <tr>
                        <th class="number-right">Tổng cộng</th>
                        <td><input type="text" name='total_amount' id="total_amount" value="0" placeholder='0' class="form-control cart_total" readonly/></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div> -->
        <!--row button sumit add invoice-->
        <div class="form-row">
            <div class="form-group col-md-8"></div>
            <div class="form-group col-md-4">
                <button type="submit"  class="btn btn-success">Thêm hoá đơn</button>
            </div>
        </div>
 </form>
 <!--#end form submit add invoice-->
</div>
<!--
	  Validation check emrty cart
 -->
<script>
function validateForm() {
    let cart = document.forms["form_add_invoice"]["total_amount"].value;
    var cart_total = parseInt(cart.replace(new RegExp(',', 'g'),"")); //convert string to int
    if (cart_total <= 0) { //if no choose item
         alert("Hãy chọn sản phẩm cho hoá đơn.");
         return false;
     }
    let create_at = document.forms["form_add_invoice"]["ngaytao"].value; //get datepicker create day
    let expire_at = document.forms["form_add_invoice"]["hantt"].value; //get datepicker expire
    var current_day = new Date(getCurrentDay());
    var create_day = new Date(create_at); 
    var expire_day = new Date(expire_at); 
    if(compareDate(current_day,create_day) == 1){ //if current day> create day
        alert("Ngày tạo nhỏ hơn ngày hiện tại");
        return false;
    }
    if(compareDate(create_day,expire_day) == 1){ //if create day > expire day
        alert("Ngày tạo lớn hơn hạn thanh toán");
        return false;
    }
}
//function compare two date
function compareDate(date1, date2) {
    if(date1 > date2){
        return 1;
    }
    else if(date1 < date2){
        return -1;
    }
    else{
        return 0;
    }
}
//function get current day
function getCurrentDay(){
    var today = new Date();
    var d = String(today.getDate()).padStart(2, '0');
    var m = String(today.getMonth() + 1).padStart(2, '0'); 
    var y = today.getFullYear();
    today = y + '/' + m + '/' + d;
    return today;
}
</script>
@endsection
