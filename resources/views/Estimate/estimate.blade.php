@extends('layout')
@section('title', 'Home page')
@section('title-detail', 'Danh sách Estimate')
@section('content')
<div class="add-button">
	<button type="button" onclick="window.location.href='/form-add-estimate'" class="btn btn-success btn-lg">+ Thêm </button>
</div>
@if ($message = Session::get('success'))
<div class="alert alert-success">
    <strong>{{ $message }}</strong>
</div>
@endif
	<table class="table table-hover table-bordered table-border-margin"> 
		<thead>
			<tr style="background-color: black;">
				<th class="display-text">STT</th>
				<th class="display-text">Estimate</th>
                <th class="display-text">Path</th>
				<th class="display-text">Download</th>
				<th class="display-text">Edit</th>
				<th class="display-text">Delete</th>
			</tr>
		</thead>
		<tbody>	
		@foreach($estimates as $estimate)
			<tr>
				<td class="display-text">{{($estimates->currentPage()-1) * $estimates->perPage() + $loop->index + 1 }}</td>
				<td>{{$estimate->name}}</td>
                <td>{{$estimate->path}}</td>
				<td class="text-mid"><a href="{{URL::to('/download-estimate/'.$estimate->id)}}">Download</a></td>
				<td class="display-text"><a href="{{'/form-edit-estimate/'.$estimate->id}}" class="fas fa-edit" style="color:seagreen;font-size:20px;"></a></td>
				<td class="display-text"><a href="{{'/delete-estimate/'.$estimate->id}}" class="fas fa-trash delete_customer" style="color:red;font-size:20px;"></a></td>
			</tr>
		@endforeach
  		</tbody> 
	</table>
	{{ $estimates -> links() }}
@endsection
