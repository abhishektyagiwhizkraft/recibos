@extends('layouts.admin')
@section('content')
@php
    \Carbon\Carbon::setLocale('es');
@endphp

<div class="row justify-content-right">
    <button class="btn btn-xs btn-default" onclick="window.print()" id="print_btn"><i class="fas fa-print"></i></button>
</div>

<section class="recipt_main">
    <div class="container-fluid">
        <div class="reciptform row">  
            <div class="col-md-12 receipt_heading">
                <h1 class="receipt_text text-center">Recibo de Pago Provisional</h1>
            </div>
            <div class="col-md-7 col-sm-6 top_section">
                <h1 class="receipt_text"><img src="{{ asset('images/logo.png') }}" class="img-fluid logo"></h1>
                <!--h1 class="receipt_text">RECEIPT</h1-->
            </div>
            <div class="col-md-5 col-sm-6 top_section">
                <h3 class="recipt_no under_line"><span>No.</span>{{ $receipt->id }}</h3>
                <h3 class="recipt_no under_line"><span>{{ trans('cruds.receipt.invoiceno') }}.</span>#{{ $receipt->invoice_id }}</h3>

            </div>                      
            <div class="col-md-12 col-sm-12">
                <h3 class="date_field under_line"><span>{{ trans('cruds.receipt.date') }}</span>{{ ucwords($receipt->created_at->translatedFormat('l, jS F Y')) }}</h3>
                <h3 class="form_field under_line"><span>{{ trans('cruds.receipt.from') }}</span>{{ $receipt->invoice->from }}</h3>
            </div>
            <!-- <div class="col-md-3 col-sm-3">
                <div class="photo_field "></div>
            </div> -->
            <div class="col-md-12 col-sm-12">
                <h3 class="dollar_field under_line">L {{ $receipt->total_payment }} - {{ucwords(\App\Invoices::numberToWord($receipt->total_payment))}}</h3>
            </div>
            <div class="col-md-12 col-sm-12">
                <h3 class="form_field under_line"><span>Por concepto de:</span>{{ ($receipt->concept) ? $receipt->concept : 'El pago de la factura' }}</h3>
            </div>
            <div class="col-md-6 col-sm-5 bottom_section">
                <div class="leftside_table">
                    <!-- <div class="table_field">
                        <span>ACCT</span>
                        <span></span>
                    </div> -->
                    <div class="table_field">
                        <span>Pagado</span>
                        <span>L {{ $receipt->invoice->amount }}</span>
                    </div>
                    <div class="table_field">
                        <span>Saldo Pendiente</span>
                        <span>{{ (Helper::paid($receipt->invoice))}}</span>
                    </div>
                   
                    
                    @if($receipt->payment_mode == 'Depositar')
                    <div class="table_field">
                        <span>Referencia no.</span>
                        <span>{{$receipt->reference_number}}</span>
                    </div>
                    @endif
                    @if($receipt->payment_mode == 'Cheque')
                    <div class="table_field">
                        <span>Cheque no.</span>
                        <span>{{$receipt->cheque_number}}</span>
                    </div>
                    @endif
                   
                    
                </div>
            </div>
            <div class="col-md-6 col-sm-7 bottom_section">
                <div class="rightside_table">
                   <!--  <div class="from_to under_line ">                        
                        <p>{{ trans('cruds.receipt.from') }}<span>{{ $receipt->invoice->from }}</span></p>
                        <p>TO<span></span></p>
                    </div> -->
                    @if($receipt->payment_mode != 'Efectivo')
                    <div class="from_to under_line ">                        
                        <p>Banco<span>{{$receipt->bank_name}}</span></p>                        
                    </div>
                    @endif
                    <div class="from_to under_line ">                        
                        <p>Modo de pago<span>{{$receipt->payment_mode}}</span></p>                        
                    </div>
                    <div class="received_by under_line">
                        <span>{{ trans('cruds.receipt.receivedby') }}</span>{{$receipt->generated_by}}                        
                    </div>
                
                </div>
            </div>
            <div class="col-md-12 col-sm-12">
                <div class="footer_receipt">
                    <h4>Bo. Montevideo 7ave. 4 calle, El Progreso, Yoro</h4>
                    <h4><strong>Mail:</strong>&nbsp;&nbsp;info@ledison.shop, <strong>Phone:</strong>&nbsp;&nbsp;9996-3398</h4>
                    <h4><strong>RTN:</strong>&nbsp;&nbsp;05018012477539</h4>
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
