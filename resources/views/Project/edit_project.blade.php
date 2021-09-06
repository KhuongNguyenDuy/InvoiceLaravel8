@extends('layout')
@section('title-detail', 'Sửa thông tin dự án')
@section('library')

@endsection

@section('content')
<div style="font-size:0.9rem; margin: auto; width:80%;border: solid 1px gray;padding:15px;">
    <!--form submit request add invoice-->
    <form action='/edit-project' method="post" name="form_edit_project" onsubmit="return validateForm()">
         @csrf
         <div class="form-row">
              <div class="form-group col-md-2">
                  <b><label for="project_name" >Khách hàng : (*)</label></b>
              </div>
              <div class="form-group col-md-10">
                <select class="form-control" id="customer" name="customer" placeholder="Chọn khách hàng..." required oninvalid="this.setCustomValidity('Xin vui lòng chọn khách hàng')" oninput="this.setCustomValidity('')">
                    @foreach($customers as $c)
                        @if ($projects->customer_id == $c->id)
                            <option value="{{$c->id}}" selected>{{$c->name}}</option>
                        @else
                            <option value="{{$c->id}}" >{{$c->name}}</option>
                        @endif
                    @endforeach
                </select>                      
              </div>
        </div>
        <!--row ten project name-->
        <div class="form-row">
            <div class="form-group col-md-2">
                <b><label for="project_name" >Tên dự án : (*)</label></b>
            </div>
            <div class="form-group col-md-10">
                <input type="hidden" name="project_id" value="{{$projects->id}}">
                <input type="text" value="{{$projects->name}}" id="project_name" name="project_name" placeholder="" class="input-xlarge form-control" required oninvalid="this.setCustomValidity('Hãy nhập tên dự án')" oninput="this.setCustomValidity('')">
                <p class="help-block" id="mess_name"><i>Tên dự án không quá 225 ký tự</i></p>                        
            </div>
        </div>
         <!--row ten project name-->
        <div class="form-row">
            <div class="form-group col-md-8"></div>
            <div class="form-group col-md-4">
                <button type="submit"  class="btn btn-success">Cập nhật</button>
            </div>
        </div>
    </form>
    <!--#end form submit add project-->
</div>
<script>
function validateForm() {
    let name = document.forms["form_edit_project"]["project_name"].value;
    var flag = 0;
    //check project name
    if(name.length > 255){
        document.getElementById("mess_name").style.color = "red";
        flag = 1;
    }else{
        document.getElementById("mess_name").style.color = "#858796";
    }
    if(flag == 1){
        return false;
    }
}
</script>
@endsection