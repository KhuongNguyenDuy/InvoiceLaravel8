@extends('layout')
@section('title-detail', 'Sửa Estimate')
@section('content')

<div style="font-size:0.9rem; margin: auto; width:95%;">
    <div class="form-row">
        <div class="form-group col-md-10" style="border: solid 1px gray;padding:15px;margin:auto;">
            <h4 style="text-align:center"><b>ESTIMATE</b></h4>
            <p></p>
            <!--form submit add estimate-->
            <form action="{{ URL::to('edit-estimate') }}" method="post" name="form_edit_estimate" onsubmit="return validateFormEstimate()" enctype="multipart/form-data">
                @csrf
                <!--row project-->
                <div class="form-row">
                    <div class="form-group col-md-2">
                        <b><label for="project_name" >Project : (*)</label></b>
                    </div>
                    <div class="form-group col-md-10">
                        <select class="form-control" id="project" name="project" required oninvalid="this.setCustomValidity('Xin vui lòng chọn project')" oninput="this.setCustomValidity('')">
                          @foreach($projects as $p)
                            @if ( $estimate->project_id == $p->id)
                              <option value="{{$estimate->project_id}}" selected>{{$p->name}}</option>
                            @else
                              <option value="{{$p->id}}">{{$p->name}}</option>
                            @endif
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
                        <input type="text" value="{{ $estimate->no }}" id="est_no" name="est_no" placeholder="" class="input-xlarge form-control" required oninvalid="this.setCustomValidity('Hãy nhập estimate')" oninput="this.setCustomValidity('')">
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
                        <input type="hidden" name="estimate_id" value="{{$estimate->id}}">
                        <input type="hidden" name="estimate_name" value="{{$estimate->name}}">
                        <input type="file" name="estFile" class="custom-file-input" id="chooseFile" >
                        <label class="custom-file-label" for="chooseFile">{{ $estimate->name }}</label>
                    </div>
                </div>
                <!--row button add submit-->
                <div class="form-row">
                    <div class="form-group col-md-2"></div>
                    <div class="form-group col-md-10">
                        <button type="submit"  class="btn btn-success">Cập nhật</button>
                    </div>
                </div>
            </form><!--#end form submit add estimate-->
        </div>
    </div>
</div>
<script>
 $('#chooseFile').on('change',function(){
    fileName = $(this).val().replace('C:\\fakepath\\', " ");
     $(this).next('.custom-file-label').html(fileName);
 })
 function validateFormEstimate() {
    let estNo = document.forms["form_edit_estimate"]["est_no"].value;
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
</script>

@endsection
