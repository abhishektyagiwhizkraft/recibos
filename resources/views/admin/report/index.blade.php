@extends('layouts.admin')
@section('content')

<style> 
select.sales_by {
    max-width: 140px;
    display: initial;
    position: relative;
    top: 2px;
}
</style> 
<div class="card card-warning">
    <div class="card-header">
        {{ trans('cruds.report.title') }} {{ trans('global.list') }}
    </div>
	<div class="row m-2 imm_options">
      <div class="col-md-4 col-lg-4 col-sm-12">
        <select class="form-control mr-2 select_mode" name="mode">
            <option value="" selected="">Seleccionar modo</option>
            <option value="Depositar">{{ trans('cruds.report.deposit') }}</option>
            <option value="Cheque">{{ trans('cruds.report.cheque') }}</option>
            <option value="Efectivo">{{ trans('cruds.report.cash') }}</option>
        </select>
      </div>
      <div class="col-md-2 col-lg-2 col-sm-12 bank_name">
      </div>
      <div class="col-md-2 col-lg-2 col-sm-12 bank_tNo">
      </div>	 
	  <div class="col-md-4 col-lg-4 col-sm-12">
	    <button class="btn btn-primary reset_salesby " >Reset</button>
	    <select class="form-control mr-2 sales_by " name="sales_by">
            <option value="" selected="">Ventas por</option>
            <option value="by_client">Por Cliente</option>
            <option value="by_product">Por Producto</option>
        </select> 
		<div class="byClient_div mt-1" style="display:none">
		    <select name="by_client" class="by_client form-control customselect2" required=""> 
				 <option value="" selected="">Seleccione Cliente...</option>
				 @foreach($clients as $client)
				 <option value="{{ $client->id }}">{{ $client->name }}</option>
				 @endforeach
            </select>
		</div>
		<div class="byProduct_div mt-1" style="display:none">
		<select name="by_product" class="by_product form-control customselect2" required=""> 
			 <option value="" selected="">Seleccione Producto...</option>
			 @foreach($products as $product)
			 <option value="{{ $product->id }}">{{ $product->description }}</option>
			 @endforeach
            </select>
		</div>
      </div>
    </div>
    <div class="row">
    <div class="col-xs-3 form-inline imm_receive_list" style="position: absolute; z-index: 2;margin-left: 225px;margin-top: 20px;">
       <form method="post" action="{{route('admin.receipts.csv')}}">
        @csrf
         <input type="hidden" name="p_method" id="p_method">
         <input type="hidden" name="post_cheque" id="post_cheque">
         <input type="hidden" name="post_reference" id="post_reference">
         <input type="hidden" name="post_bank" id="post_bank">
         <input type="hidden" name="post_cash_date" id="post_cash_date">
         <input type="hidden" name="csv_by_client" id="csv_by_client">
         <input type="hidden" name="csv_by_product" id="csv_by_product">

         <button type="submit" class="btn btn-primary">CSV</button>
       </form>
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
                            Cliente
                        </th>
                        <th>
                            {{ trans('cruds.receipt.invoiceno') }}
                        </th>
                        <th>
                            {{ trans('cruds.receipt.bank_name') }}
                        </th>
                        <th>
                            Cheque no
                        </th>
                        <th>
                            Referencia no
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
  $('.input_cash_date').datepicker({
        dateFormat: 'yy-mm-dd'
  });
  
  var table = $('.datatable-User').DataTable({
        processing:true,
        serverSide: true,
        // dom: 'Blfrtip',
        // buttons: [
        //       {
        //     extend: 'csv',
        //     exportOptions: {
        //         columns: [1,2,3,4,5]
        //     }
        //   }
        // ],
        ajax: {
          
          url: "{{ route('admin.reports.all') }}",
           headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                  type: "POST",
                  data: function(d) {
                    d.start_date = $('input[name=start]').val();
                    d.end_date = $('input[name=end]').val();
                    d.payment_mode = $('.select_mode').val();
                    d.bank = $('.input_bank').val();
                    d.reference = $('.input_reference').val();
                    d.cheque = $('.input_cheque').val();
                    d.cash_date = $('.input_cash_date').val();
                    d.sales_by = $('.sales_by').val();
                    d.by_client = $('.by_client').val();
                    d.by_product = $('.by_product').val();
                  }
        },
        columnDefs: [
              {
                 targets: '_all',
                 defaultContent: '-'
              }
           ],

        columns: [
            {
                    "data": 'DT_RowIndex',
                    orderable: false, 
                    searchable: false
                },
                {data: 'invoice.from'}, 
                {data: 'invoice_id'}, 
                {data: 'bank_name'},
                {data: 'cheque_number'},
                {data: 'reference_number'},
                {data: 'total_payment',
                  render: function(data){

                      return 'L '+data;
                    
                    }
                },
                {data: 'payment_mode'},
                {data: 'created_at',
                   render: function(data){

                       return moment(data, 'YYYY-MM-DD').format('dddd, MMMM Do YYYY');
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
    $('.select_mode').on('change', function() {
         $('#p_method').val($(this).val());
      if($(this).val()=='Efectivo'){

         $('.bank_tNo').empty();
         $('.bank_name').html('<input type="text" class="form-control input_cash_date" placeholder="Fecha de pago" name="cash_date">');
         $('.input_cash_date').datepicker({
            dateFormat: 'yy-mm-dd'
         });
       }else{
      $('.bank_name').html('<input type="text" class="form-control input_bank" placeholder="Banco" name="bank_name">');
      if($(this).val()=='Depositar'){
        $('.bank_tNo').html('<input type="text" class="form-control input_reference" placeholder="Referencia no." name="reference">');
      }
      if($(this).val()=='Cheque'){
        $('.bank_tNo').html('<input type="text" class="form-control input_cheque" placeholder="Cheque no." name="chequeno">');
      }
      if($(this).val()==''){
        $('.bank_tNo').empty();
        $('.bank_name').empty();
      }
      
       
      }
                table.draw();
    });

    $('.bank_name').on('keyup change','.input_cash_date, .input_bank',function() {
      $('#post_cash_date').val($('.input_cash_date').val());
        $('#post_bank').val($('.input_bank').val());

                table.draw();
    });
    $('.bank_tNo').on('keyup change','.input_reference, .input_cheque',function() {
        $('#post_cheque').val($('.input_cheque').val());
        $('#post_reference').val($('.input_reference').val());
                table.draw();
    });
    $('.sales_by').on('change', function() {
		
		$('#p_method').val('salesBy');
		
		var val = $(this).val();
		if(val == 'by_client'){
			$('.byClient_div').show();
			$('.byProduct_div').hide();
		}else{
			$('.byProduct_div').show();
			$('.byClient_div').hide();
		}
		
	});
	
	$('.by_client').on('change', function() {
		$('#csv_by_client').val($(this).val());
		$('#csv_by_product').val('');
		$(".by_product").val('');
		table.draw();
	});
	$('.by_product').on('change', function() {
		$('#csv_by_client').val('');
		$('#csv_by_product').val($(this).val());
		$(".by_client").val('');
		table.draw();
	});
    
	$('.customselect2').select2({

          theme: "classic"

    });

});
$('.reset_salesby').on('click',function(){
	
	$(".sales_by").val('');
	$('.byClient_div').hide();
	$('.byProduct_div').hide();
	
});
$('.datatable-User').on('click','.send_mail',function(){
  $('.receiptt').val($(this).data('id'));
  $('#mailModal').modal('show');
});



</script>

@endsection