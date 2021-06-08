@extends('layouts.admin')
@section('content')
<div class="row">
  <div class="col-md-6">
    <div class="card card-warning">
      <div class="card-header">
        {{ trans('cruds.receipt.invoice_title') }}
      </div>
      <div class="card-body ">
        <p><strong>{{ trans('cruds.receipt.invoiceno') }}.</strong> :  #{{$invoice->id}}</p>
        <p><strong>{{ trans('cruds.receipt.amount') }}</strong> :  ${{$invoice->amount}} - {{ucwords(\App\Invoices::numberToWord($invoice->amount))}} Dollars</p>
      </div>
    </div>
  </div>
  <div class="col-md-6">
    <div class="card card-warning">
    <div class="card-header">{{ trans('cruds.receipt.create_title') }}</div>

    <div class="card-body">
            <form action="{{ route("admin.receipts.store") }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="row">
              <div class="col-md-12">
                <div class="form-group form-group {{ $errors->has('bank_name') ? 'has-error' : '' }}">
                        <label for="bank_name">{{ trans('cruds.receipt.bank_name') }}*</label>
                        <input type="text" id="bank_name" name="bank_name" class="form-control" value=""  required="">
                        
                    </div>
                  </div>
            </div>
           
            @if(session()->get('payment_mode') == 'deposit')
            <div class="row">
              <div class="col-md-12">
                    <div class="form-group {{ $errors->has('reference_number') ? 'has-error' : '' }}">
                        <label for="reference_number">{{ trans('cruds.receipt.referenceno') }},*</label>
                        <input type="text" id="reference_number" name="reference_number" class="form-control" value=""  required="">
                        
                    </div>
                  </div>
            </div>
            @else
            <div class="row">
              <div class="col-md-12">
                    <div class="form-group {{ $errors->has('cheque_number') ? 'has-error' : '' }}">
                        <label for="cheque_number">{{ trans('cruds.receipt.chequeno') }},*</label>
                        <input type="text" id="cheque_number" name="cheque_number" class="form-control" value=""  required="">
                        
                    </div>
                  </div>
            </div>
            @endif
            <div class="row">
              <div class="col-md-12">
                    <div class="form-group {{ $errors->has('issue_deposit_date') ? 'has-error' : '' }}">
                        <label for="issue_deposit_date">{{(session()->get('payment_mode') == 'deposit') ? trans('cruds.receipt.deposit_date') : trans('cruds.receipt.cheque_issue_date')}}*</label>
                        <input type="text" id="issue_deposit_date" name="issue_deposit_date" class="form-control" value=""  required="">
                        
                    </div>
                  </div>
            </div>
            <input type="hidden" name="payment_mode" value="{{ session()->get('payment_mode') }}">
                    <button type="submit" class="btn btn-primary">{{ trans('global.save') }}</button>
            
             </form>
              </div>
            </div>

    </div>
</div>
  </div>



@endsection
@section('scripts')
@parent
<script>

$(function () {
  $('#issue_deposit_date').datepicker({
                     dateFormat: 'yy-mm-dd'
  });
  
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

  $('#receiptMode').modal('show');
    
});

$('#modalPoll-1').on('click','.perm_checkbox',function(){
  
  var ischecked= $(this).is(':checked');
    if(!ischecked){
    var url = '{{route("admin.perm.unselect")}}';
  }else{
    var url = '{{route("admin.perm.select")}}';
  }
    var id = $(this).val();
    alert(id);
  $.ajax({
      
      headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
      
      type:'POST',

      url:url,

      data:{perm_id:$(this).val()},
      
      beforeSend: function() {
        $("#loading-wrapper").fadeIn();
      },
  
      success:function(data){

      }

       });
  
});

</script>

@endsection