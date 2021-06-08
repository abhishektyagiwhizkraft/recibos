@extends('layouts.admin')
@section('content')

<div class="row justify-content-right">
    <button class="btn btn-xs btn-default" onclick="window.print()" id="print_btn"><i class="fas fa-print"></i></button>
</div>
<section class="receipt_heading">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <h1 class="receipt_text text-center">Recibo de Pago Provisiona</h1>
            </div>
        </div>
    </div>
</section>
<section class="recipt_main">
    <div class="container-fluid">

        <div class="reciptform row">  
            <div class="col-md-7 col-sm-6 top_section">
                <h1 class="receipt_text"><img src="{{ asset('images/logo.png') }}" class="img-fluid logo"></h1>
                <!--h1 class="receipt_text">RECEIPT</h1-->
            </div>
            <div class="col-md-5 col-sm-6 top_section">
                <h3 class="recipt_no under_line"><span>No.</span>#{{ $receipt->id }}</h3>
                <h3 class="recipt_no under_line"><span>{{ trans('cruds.receipt.invoiceno') }}.</span>#{{ $receipt->invoice_id }}</h3>

            </div>                      
            <div class="col-md-9 col-sm-9">
                <h3 class="date_field under_line"><span>{{ trans('cruds.receipt.date') }}</span>{{ date('D, d M Y', strtotime($receipt->created_at)) }}</h3>
                <h3 class="form_field under_line"><span>{{ trans('cruds.receipt.from') }}</span>{{ $receipt->invoice->from }}</h3>
            </div>
            <div class="col-md-3 col-sm-3">
                <div class="photo_field "></div>
            </div>
            <div class="col-md-12 col-sm-12">
                <h3 class="dollar_field under_line">${{ $receipt->invoice->amount }} - {{ucwords(\App\Invoices::numberToWord($receipt->invoice->amount))}}<span>DOLARES</span></h3>
            </div>
            <div class="col-md-6 col-sm-5 bottom_section">
                <div class="leftside_table">
                    <div class="table_field">
                        <span>ACCT</span>
                        <span></span>
                    </div>
                    <div class="table_field">
                        <span>{{ trans('cruds.receipt.paid') }}</span>
                        <span>${{ $receipt->invoice->amount }}</span>
                    </div>
                    <div class="table_field">
                        <span>{{ trans('cruds.receipt.due') }}</span>
                        <span></span>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-7 bottom_section">
                <div class="rightside_table">
                    <div class="from_to under_line ">
                        <!-- <span>FROM</span>ABCD
                        <span>TO</span>EFGH -->
                        <p>{{ trans('cruds.receipt.from') }}<span>{{ $receipt->invoice->from }}</span></p>
                        <p>TO<span></span></p>
                    </div>
                    <div class="received_by under_line">
                        <span>{{ trans('cruds.receipt.receivedby') }}</span>{{auth()->user()->name}}                        
                    </div>
                    <div class="signature under_line">
                        <span>{{ trans('cruds.receipt.signature') }}</span>...                  
                    </div>
                </div>
            </div>
            <div class="col-md-12 col-sm-12">
                <div class="footer_receipt">
                    <h4><strong>{{ Helper::receiptFooter()['city'] }},</strong> {{ Helper::receiptFooter()['address_line1'] }} - {{Helper::receiptFooter()['zip'] }}</h4>
                    <h4>{{(Helper::receiptFooter()['address_line2']) ? Helper::receiptFooter()['address_line2'].',' : ''}} <strong>Phone:</strong> {{ Helper::receiptFooter()['phone'] }}</h4>
                </div>
            </div>
        </div>
    </div>  
</section>
@endsection
@section('scripts')
@parent
<script>
   
</script>
@endsection
