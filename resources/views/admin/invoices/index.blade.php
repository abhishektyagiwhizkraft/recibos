@extends('layouts.admin')
@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" crossorigin="anonymous" />
<div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.invoices.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.invoice.title_singular') }}
            </a>
        </div>
 </div>

<div class="card imm_invoice_list">
    <div class="card-header">
        Facturas List
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
                            Orden ID
                        </th>
                        <th>
                            {{ trans('cruds.invoice.table.from') }}
                        </th>
                      
                        <th>
                            {{ trans('cruds.invoice.table.amount') }}
                        </th>
                        <th>Estado de pago</th>
						<td>Fecha de Vencimiento</td>
						<td>Status</td>
						<td>Actions</td>
                        
                       
                    </tr>
                </thead>
                <tbody>
                   
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
		  <div class="col-md-6">
            <div class="custom-control custom-radio">
              <input class="custom-control-input pMode" type="radio" value="nota_de_credito" id="notaDeCredito" name="payment_mode">
              <label for="notaDeCredito" class="custom-control-label">Nota de Crédito</label>
            </div>
          </div>
          <div class="col-md-6">
            <div class="custom-control custom-radio">
              <input class="custom-control-input pMode" type="radio" value="Depositar" id="deposit" name="payment_mode">
              <label for="deposit" class="custom-control-label">{{ trans('cruds.invoice.fields.deposit') }}</label>
            </div>
          </div>
		  </div>
		  <div class="row m-4">
          <div class="col-md-6">
            <div class="custom-control custom-radio">
              <input class="custom-control-input pMode" type="radio" value="Cheque" id="cheque" name="payment_mode" >
              <label for="cheque" class="custom-control-label">{{ trans('cruds.invoice.fields.cheque') }}</label>
            </div>
          </div>
            <div class="col-md-6">
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
<input type="hidden" id="client_id">
<!-- Modal: modalPoll -->
@endsection
@section('scripts')
@parent
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" crossorigin="anonymous"></script>

<script>
$(function () {
  $('.input-daterange').datepicker({
        dateFormat: 'yy-mm-dd'
  });
  var table = $('.datatable-User').DataTable({
        processing:true,
        serverSide: true,
		
        ajax: {
          
          url: "{{ url('admin/invoices/list') }}",
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
                {data: 'invoice_no'}, 
                {data: 'order_id'},
                {data: 'from'},
                {data: 'amount'},
                {data: 'paid_status',
				
					render: function(data,type,full){
						var btn ='';
                       if(data == 'Pendiente'){
						   btn = '<button class="btn btn-xs btn-warning">Pendiente</button> (L '+full.pending_amount+')';
					   }else{
						   btn = '<button class="btn btn-xs btn-success">Terminado</button>';
					   }
                       return btn;
                    }
				
				},
				{data: 'due_date',
					render: function(data){
						return moment(data, 'YYYY-MM-DD').format('dddd, MMMM Do YYYY,');
                    }
                },
				{data: 'status',
					render: function(status){
						if(status == '1'){
						var btn = '<button class="btn btn-xs btn-success" >Active</button>'
						}else{
							var btn = '<button class="btn btn-xs btn-danger" >Canceled</button>'
						}
						
						return btn;
                    }
                },
				{data: 'id',
					render: function(id,type,data){
						
						var btn1 = '<a class="btn btn-xs btn-info" href="/admin/invoices/'+id+'/edit">Editar</a> '
						var btn2 = '';
						var btn3 = '<a class="btn btn-xs btn-info" href="/admin/invoice/'+id+'/history">Historia</a> ';
						var btn4 = '';
						var btn5 = '';
						@can('manage_invoice')
						   btn2 = '<button class="btn btn-xs btn-primary receipt_btn" data-client="'+data.from_id+'" data-id="'+id+'">Generar recibo</button> ';
					
						   btn4 = '<button class="btn btn-xs btn-danger delete_btn" data-id="'+id+'">Eliminar</button>'
						@endcan
						
							btn5 = '<a href="/admin/invoice/'+id+'/cancel" class="btn btn-xs btn-danger delete_btn" >Cancelar</a>'
					
						return btn1+btn2+btn3+btn4+btn5;
						
                    }
                },
                
   
                 
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

$(document).on('click','.receipt_btn',function(){
  $('#invoice').val($(this).data('id'));
  $('#client_id').val($(this).attr('data-client'));
  $('#receiptMode').modal('show');
    
});



$('#receiptMode').on('click','.pMode',function(){
    
    var url = '{{route("admin.invoices.savetosession")}}';

  $.ajax({
      
      headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
      
      type:'POST',

      url:url,

      data:{p_mode:$(this).val(),client_id:$('#client_id').val(),invoice_id:$('#invoice').val()},
      
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


$(document).on('click','.delete_btn',function(){
    var id = $(this).data('id');
    var url = '/admin/invoices/'+id;
    if (!confirm('Estas seguro?')) return false;
  $.ajax({
      
      headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
      
      type:'POST',
      data: { _method : 'DELETE' },
      url:url,
	  
      beforeSend: function() {
        $("#loading-wrapper").fadeIn();
      },
  
      success:function(data){
         if(data.status == 'success'){
             $('.datatable-User').DataTable().ajax.reload();
			 toastr.success('Factura eliminada !');
         }
      }

       });
  
});

</script>

@endsection