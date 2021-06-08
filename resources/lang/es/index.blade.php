@extends('layouts.admin')
@section('content')

 
<div class="card card-warning">
    <div class="card-header">
        Receipt List
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
                            Invoice ID
                        </th>
                        <th>
                            Bank Name
                        </th>
                        <th>
                            Amount
                        </th>
                        <th>
                            Payment Mode
                        </th>
                         <th>
                            Date
                        </th>
                        <th>
                            {{ trans('global.actions') }}
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($receipts as $key => $receipt)
                        <tr data-entry-id="{{ $receipt->id }}">
                            <td>
                                
                            </td>
                            <td>
                                {{ $receipt->invoice_id ?? '' }}
                            </td>
                            <td>
                                {{ $receipt->bank_name ?? '' }}
                            </td>
                            <td>
                                ${{ $receipt->total_payment ?? '' }}
                            </td>
                            <td>
                                {{ ucwords($receipt->payment_mode) ?? '' }}
                            </td>
                            <td>
                                {{ date('D, M Y', strtotime($receipt->created_at)) ?? '' }}
                            </td>
                            
                            <td>
                               
                                @hasPermission('manage_receipt')
                                <a href="{{ route('admin.receipts.show',$receipt->id) }}" class="btn btn-xs btn-primary">
                                    {{ trans('cruds.invoice.table.receipt') }}
                                </a>
                                <a href="{{ route('admin.receipts.pdf',$receipt->id) }}" class="btn btn-xs btn-primary">
                                    Download
                                </a>
                                @endhasPermission

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
              <input class="custom-control-input pMode" type="radio" value="deposit" id="deposit" name="payment_mode">
              <label for="deposit" class="custom-control-label">{{ trans('cruds.invoice.fields.deposit') }}</label>
            </div>
          </div>
          <div class="col-md-4">
            <div class="custom-control custom-radio">
              <input class="custom-control-input pMode" type="radio" value="cheque" id="cheque" name="payment_mode" >
              <label for="cheque" class="custom-control-label">{{ trans('cruds.invoice.fields.cheque') }}</label>
            </div>
          </div>
            <div class="col-md-4">
            <div class="custom-control custom-radio">
              <input class="custom-control-input pMode" type="radio" value="cash" id="cash" name="payment_mode">
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