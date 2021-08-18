@extends('layout')
@section('title', 'Home page')
@section('title-detail', 'Danh sách khách hàng')
@section('content')
<div class="add-button">
	<!--<button type="button" class="btn btn-success btn-lg"> + Add Customer</button>-->
	<button type="button" onclick="window.location.href='/form-add-customer'" class="btn btn-success btn-lg">+ Thêm </button>
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
			<th class="display-text">Customer Name</th>
			<th class="display-text">Abbreviate</th>
			<th class="display-text">Address</th>
			<th class="display-text">Phone</th>
            <th class="display-text">Fax</th>
			<th class="display-text">Edit</th>
			<th class="display-text">Delete</th>
		</tr>
	</thead>
	<tbody>	
		@foreach($customers as $customer)
		<tr>
			<td>{{($customers->currentPage()-1) * $customers->perPage() + $loop->index + 1 }}</td>
			<td>{{$customer->name}}</td>
			<td>{{$customer->abbreviate}}</td>
			<td>{{$customer->address}}</td>
            <td>{{$customer->phone}}</td>
			<td>{{$customer->fax}}</td>
			<td class="display-text"><a href="{{'/form-edit-customer/'.$customer->id}}" class="fas fa-edit" style="color:seagreen;font-size:20px;"></a></td>
			<td class="display-text"><a href="{{'/delete-customer/'.$customer->id}}" class="fas fa-trash delete_customer" style="color:red;font-size:20px;"></a></td>
		</tr>
		@endforeach
	</tbody> 
</table>
<script type="text/javascript">
    $('.delete_customer').on('click', function () {
        return confirm('Bạn có muốn xoá mục đã chọn không?');
    });
</script>
	{{ $customers -> links() }}
@endsection



	