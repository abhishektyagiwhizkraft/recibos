@extends('layouts.admin')
@section('content')

 
<div class="card card-warning">
    <div class="card-header">
        {{ trans('cruds.receipt.title') }} {{ trans('global.list') }}
    </div>

    <div class="row">
    <div class="col-xs-3 form-inline imm_receive_list" style="position: absolute; z-index: 2;margin-left: 170px;margin-top: 20px;">
      <div class=" input-group">
        <input type="text" class="input-sm form-control input-daterange" name="start" placeholder="{{ trans('global.start_date') }}" />
        <span class="input-group-addon"> To </span>
        <input type="text" class="input-sm form-control input-daterange" name="end" placeholder="{{ trans('global.end_date') }}" />
      </div>
      <button type="button" id="dateSearch" class="btn btn-sm btn-primary ml-1">show</button>
      <!--select class="selectApp form-control ml-2">
      <option value="all" selected>All Appointments</option>
      <option  value="upcoming">Upcoming Apointments</option>
      </select-->
    </div>
    </div>
    <div class="card-body imm_receive_table">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-User">
                <thead>
                    <tr>
                        <th width="10">
                            {{ trans('global.sno') }}
                        </th>
                        <th>
                            {{ trans('cruds.receipt.invoiceno') }}
                        </th>
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
                            {{ trans('cruds.receipt.date') }}
                        </th>
                        <th>
                            {{ trans('global.actions') }}
                        </th>
                    </tr>
                </thead>
                <tbody>
                    
                </tbody>
            </table>
        </div>


    </div>
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
        <h4>Enter User Email</h4>
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
$(function () {

  $('.input-daterange').datepicker({
        dateFormat: 'yy-mm-dd'
  });
  
  var table = $('.datatable-User').DataTable({
        processing:true,
        serverSide: true,
       
        ajax: {
          
          url: "{{ route('admin.receipts.all') }}",
           headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                  type: "POST",
                  data: function(d) {
                    d.start_date = $('input[name=start]').val();
                    d.end_date = $('input[name=end]').val();
                  }
        },

        columns: [
            {
                    "data": 'DT_RowIndex',
                    orderable: false, 
                    searchable: false
                },
                {data: 'invoice_id'}, 
                {data: 'bank_name'},
                {data: 'total_payment',
                  render: function(data){

                      return 'L '+data;
                    
                    }
                },
                {data: 'payment_mode'},
                {data: 'created_at',
                   render: function(data){

                       return moment(data, 'YYYY-MM-DD').format('dddd, MMMM Do YYYY,');
                    }
                },
                
                {data: 'id',orderable:false, 
                 
                   render: function(id,type,data){
                    @can('manage_receipt')
                    if(data.status == 2){
                      return '<a class="btn btn-xs btn-success" href="receipts/'+id+'" target="_blank">{{ trans("cruds.invoice.table.printreceipt") }}</a> <a href="generatepdf/'+id+'" class="btn btn-xs btn-primary">{{trans("global.download")}}</a> <button data-id="'+id+'" class="btn btn-xs btn-primary send_mail">Mail PDF</button> <a href="delete-receipt/'+id+'" class="btn btn-xs btn-danger">Eliminar</a>';
                    }else{
                      return '<a href="javascript:void()" class="btn btn-xs btn-danger">{{trans("global.unauth")}}</a> <a href="delete-receipt/'+id+'" class="btn btn-xs btn-danger">Eliminar</a>';
                    }
                    @else
                     return '<a href="delete-receipt/'+id+'" class="btn btn-xs btn-danger">Eliminar</a>';
                    @endcan
                    }
                    

                }
                 
        ]

  });
    $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
        $($.fn.dataTable.tables(true)).DataTable()
            .columns.adjust();
    });
    $('#dateSearch').on('click', function() {
                table.draw();
    });

});

$('.datatable-User').on('click','.send_mail',function(){
  $('.receiptt').val($(this).data('id'));
  $('#mailModal').modal('show');
});



</script>

@endsection