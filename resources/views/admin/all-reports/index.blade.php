@extends('layouts.admin')
@section('content')
<style>
.report-widge .form-group {
    margin-bottom: 0px !important;
}
.select2 {
    max-width: 300px !important;
    width: 300px !important;
}
</style>
<form method="post" action="{{ route('admin.reports-generate-all') }}">
@csrf
<div class="row">
    <div class="col-md-6">
		<div class="card">
			<div class="card-header">
				Rango de Fecha
			</div>

			<div class="card-body">
				<div class="form-group row">
					<label for="desde" class="col-sm-2 col-form-label">Desde</label>
					<div class="col-sm-10">
						<input type="text" id="desde" name="desde" class="form-control form-control-sm desde" placeholder="Desde" value="{{ old('desde') }}" required>
					</div>
                </div>
				<div class="form-group row">
					<label for="hasta" class="col-sm-2 col-form-label">Hasta</label>
					<div class="col-sm-10">
						<input type="text" id="hasta" name="hasta" class="form-control form-control-sm desde" placeholder="Hasta" value="{{ old('hasta') }}" required>
					</div>
                </div>
			</div>
		</div>
	</div>
	<div class="col-md-6">
		<div class="card report-widge">
			<div class="card-header">
				Reporte
			</div>

			<div class="card-body ml-5">
			     <div class="form-group row">
				  <div class="icheck-primary d-inline">
					<input type="radio" id="daily_report" class="report" name="report" value="daily_report" required>
					<label for="daily_report">
					  Reporte diario de ventas
					</label>
				  </div>
				 </div>
				 <div class="form-group row">
				  <div class="icheck-primary d-inline">
					<input type="radio" id="per_product_report" class="report" name="report" value="per_product_report" required>
					<label for="per_product_report">
					  Reporte Detallado x Producto
					</label>
				  </div>
				 </div>
			     <div class="form-group row">
				  <div class="icheck-primary d-inline">
					<input type="radio" id="factura_report" class="report" name="report" value="factura_report" required>
					<label for="factura_report">
					  Reporte ventas x factura
					</label>
				  </div>
				  </div>
				 <div class="form-group row">
				  <div class="icheck-primary d-inline">
					<input type="radio" id="vendor_report" class="report" name="report" value="vendor_report" required>
					<label for="vendor_report">
					  Reporte ventas x vendedor
					</label>
				  </div>
				  </div>
				  <div class="form-group row">
				  <div class="icheck-primary d-inline">
					<input type="radio" id="client_report" class="report" name="report" value="client_report" required>
					<label for="client_report">
					  Reporte ventas x Cliente
					</label>
				  </div>
				  </div>
				  <div class="form-group row">
				  <div class="icheck-primary d-inline"> 
					<input type="radio" id="comision_report" class="report" name="report" value="comision_report" required>
					<label for="comision_report">
					  Reporte Comisiones
					</label>
				  </div>
				   </div>
				  <div class="form-group row">
				  <div class="icheck-primary d-inline">
					<input type="radio" id="client_days_report" class="report" name="report" value="client_days_report" required>
					<label for="client_days_report">
					  Reporte de días de no Compra x cliente
					</label>
				  </div>
				  </div>
				  
                </div>
			</div>
		</div>
	</div>
	
	
	<div class="row filter_area_client" style="display:none">
	
		<div class="col-md-6">
		<h5>Cliente</h5>
			<div class="form-group row m-3"> 
				<!--div class="icheck-primary d-inline mr-2 mt-2"> 
					<input type="radio" id="Todos" name="gen_type" value="client_all" checked>
					<label for="Todos"> Todos</label> 
				</div--> 
				<div class="icheck-primary d-inline mt-2">
					<input type="radio" id="specific_client" name="gen_type" value="client" checked>
					<label for="specific_client"> Cliente</label> 
				</div> 
				<div class="form-group d-inline">
					<div class="col-sm-10">
						<select class="form-control form-control-sm customselect2 selected_client" name="selected_client" >
							<option value="" selected disabled>Select Client</option>
							@foreach($clients as $client)
							<option value="{{ $client->id }}" >{{ $client->name }}</option>
							@endforeach
						</select>
					</div> 
				</div> 
			</div>
		</div>
		
	</div>
	<div class="row filter_area_vendor" style="display:none">
		<div class="col-md-6">
		<h5>Filtrar x Vendedor</h5> 
			<div class="form-group row m-3"> 
				<!--div class="icheck-primary d-inline mr-2 mt-2"> 
					<input type="radio" id="filxvend" name="gen_type_v" value="vendor_all" checked >
					<label for="filxvend"> Todos</label> 
				</div--> 
				<div class="icheck-primary d-inline mt-2">
					<input type="radio" id="filxvend1" name="gen_type_v" value="vendor" checked>
					<label for="filxvend1"> Vendedor</label> 
				</div> 
				<div class="form-group d-inline">
					<div class="col-sm-10">
						<select class="form-control form-control-sm customselect2 selected_vendor" name="selected_vendor" >
							<option value="" selected disabled>Select Vendedor</option>
							@foreach($vendors as $vendor)
							<option value="{{ $vendor->id }}" >{{ $vendor->name }}</option>
							@endforeach
						</select>
					</div> 
				</div> 
			</div>
		</div>
	</div>
	<div class="row filter_vender_factura" style="display:none">
		<div class="col-md-6">
		<h5>Tipo de la Factura</h5> 
			<div class="form-group row m-3"> 
				<div class="icheck-primary d-inline mr-2 mt-2"> 
					<input type="radio" id="filxvend" name="factura_type" value="contadoycredito" checked >
					<label for="filxvend"> Contado y Credito</label> 
				</div> 
				<div class="icheck-primary d-inline mr-2 mt-2"> 
					<input type="radio" id="solocredito" name="factura_type" value="solocredito"  >
					<label for="solocredito">Solo Credito</label> 
				</div> 
		
			</div>
			<div class="form-group row m-3"> 
				<div class="icheck-primary d-inline mr-2 mt-2"> 
					<input type="radio" id="solocontado" name="factura_type" value="solocontado"  >
					<label for="solocontado">Solo Contado</label> 
				</div> 
		
			</div>
		</div>
		<div class="col-md-6">
		<h5>Estado de la Factura</h5> 
			<div class="form-group row m-4"> 
				<div class="icheck-primary d-inline mr-2 mt-2"> 
					<input type="radio" id="todas" name="estado_factura" value="todas"  checked>
					<label for="todas"> Todas</label> 
				</div> 
				<div class="icheck-primary d-inline mr-2 mt-2"> 
					<input type="radio" id="pagadas" name="estado_factura" value="pagadas"  >
					<label for="pagadas"> Pagadas</label> 
				</div> 
				<div class="icheck-primary d-inline mr-2 mt-2"> 
					<input type="radio" id="pendientes" name="estado_factura" value="pendientes"  >
					<label for="pendientes"> Pendientes</label> 
				</div> 
		
			</div>
		</div>
	</div>
  <hr>
	
	
	<div class="row">
		<div class="col-md-12 text-center">
			<button type="submit" class="btn btn-primary">Generate <i class="fas fa-file-export"></i></button>
		</div>
	</div>
