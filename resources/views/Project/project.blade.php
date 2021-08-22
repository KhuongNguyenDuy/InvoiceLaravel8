@extends('layout')
@section('title', 'Home page')
@section('title-detail', 'Danh sách Project')
@section('content')
	<div class="add-button">
		<button type="button" class="btn btn-success btn-lg"> + Add Project</button>
	</div>
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
				<td class="col-sm-1 display-text"><a href="{{'/project'.$project->id.'/edit'}}" class="fas fa-edit" style="color:seagreen;"></a></td>
				<td class="col-sm-1 display-text"><a href="{{'/project'.$project->id.'/delete'}}" class="fas fa-trash" style="color:red;"></a></td>
			</tr>
		@endforeach
  		</tbody> 
	</table>
	{{ $projects -> links() }}
@endsection
