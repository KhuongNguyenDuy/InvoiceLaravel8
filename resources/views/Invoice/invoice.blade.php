@extends('layout')
@section('title', 'Home page')
@section('title-detail', 'Danh sách Invoice')
@section('content')
<div class="add-button">
	<!--<button type="button" class="btn btn-success btn-lg"> <a href="{{URL::to('/get-project')}}">+ Add Invoice</a></button>-->
	<button type="button" onclick="window.location.href='/get-project'" class="btn btn-success btn-lg">+ Add Invoice</button>
</div>
	<!--check session add invoice if success-->
	@if (session('success'))
		<div class="alert alert-success" style="margin:10px;">
			{{ session('success') }}
		</div>
	@endif
	<!--table show list info invoice-->
	<table class="table table-hover table-bordered table-border-margin"> 
		<thead>
			<tr style="background-color: black;">
				<th class="text-mid">STT</th>
				<th class="text-left">Create Date</th>
				<th class="text-left">Customer</th>
				<th class="text-left">Address</th>				
				<th class="text-left">Estimate</th>   
				<th class="text-left">Expire Date</th>             
				<th class="number-right">Total</th>
				<th class="text-mid">Status</th>
                <th class="text-mid">Detail</th>				
			</tr>
		</thead>
		<tbody>	
		@foreach($invoices as $invoice)
            @if($invoice->status == 0)
                <?php $status = "Chưa thanh toán"; $color = "btn-danger"?>
            @else
                <?php $status = " Đã thanh toán "; $color = "btn-success"?>
            @endif
			<tr>
				<td class="text-mid">{{($invoices->currentPage()-1) * $invoices->perPage() + $loop->index + 1 }}</td>
				<td class="text-left"><?php echo date_format(new DateTime($invoice->create_date),'Y/m/d');  ?></td>
				<td class="text-left">{{$invoice->customer_name}}</td>
				<td class="text-left">{{$invoice->customer_address}}</td>				
				<td class="text-left">{{$invoice->estimate_name}}</td>
				<td class="text-left"><?php echo date_format(new DateTime($invoice->expire_date),'Y/m/d');?></td>
				<td class="number-right"><?php echo number_format($invoice->total)?></td>
                <td class="text-mid">
					<form action="{{URL::to('/update-status')}}" method="POST">
						@csrf
						<input type="hidden" id="stat" name="stat" value="{{$invoice->status}}">
						<input type="hidden" id="invId" name="invId" value="{{$invoice->id}}">
						<input type="submit" value="{{$status}}" class="btn {{$color}} btn-sm">
                    </form>
				</td>				                
                <td class="text-mid"><a href="{{URL::to('/invoice-detail/'.$invoice->id)}}" style="color:seagreen;">Detail</a></td>
			</tr>
			<!-- last day of next month
			<?php
				$date = new DateTime($invoice->expire_date);
				$date->modify('last day of next month');
				echo $date->format('Y/m/d');
			?> -->
		@endforeach
  		</tbody> 
	</table>
	{{ $invoices -> links() }}
@endsection