</div>
</form>
@endsection
@section('scripts')

<script>

   $(function () {
	   selectRefresh();
      function selectRefresh() {
        $('.customselect2').select2({

          theme: "classic"

        });
      }
   });
   
   $('input[name=gen_type]').click(function(){
	   if($(this).val() == 'client'){
		   $('.selected_client').removeAttr('disabled');
	   } else {
		   $('.selected_client').val(null).trigger('change');
		   $('.selected_client').attr('disabled',true);
	   }
   });
   $('input[name=gen_type_v]').click(function(){
	   if($(this).val() == 'vendor'){
		   $('.selected_vendor').removeAttr('disabled');
	   } else {
		   $('.selected_vendor').val(null).trigger('change');
		   $('.selected_vendor').attr('disabled',true);
	   }
   });
   
   $('.desde').datepicker({
        dateFormat: 'dd-mm-yy'
  });
  
  
  
  $('.report').on('click',function(){
	  if($(this).val() == 'client_report'){
		  $('.filter_area_vendor').hide();
		  $('.filter_vender_factura').hide();
		  $('.filter_area_client').show();
		   
	  } else if($(this).val() == 'client_days_report') {
		  $('.filter_area_client').hide();
		  $('.filter_vender_factura').hide();
		  $('.filter_area_vendor').show();
		  
	  } else if($(this).val() == 'client_days_report') {
		  $('.filter_area_client').hide();
		  $('.filter_vender_factura').hide();
		  $('.filter_area_vendor').show();
		  
	  } else if($(this).val() == 'factura_report') {
		  $('.filter_area_client').hide();
		  $('.filter_area_vendor').hide();
		  $('.filter_vender_factura').show();
		  
	  } else {
		  $('.filter_vender_factura').hide();
		  $('.filter_area_vendor').hide();
		  $('.filter_area_client').hide();
	  }
  });

  
  
</script>
@endsection