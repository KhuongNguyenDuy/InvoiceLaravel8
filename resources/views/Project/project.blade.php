@extends('layout')
@section('title-detail', 'Danh sách Project')
@section('content')
	<div class="add-button">
		<button type="button" onclick="window.location.href='/form-add-project'" class="btn btn-success btn-lg">+ Thêm </button>
	</div>
	@if (session('success'))
		<div class="alert alert-success" style="margin:10px;">
			{{ session('success') }}
		</div>
	@endif
	<table class="table table-hover table-bordered table-border-margin"> 
		<thead>
			<tr style="background-color: black;">
				<th class="col-sm-1 display-text" >STT</th>
				<th class="col-sm-9 display-text">Project Name</th>
				<th class="col-sm-1 display-text">Edit</th>
				<th class="col-sm-1 display-text">Delete</th>
			</tr>
		</thead>
		<tbody>	
		@foreach($projects as $project)
			<tr>
				<td class="col-sm-1 display-text">{{($projects->currentPage()-1) * $projects->perPage() + $loop->index + 1 }}</td>
				<td class="col-sm-9">{{$project->name}}</td>
				<td class="display-text"><a href="{{'/form-edit-project/'.$project->id}}" class="fas fa-edit" style="color:seagreen;font-size:20px;"></a></td>
				<td class="display-text"><a href="{{'/delete-project/'.$project->id}}" class="fas fa-trash delete_project" style="color:red;font-size:20px;"></a></td>
			</tr>
		@endforeach
  		</tbody> 
	</table>
	{{ $projects -> links() }}
@endsection
