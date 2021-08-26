@extends('layout')
@section('title-detail', 'Sửa Estimate')
@section('content')
<style>
        .container {
            max-width: 500px;
        }
        dl, ol, ul {
            margin: 0;
            padding: 0;
            list-style: none;
        }
</style>
<div class="container mt-5">
        <form action="/edit-estimate" method="post" enctype="multipart/form-data">
          <h3 class="text-center mb-3">Chọn file Estimate</h3>
            @csrf
          	@if (count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                      <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
          	@endif

            <div class="custom-file">
                <input type="hidden" name="estimate_id" value="{{$estimates->id}}">
                <input type="hidden" name="estimate_name" value="{{$estimates->name}}">
                <input type="file" name="file" class="custom-file-input" id="chooseFile" >
                <label class="custom-file-label" for="chooseFile">{{ $estimates->name }}</label>
            </div>

            <button type="submit" name="submit" class="btn btn-primary btn-block mt-4">
                Change Estimate
            </button>
        </form>
    </div>
<script>
 $('#chooseFile').on('change',function(){
    fileName = $(this).val().replace('C:\\fakepath\\', " ");
     $(this).next('.custom-file-label').html(fileName);
 })
</script>
@endsection