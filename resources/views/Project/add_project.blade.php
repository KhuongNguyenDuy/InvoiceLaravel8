@extends('layout')
@section('title', 'Home page')
@section('title-detail', 'Thêm dự án')
@section('library')

@endsection

@section('content')
<div style="font-size:0.9rem; margin: auto; width:80%;border: solid 1px gray;padding:15px;">
    <!--form submit request add project-->
    <form action='/add-project' method="post" name="form_add_project" onsubmit="return validateForm()">
         @csrf
         <!--row ten project name-->
        <div class="form-row">
            <div class="form-group col-md-2">
                <b><label for="project_name" >Tên dự án : (*)</label></b>
            </div>
            <div class="form-group col-md-10">
                <input type="text" id="project_name" name="project_name" placeholder="" class="input-xlarge form-control" required oninvalid="this.setCustomValidity('Hãy nhập tên dự án')" oninput="this.setCustomValidity('')">
                <p class="help-block" id="mess_name"><i>Tên dự án không quá 225 ký tự</i></p>                        
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-8"></div>
            <div class="form-group col-md-4">
                <button type="submit"  class="btn btn-success">Thêm dự án</button>
            </div>
        </div>
    </form>
    <!--#end form submit add project-->
</div>
<script>
function validateForm() {
    let name = document.forms["form_add_project"]["project_name"].value;
    var flag = 0;
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