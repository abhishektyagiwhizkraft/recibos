@extends('layouts.admin')
@section('content')
<a style="margin-top:20px;" class="btn btn-default mb-2" href="{{ url()->previous() }}">
                Volver a la lista
            </a>
<div class="card">
    <div class="card-header">
        <strong>Factura #{{$invoice->invoice_no}} Historia</strong>
    </div>

    <div class="card-body">
        <div class="mb-2">
            <table class=" table table-bordered table-striped table-hover datatable datatable-User">
                <thead>
                    <tr>
                       
                        <th>
                            {{ trans('cruds.invoice.table.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.invoice.table.from') }}
                        </th>
                      
                        <th>
                            {{ trans('cruds.invoice.table.amount') }}
                        </th>
                        <th>
                            {{ trans('cruds.invoice.table.date') }}
                        </th>
                        <th>
                          Estado de pago
                        </th>
                         
                        
                    </tr>
                </thead>
                <tbody>
                    <tr>
                    
                            <td>
                                {{ $invoice->invoice_no ?? '' }}
                            </td>
                            <td>
                                {{ $invoice->from ?? '' }}
                            </td>
                          
                            <td>
                                L {{ $invoice->amount ?? '' }}
                            </td>
                            
                           
                            <td>
                                {{ date('D, d M Y', strtotime($invoice->due_date)) ?? '' }}
                            </td>
                            <td>
                              
                               
                                @if(Helper::paid($invoice) > 0)
                                <button class="btn btn-xs btn-warning">Pendiente</button> (L {{ Helper::paid($invoice) }})
                                 
                                @else
                                <button class="btn btn-xs btn-success">Terminado</button>
                                @endif

                          
                            </td>
                            
                          

                        </tr>
                </tbody>
            </table>
            
        </div>


    </div>
</div>

<div class="card">
    <div class="card-header">
        <strong>Ingresos</strong>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-User">
                <thead>
                    <tr>
    
                        <th>
                            {{ trans('cruds.receipt.bank_name') }}
                        </th>
                        <th>
                            {{ trans('cruds.receipt.amount') }}
                        </th>
                        <th>
                            {{ trans('cruds.receipt.payment_mode') }}
                        </th>
                         <th>
                            {{ trans('cruds.receipt.chequeno') }}
                        </th>
                        <th>
                            {{ trans('cruds.receipt.referenceno') }}
                        </th>
                        <th>
                            {{ trans('cruds.receipt.date') }}
                        </th>
                        <th>
                            {{ trans('global.actions') }}
                        </th>
                    </tr>
                </thead>
                <tbody>

                    @foreach($invoice->receipts as $receipt)
                    <tr>
                        <td>{{($receipt->bank_name) ? $receipt->bank_name:'N/A'}}</td>
                        <td>L {{$receipt->total_payment}}</td>
                        <td>{{$receipt->payment_mode}}</td>
                        <td>{{($receipt->cheque_number)?$receipt->cheque_number:'N/A'}}</td>
                        <td>{{($receipt->reference_number)?$receipt->reference_number:'N/A'}}</td>
                        <td>{{ date('D, d M Y', strtotime($receipt->created_at)) ?? '' }}</td>
                        <td>
                        @if($receipt->status == 2)
                          <a class="btn btn-xs btn-success" href="{{url('/admin')}}/receipts/{{$receipt->id}}" target="_blank">{{ trans("cruds.invoice.table.printreceipt") }}</a> <a href="{{url('/admin')}}/generatepdf/{{$receipt->id}}" class="btn btn-xs btn-primary">{{trans("global.download")}}</a> <button data-id="{{$receipt->id}}" class="btn btn-xs btn-primary send_mail">Mail PDF</button>
                        @else
                          <a href="javascript:void()" class="btn btn-xs btn-danger">{{trans("global.unauth")}}
                        @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@if(!empty($order))
<div class="card">
    <div class="card-header">
        <strong>Productos</strong>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-User">
                <thead>
                    <tr>
    
                        <th>
                            Codigo
                        </th>
                        <th>
                            Descripción
                        </th>
                        <th>
                            Precio
                        </th>
                         <th>
                            Cantidad
                        </th>
                        
                    </tr>
                </thead>
                <tbody>

                   @foreach($order->products as $product)
                    <tr>
                        <td>{{ $product->code }}</td>
                        <td>{{ $product->description }}</td>
                        <td>{{ $product->price }}</td>
                        <td>{{ $product->pivot->qty }}</td>
                    </tr>
                   @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif
</div>
     <!-- Modal: modalPoll -->
<div class="modal fade right" id="mailModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
  aria-hidden="true" data-backdrop="false">
<div class="modal-dialog modal-full-height modal-right modal-notify modal-info" role="document">
  <form method="post" action="{{ route('admin.receipts.emailpdf') }}" >
    @csrf
    <div class="modal-content">
      <!--Header-->
      <div class="modal-header justify-content-center">
        <h4>Ingrese el correo electrónico del usuario</h4>
      </div>

      <!--Body-->
      <div class="modal-body">
        <div class="form-group">
          <input type="input" name="email" class="form-control mb-2" placeholder="Enter Email" required="" />
          <textarea class="form-control" name="pdf_desc" placeholder="Description"></textarea>
          <input type="hidden" name="receipt_id" class="receiptt" />
        </div>
      </div>

      <!--Footer-->
      <div class="modal-footer justify-content-center">
        <a type="button" class="btn btn-outline-primary waves-effect" data-dismiss="modal">{{ trans('global.close') }}</a>
        <button type="submit" class="btn btn-primary waves-effect next_btn">Send</button>
      </div>
    </div>
  </form>
  </div>
</div>
<!-- Modal: modalPoll -->
@endsection
@section('scripts')
@parent
<script>
$('.datatable-User').on('click','.send_mail',function(){
  $('.receiptt').val($(this).data('id'));
  $('#mailModal').modal('show');
});
</script>
@endsection
