@extends('layout')
@section('title', 'Home page')
@section('title-detail', 'Chọn Project..')
@section('library')
@endsection

@section('content')
<form action='/form-add-invoice' method="post" onsubmit="return(Validate());" name="myform">
@csrf
    <div class="form-row" style="margin-left:100px;">
        <div class="form-group col-md-1">
            <label for="project">Project:</label>     
        </div>
        <div class="form-group col-md-5">
            <select class="form-control" id="project" name="project"> <!--required-->
                <option value="" selected disabled>Chọn project..</option>
                @foreach($projects as $p)
                    <option value="{{$p->id}}">{{$p->name}}</option>
                @endforeach   
            </select>
            <p></p>
            <div id="name_error" style="color:red;"></div>                               
        </div>
    </div>
    <input style="margin:50px" type="submit" value="Tiếp theo" class="btn btn-success"/>
</form>
<script type="text/javascript">
      // Form validation
      function Validate(){
         if( document.myform.project.value == "" ){
           // alert( "Please provide your city!" );
            name_error.textContent = "Hãy chọn project.!"; 
            document.myform.project.focus() ;
            return false;
         }
         return( true );
      }
</script>

@endsection

