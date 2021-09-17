@extends('layout')
@section('title-detail', 'Chi tiết hoá đơn')
@section('content')
<div class="container mt-5">
    <div class="d-flex justify-content-center row">
        <div class="col-md-8">
            <div class="p-3 bg-white rounded">
                <!--row title invoice-->
                <div class="row">
                    <div class="col-md-8">
                        <h1 class="text-uppercase">Hoá Đơn</h1>
                    </div>
                    <div class="col-md-4 text-right mt-3">
                        <h4 class="text-danger mb-0">VAIX CO., LTD</h4><span>Tel: +843-3384-6868</span>
                    </div>
                </div>
                 <!--row info invoice-->
                <div class="row">
                    <div class="billed"><span class="font-weight-bold text-uppercase">Ngày tạo : </span><span class="ml-1"><?php echo date_format(new DateTime($customerInvoice->create_date),'Y/m/d');?></span></div>
                </div>
                <div class="row">
                    <div class="billed"><span class="font-weight-bold text-uppercase">Khách hàng : </span><span class="ml-1">{{$customerInvoice->customer_name}}</span></div>
                </div> 
                <div class="row">
                    <div class="billed"><span class="font-weight-bold text-uppercase">Địa chỉ : </span><span class="ml-1">{{$customerInvoice->customer_address}}</span></div>
                </div> 
                <div class="row" style="margin-left:-25px;">
                    <div class="col-md-7">
                        <div class="billed"><span class="font-weight-bold text-uppercase">Số điện thoại : </span><span class="ml-1">{{$customerInvoice->customer_phone}}</span></div>
                    </div>
                    <div class="col-md-5">
                        <div class="billed"><span class="font-weight-bold text-uppercase">Fax :</span><span class="ml-1"> {{$customerInvoice->customer_fax}}</span></div>
                    </div>
                </div> 
                <div class="row" style="margin-left:-25px;">
                    <div class="col-md-7">
                        <div class="billed"><span class="font-weight-bold text-uppercase">Estimate No :</span><span class="ml-1">{{$customerInvoice->estimate_no}}</span></div>
                    </div>
                    <div class="col-md-5">
                        <div class="billed"><span class="font-weight-bold text-uppercase">Order No :</span><span class="ml-1"> {{$orderNo}}</span></div>
                    </div>
                </div> 
                <div class="row">
                    <div class="billed"><span class="font-weight-bold text-uppercase">Project :</span><span class="ml-1">{{$invoiceCart[0]->project_name}}</span></div>
                </div>   
                                          
                <!--invoice detail-->
                <div class="mt-3">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th class="text-mid">Số TT</th>
                                    <th class="text-left">Sản Phẩm</th>
                                    <th class="number-right">Số Lượng</th>
                                    <th class="number-right">Đơn Giá</th>
                                    <th class="number-right">Thành Tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                                $stt = 0;
                                $sub_total = 0;
                            ?>
                            @for ($y = 0; $y < $invoiceCart->count();$y++)
                                <?php $sub_total += $invoiceCart[$y]->amount; ?>
                                <tr>
                                    <td class="text-mid">{{++$stt}}</td>
                                    <td class="text-left">{{$invoiceCart[$y]->item_name}}</td>
                                    <td class="number-right">{{$invoiceCart[$y]->quantity}}</td>
                                    <td class="number-right"><?php echo number_format($invoiceCart[$y]->price); ?></td>
                                    <td class="number-right"><?php echo number_format($invoiceCart[$y]->amount); ?></td>
                                </tr>
                            @endfor
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td class="number-right"><b>Tổng :</b></td>
                                    <td class="number-right"><?php echo number_format($sub_total); ?></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td class="number-right"><b>Thuế tiêu thụ ({{$customerInvoice->tax_rate}}%) :</b></td>
                                    <td class="number-right"><?php echo number_format($sub_total * $customerInvoice->tax_rate / 100);?></td><!--#echo tax in file global-->
                                </tr>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td class="number-right"><b>Thành tiền :</b></td>
                                    <td class="number-right"><?php echo number_format($sub_total + ($sub_total * $customerInvoice->tax_rate / 100));?></td>
                                </tr>
                            </tbody>
                        </table>
                        <i><div class="billed"><span class="font-weight-bold">Hạn thanh toán:</span><span class="ml-1"><?php echo date_format(new DateTime($customerInvoice->expire_date),'Y/m/d');?></span></div></i>
                        <!--#Check invoice status-->
                        <?php $status = ""; ?>
                        @if($customerInvoice->status == 0)
                            <?php $status = "Chưa thanh toán"; ?>
                        @else
                            <?php $status = "Đã thanh toán"; ?>
                        @endif
                        <!--# end Check invoice status-->
                        <i><div class="billed"><span class="font-weight-bold">Tình trạng hoá đơn:</span><span class="ml-1">{{$status}}</span></div></i>
                    </div>
                </div>
                <p></p>
                <a href="{{URL::to('/export-invoice/'.$customerInvoice->id)}}" class="btn  btn-primary">Xuất file Excel</a>
            </div>
        </div>
    </div>
</div>

@endsection
