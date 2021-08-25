@extends('layout')
@section('title-detail', 'Thêm Item')
@section('library')

@endsection

@section('content')
<div style="font-size:0.9rem; margin: auto; width:80%;border: solid 1px gray;padding:15px;">
    <!--form submit request add item-->
    <form action='/add-item' method="post" name="form_add_item" onsubmit="return validateForm()">
         @csrf
         <!--row name Item-->
        <div class="form-row">
            <div class="form-group col-md-2">
                <b><label for="item_name" >Tên Item : (*)</label></b>
            </div>
            <div class="form-group col-md-10">
                <input type="text" id="item_name" name="item_name" placeholder="" class="input-xlarge form-control" required oninvalid="this.setCustomValidity('Hãy nhập tên Item')" oninput="this.setCustomValidity('')">
                <p class="help-block" id="mess_name"><i>Tên item không quá 225 ký tự</i></p>                        
            </div>
        </div>
        <!--row price item -->
        <div class="form-row">
            <div class="form-group col-md-2">
                <b><label for="item_price" >Giá Item : (*)</label></b>
            </div>
            <div class="form-group col-md-7">          
                <input type="number" id="item_price" name="item_price" placeholder="" class="input-xlarge form-control" min="0" required oninvalid="this.setCustomValidity('Hãy nhập giá từ 0 trở lên')" oninput="this.setCustomValidity('')">
                <p class="help-block" id="mess_item_price"><i>Giá không quá 20 ký số</i></p>                        
            </div>
        </div>
         <!--row project-->
        <div class="form-row">
            <div class="form-group col-md-2">
                <b><label for="item_project">Thuộc Project : (*)</label></b>
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
 

         <!--row button add-->
        <div class="form-row">
            <div class="form-group col-md-8"></div>
            <div class="form-group col-md-4">
                <button type="submit"  class="btn btn-success">Thêm Item</button>
            </div>
        </div>
    </form>
    <!--#end form submit add item-->
</div>
<script>
function validateForm() {

    let name = document.forms["form_add_item"]["item_name"].value;
    let price = document.forms["form_add_item"]["item_price"].value;

    var flag = 0;

    if(name.length > 255){
        document.getElementById("mess_name").style.color = "red";
        flag = 1;
    }else{
        document.getElementById("mess_name").style.color = "#858796";
    }
    if(price.length > 20){
        document.getElementById("mess_item_price").style.color = "red";
        flag = 1;
    }else{
        document.getElementById("mess_item_price").style.color = "#858796";
    }
    
    if(flag == 1){
        return false;
    }
}
</script>
@endsection
