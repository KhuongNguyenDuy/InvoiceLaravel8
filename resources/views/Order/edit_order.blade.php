@extends('layout')
@section('title-detail', 'Sửa Order')
@section('content')

<div style="font-size:0.9rem; margin: auto; width:95%;">
    <div class="form-row">        
        <div class="form-group col-md-10" style="border: solid 1px gray;padding:15px;margin:auto;">
            <h4 style="text-align:center"><b>ORDER</b></h4>
            <p></p>
            <!--form submit add order-->
            <form action="{{ URL::to('edit-order') }}" method="post" name="form_edit_order" onsubmit="return validateFormOrder()" enctype="multipart/form-data">
                @csrf
                <!--row project-->
                <div class="form-row">
                    <div class="form-group col-md-2">
                        <b><label for="project_name" >Project : (*)</label></b>
                    </div>
                    <div class="form-group col-md-10">
                        <select class="form-control" id="project" name="project" required oninvalid="this.setCustomValidity('Xin vui lòng chọn project')" oninput="this.setCustomValidity('')">
                          @foreach($projects as $p)
                            @if ( $order->project_id == $p->id)
                              <option value="{{$order->project_id}}" selected>{{$p->name}}</option>
                            @else
                              <option value="{{$p->id}}">{{$p->name}}</option>
                            @endif       
                          @endforeach                          
                        </select>                       
                    </div>
                </div>
                <!--row order No-->
                <div class="form-row">
                    <div class="form-group col-md-2">
                        <b><label for="order_no" >Order.No : (*)</label></b>
                    </div>
                    <div class="form-group col-md-7">
                        <input type="text" value="{{ $order->no }}" id="order_no" name="order_no" placeholder="" class="input-xlarge form-control" required oninvalid="this.setCustomValidity('Hãy nhập order')" oninput="this.setCustomValidity('')">
                        <p class="help-block" id="mess_order_no"><i>Order không quá 20 ký tự</i></p>                        
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
                        <input type="hidden" name="order_id" value="{{$order->id}}">
                        <input type="hidden" name="order_name" value="{{$order->name}}">
                        <input type="file" name="orderFile" class="custom-file-input" id="chooseFile" >
                        <label class="custom-file-label" for="chooseFile">{{ $order->name }}</label>                  
                    </div>
                </div>  
                <!--row button add submit-->
                <div class="form-row">
                    <div class="form-group col-md-2"></div>
                    <div class="form-group col-md-10">
                        <button type="submit"  class="btn btn-success">Cập nhật</button>
                    </div>
                </div>
            </form><!--#end form submit add order-->
        </div>
    </div>
</div>
<script>
 $('#chooseFile').on('change',function(){
    fileName = $(this).val().replace('C:\\fakepath\\', " ");
     $(this).next('.custom-file-label').html(fileName);
 })
 function validateFormOrder() {
    let orderNo = document.forms["form_edit_order"]["mess_order_no"].value;
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