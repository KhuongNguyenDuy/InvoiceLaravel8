@extends('layout')
@section('title-detail', 'Danh sách Item')
@section('content')
<div class="add-button">
	<button type="button" onclick="window.location.href='/form-add-item'" class="btn btn-success btn-lg">+ Thêm </button>
</div>
<!--check session add invoice if success-->
@if (session('success'))
<div class="alert alert-success" style="margin:10px;">
	{{ session('success') }}
</div>
@endif
<table class="table table-hover table-bordered table-border-margin"> 
		<thead>
			<tr style="background-color: black;">
			<th class="display-text" >STT</th>
			<th class="display-text">Item Name</th>
			<th class="display-text">Price</th>
			<th class="display-text">Thuộc Project</th>
			<th class="display-text">Edit</th>
			<th class="display-text">Delete</th>
		</tr>
	</thead>
	<tbody>	
		@foreach($items as $item)
			<tr>
				<td class="display-text">{{($items->currentPage()-1) * $items->perPage() + $loop->index + 1 }}</td>
			<td class="">{{$item->name}}</td>
			<td class="text-right"><?php echo number_format($item->price)?></td>
			<td class="">{{$item->project_id}}</td>
			<td class="display-text"><a href="{{'/form-edit-item/'.$item->id}}" class="fas fa-edit" style="color:seagreen;font-size:17px;"></a></td>
			<td class="display-text"><a href="{{'/delete-item/'.$item->id}}" class="fas fa-trash delete_item" style="color:red;font-size:17px;"></a></td>
		</tr>
	@endforeach
	</tbody> 
</table>

<script type="text/javascript">
$('.delete_item').on('click', function () {
	    return confirm('Khi xóa Item, thông tin hóa đơn chứa Item này cũng sẽ bị xóa?');
});
</script>
{{ $items -> links() }}
@endsection


	