@extends('layout')
@section('title', 'Home page')
@section('title-detail', 'Danh sách Item')
@section('content')
<div class="add-button">
	<button type="button" class="btn btn-success btn-lg"> + Add Item</button>
</div>

	<table class="table table-hover table-bordered table-border-margin"> 
		<thead>
			<tr style="background-color: black;">
				<th class="col-sm-1 display-text" >STT</th>
				<th class="col-sm-4 display-text">Item Name</th>
				<th class="col-sm-1 display-text">Price</th>
				<th class="col-sm-4 display-text">Thuộc Project</th>
				<th class="col-sm-1 display-text">Edit</th>
				<th class="col-sm-1 display-text">Delete</th>
			</tr>
		</thead>
		<tbody>	
		@foreach($items as $item)
			<tr>
				<td class="col-sm-1 display-text">{{($items->currentPage()-1) * $items->perPage() + $loop->index + 1 }}</td>
				<td class="col-sm-4">{{$item->name}}</td>
				<td><?php echo number_format($item->price)?></td>
				<td class="col-sm-4">{{$item->project_id}}</td>
				<td class="col-sm-1 display-text"><a href="{{'/item'.$item->id.'/edit'}}" class="fas fa-edit" style="color:seagreen;"></a></td>
				<td class="col-sm-1 display-text"><a href="{{'/item'.$item->id.'/delete'}}" class="fas fa-trash" style="color:red;"></a></td>
			</tr>
		@endforeach
  		</tbody> 
	</table>
	{{ $items -> links() }}
@endsection


	