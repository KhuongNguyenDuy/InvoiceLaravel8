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
				<th class="display-text" >STT</th>
				<th class="display-text">Project Name</th>
				<th class="display-text">Customer</th>
				<th class="display-text">Edit</th>
				<th class="display-text">Delete</th>
			</tr>
		</thead>
		<tbody>	
		@foreach($projects as $project)
			<tr>
				<td class="display-text">{{($projects->currentPage()-1) * $projects->perPage() + $loop->index + 1 }}</td>
				<td>{{$project->name}}</td>
				<td>{{$project->customer_name}}</td>
				<td class="display-text"><a href="{{'/form-edit-project/'.$project->id}}" class="fas fa-edit" style="color:seagreen;font-size:16px;"></a></td>
				<td class="display-text"><a href="{{'/delete-project/'.$project->id}}" class="fas fa-trash delete_project" style="color:red;font-size:16px;"></a></td>
			</tr>
		@endforeach
  		</tbody> 
	</table>
	<script type="text/javascript">
	$('.delete_project').on('click', function () {
			return confirm('Khi xóa Project, tấc cả Item thuộc project này, và hóa đơn liên quan cũng sẽ bị xóa?');
	});
	</script>
	{{ $projects -> links() }}
@endsection
