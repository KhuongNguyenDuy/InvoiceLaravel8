@extends('layout')
@section('title-detail', 'Danh sách Order')
@section('content')
<div class="add-button">
	<button type="button" onclick="window.location.href='/form-add-order'" class="btn btn-success btn-lg">+ Thêm </button>
</div>
@if ($message = Session::get('success'))
<div class="alert alert-success">
    <strong>{{ $message }}</strong>
</div>
@endif
@if ($message = Session::get('fail'))
<div class="alert alert-danger" style="color:red;">
    <strong>{{ $message }}</strong>
</div>
@endif
	<table class="table table-hover table-bordered table-border-margin">
		<thead>
			<tr style="background-color: black;">
				<th class="display-text">STT</th>
				<th class="display-text">Order number</th>
                <th class="display-text">File</th>
				<th class="display-text">Project</th>
				<th class="display-text">Amount（円）</th>
				<th class="display-text">Edit</th>
				<th class="display-text">Delete</th>
			</tr>
		</thead>
        <?php $totalAmount = 0; ?>
		<tbody>
		@foreach($orders as $order)
			<tr>
				<td class="display-text">{{($orders->currentPage()-1) * $orders->perPage() + $loop->index + 1 }}</td>
				<td >{{$order->no}}</td>
                <td><a href="{{URL::to('/download-order/'.$order->id)}}">{{$order->name}}</a></td>
                <td>{{$order->project_name}}</a></td>
                <td class="text-right">{{ number_format($order->amount) }}</a></td>
				<td class="display-text"><a href="{{'/form-edit-order/'.$order->id}}"><span class="fas fa-edit" style="color:seagreen;font-size:16px;"></span></a></td>
				<td class="display-text"><a href="{{'/delete-order/'.$order->id}}" class="fas fa-trash delete_order" style="color:red;font-size:16px;"></a></td>
			</tr>
            <?php $totalAmount += $order->amount; ?>
		@endforeach
        <tr>
            <td colspan="4"></td>
            <td class="text-right">{{ number_format($totalAmount) }}</td>
            <td colspan="2"></td>
        </tr>
  		</tbody>
	</table>
	<script type="text/javascript">
        $('.delete_order').on('click', function () {
            return confirm('Bạn có muốn xóa mục đã chọn?');
        });
	</script>
	{{ $orders -> links() }}
@endsection
