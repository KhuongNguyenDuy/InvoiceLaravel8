@extends('layout')
@section('title-detail', 'Thêm Estimate - Order')
@section('content')


<div style="font-size:0.9rem; margin: auto; width:95%;">
    <div class="form-row">
        <div class="form-group col-md-6" style="border: solid 1px gray;padding:15px;margin-left:-10px;">
            <h4 style="text-align:center"><b>ESTIMATE</b></h4>
            <p></p>
            <!--form submit add estimate-->
            <form action="{{ URL::to('add-estimate') }}" method="post" name="form_add_estimate" onsubmit="return validateFormEstimate()" enctype="multipart/form-data">
                @csrf
                <!--row project-->
                <div class="form-row">
                    <div class="form-group col-md-2">
                        <b><label for="project_name" >Project : (*)</label></b>
                    </div>
                    <div class="form-group col-md-10">
                        <select class="form-control" id="project" name="project" required oninvalid="this.setCustomValidity('Xin vui lòng chọn project')" oninput="this.setCustomValidity('')">
                          <option value="" selected disabled>Chọn project..</option>
                          @foreach($projects as $p)
                            <option value="{{$p->id}}">{{$p->name}}</option>
                          @endforeach
                        </select>
                    </div>
                </div>
                <!--row Estimate No-->
                <div class="form-row">
                    <div class="form-group col-md-2">
                        <b><label for="est_no" >Est.No : (*)</label></b>
                    </div>
                    <div class="form-group col-md-7">
                        <input type="text" id="est_no" name="est_no" placeholder="" class="input-xlarge form-control" required oninvalid="this.setCustomValidity('Hãy nhập estimate')" oninput="this.setCustomValidity('')">
                        <p class="help-block" id="mess_est_no"><i>Estimate không quá 20 ký tự</i></p>
                    </div>
                </div>
                <!--row File-->
                <div class="form-row">
                    <div class="form-group col-md-2">
                        <b><label for="file_name">File : (*)</label></b>
                    </div>
                    <div class="form-group col-md-7">
                        @if($errors->estimate->any())
                            <div class="alert alert-danger alert-dismissible fade show" style="margin-top:40px;">
                                <ul>
                                    @foreach($errors->estimate->all() as $error)
                                    <li> {{ $error }} </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <input type="file" name="estFile" class="custom-file-input" id="est-select-file" >
                        <label class="custom-file-label" for="est-select-file">Chọn file estimate...</label>
                    </div>
                </div>
                <!--row button add submit-->
                <div class="form-row">
                    <div class="form-group col-md-3"></div>
                    <div class="form-group col-md-9">
                        <button type="submit"  class="btn btn-success">Thêm Estimate</button>
                    </div>
                </div>
            </form><!--#end form submit add estimate-->
        </div>
        <!--FORM ORDER-->
        <div class="form-group col-md-6" style="border: solid 1px gray;padding:15px;margin-left:10px;">
            <h4 style="text-align:center"><b>ORDER</b></h4>
            <p></p>
            <!--form submit add order-->
            <form action="{{ URL::to('add-order') }}" method="post" name="form_add_order" onsubmit="return validateFormOrder()" enctype="multipart/form-data">
                @csrf
                <!--row project-->
                <div class="form-row">
                    <div class="form-group col-md-2">
                        <b><label for="project_name" >Project : (*)</label></b>
                    </div>
                    <div class="form-group col-md-10">
                        <select class="form-control" id="project" name="project" required oninvalid="this.setCustomValidity('Xin vui lòng chọn project')" oninput="this.setCustomValidity('')">
                            <option value="" selected disabled>Chọn project..</option>
                            @foreach($projects as $p)
                            <option value="{{$p->id}}">{{$p->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <!--row Estimate No-->
                <div class="form-row">
                    <div class="form-group col-md-2">
                        <b><label for="order_no" >Order.No : (*)</label></b>
                    </div>
                    <div class="form-group col-md-7">
                        <input type="text" id="order_no" name="order_no" placeholder="" class="input-xlarge form-control" required oninvalid="this.setCustomValidity('Hãy nhập order')" oninput="this.setCustomValidity('')">
                        <p class="help-block" id="mess_order_no"><i>Order không quá 20 ký tự</i></p>
                    </div>
                </div>
                <!--row order Amount -->
                <div class="form-row">
                    <div class="form-group col-md-2">
                        <b><label for="order_no" >Amout : (*)</label></b>
                    </div>
                    <div class="form-group col-md-7">
                        <input type="text" id="amount" name="amount" placeholder="" class="input-xlarge form-control" required oninvalid="this.setCustomValidity('Hãy nhập số tiền')" oninput="this.setCustomValidity('')">
                        <p class="help-block" id="mess_amount"><i>Nhap vao amount</i></p>
                    </div>
                </div>
                <!--row File-->
                <div class="form-row">
                    <div class="form-group col-md-2">
                        <b><label for="file_name">File : (*)</label></b>
                    </div>
                    <div class="form-group col-md-7">
                        @if($errors->order->any())
                            <div class="alert alert-danger alert-dismissible fade show" style="margin-top:40px;">
                                <ul>
                                    @foreach($errors->order->all() as $error)
                                    <li> {{ $error }} </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <input type="file" name="orderFile" class="custom-file-input" id="order-select-file" >
                        <label class="custom-file-label" for="order-select-file">Chọn file order...</label>
                    </div>
                </div>
                <!--row button add submit-->
                <div class="form-row">
                    <div class="form-group col-md-4"></div>
                    <div class="form-group col-md-8">
                        <button type="submit"  class="btn btn-success">Thêm Order</button>
                    </div>
                </div>
            </form><!--#end form submit add order-->
        </div>
    </div>
</div>

<script>
//get name of file->show
$('#est-select-file').on('change',function(){
   fileName = $(this).val().replace('C:\\fakepath\\', " ");
   $(this).next('.custom-file-label').html(fileName);
})

$('#order-select-file').on('change',function(){
   fileName = $(this).val().replace('C:\\fakepath\\', " ");
   $(this).next('.custom-file-label').html(fileName);
})

function validateFormEstimate() {
    let estNo = document.forms["form_add_estimate"]["est_no"].value;
    var flag = 0;
    if(estNo.length > 20){
        document.getElementById("mess_est_no").style.color = "red";
        flag = 1;
    }else{
        document.getElementById("mess_est_no").style.color = "#858796";
    }
    if(flag == 1){
        return false;
    }
}
function validateFormOrder() {
    let orderNo = document.forms["form_add_order"]["order_no"].value;
    var flag = 0;
    if(orderNo.length > 20){
        document.getElementById("mess_order_no").style.color = "red";
        flag = 1;
    }else{
        document.getElementById("mess_order_no").style.color = "#858796";
    }
    if(flag == 1){
        return false;
    }
}
</script>

@endsection
