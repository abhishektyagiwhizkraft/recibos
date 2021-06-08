@extends('layouts.admin')
@section('content')

<div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ url()->previous() }}">
               Back
            </a>
            
        </div>
</div>
<div class="card imm_invoice_list">
    <div class="card-header">
		Lista de facturas de <strong>{{ $user }}</strong>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-User">
                <thead>
                    <tr>
                        <th width="10">
                            {{ trans('global.sno') }}
                        </th>
                        <th>
                            {{ trans('cruds.invoice.table.id') }}
                        </th>
                        
                        <th>
                            {{ trans('cruds.invoice.table.amount') }}
                        </th>
                        <th>
                          Estado de pago
                        </th>
                         <th>
                            {{ trans('cruds.invoice.table.date') }}
                            Creada
                        </th>
						<th>
                            Creada
                        </th>
                        <th>
                            {{ trans('global.actions') }}
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($invoices as $key => $invoice)
                        <tr data-entry-id="{{ $invoice->id }}">
                            <td>
                                
                            </td>
                            <td>
                                {{ $invoice->invoice_no ?? '' }}
                            </td>
                          
                            <td>
                                L {{ $invoice->amount ?? '' }}
                            </td>
                            <td>
                              
                               
                                @if(Helper::paid($invoice) > 0)
                                <button class="btn btn-xs btn-warning">Pendiente</button> (L {{ Helper::paid($invoice) }})
                                 
                                @else
                                <button class="btn btn-xs btn-success">Terminado</button>
                                @endif

                          
                            </td>
                           
                            <td>
                                {{ date('D, d M Y', strtotime($invoice->due_date)) ?? '' }}
                            </td>
							<td>
                                {{ date('D, d M Y', strtotime($invoice->created_at)) ?? '' }}
                            </td>
                            
                            <td>
                               
                                <a class="btn btn-xs btn-info" href="{{ route('admin.invoices.edit', $invoice->id) }}">
                                    {{ trans('global.edit') }}
                                </a>
                                
                                @can('manage_invoice')
                                <button class="btn btn-xs btn-primary receipt_btn" data-id="{{ $invoice->id }}">
                                    {{ trans('cruds.invoice.table.receipt') }}
                                </button>
                                @endcan
								                <a class="btn btn-xs btn-info" href="{{ route('admin.invoices.history',$invoice->id) }}">
                                    Historia
                                </a>
                                @can('manage_invoice')
                                <form action="{{ route('admin.invoices.destroy', $invoice->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
                                </form>
                                @endcan
                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>


    </div>
</div>
  <!-- Modal: modalPoll -->
<div class="modal fade right" id="receiptMode" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
  aria-hidden="true" data-backdrop="false">
<div class="modal-dialog modal-full-height modal-right modal-notify modal-info" role="document">
    <div class="modal-content">
      <!--Header-->
      <div class="modal-header justify-content-center">
        <h4>{{ trans('cruds.invoice.fields.payment_mode') }}</h4>
      </div>

      <!--Body-->
      <div class="modal-body">
        <div class="form-group">
          <div class="row m-4">
          <div class="col-md-4">
            <div class="custom-control custom-radio">
              <input class="custom-control-input pMode" type="radio" value="Depositar" id="deposit" name="payment_mode">
              <label for="deposit" class="custom-control-label">{{ trans('cruds.invoice.fields.deposit') }}</label>
            </div>
          </div>
          <div class="col-md-4">
            <div class="custom-control custom-radio">
              <input class="custom-control-input pMode" type="radio" value="Cheque" id="cheque" name="payment_mode" >
              <label for="cheque" class="custom-control-label">{{ trans('cruds.invoice.fields.cheque') }}</label>
            </div>
          </div>
            <div class="col-md-4">
            <div class="custom-control custom-radio">
              <input class="custom-control-input pMode" type="radio" value="Efectivo" id="cash" name="payment_mode">
              <label for="cash" class="custom-control-label">{{ trans('cruds.invoice.fields.cash') }}</label>
            </div>
          </div>
        </div>
        </div>
      </div>

      <!--Footer-->
      <div class="modal-footer justify-content-center">
        <a type="button" class="btn btn-outline-primary waves-effect" data-dismiss="modal">{{ trans('global.close') }}</a>
        <a href="{{ route('admin.receipts.create') }}"><button type="button" class="btn btn-primary waves-effect next_btn" disabled="">{{ trans('global.next') }}</button></a>
      </div>
    </div>
  </div>
</div>

<input type="hidden" id="invoice">
<!-- Modal: modalPoll -->
@endsection
@section('scripts')
@parent
<script>
$(function () {
  
  $('.datatable-User').DataTable({
    "fnRowCallback" : function(nRow, aData, iDisplayIndex){
                $("td:first", nRow).html(iDisplayIndex +1);
               return nRow;
            },
  });
    $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
        $($.fn.dataTable.tables(true)).DataTable()
            .columns.adjust();
    });
});

$('.receipt_btn').on('click',function(){
  $('#invoice').val($(this).data('id'));
  $('#receiptMode').modal('show');
    
});

$('#receiptMode').on('click','.pMode',function(){
    
    var url = '{{route("admin.invoices.savetosession")}}';

  $.ajax({
      
      headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
      
      type:'POST',

      url:url,

      data:{p_mode:$(this).val(),invoice_id:$('#invoice').val()},
      
      beforeSend: function() {
        $("#loading-wrapper").fadeIn();
      },
  
      success:function(data){
         if(data == 'success'){
             $('.next_btn').removeAttr("disabled");
         }
      }

       });
  
});

</script>

@endsection