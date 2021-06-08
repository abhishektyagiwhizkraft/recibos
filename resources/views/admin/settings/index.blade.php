@extends('layouts.admin')
@section('content')
<style>
 fieldset 
	{
		border: 1px solid #ddd !important;
		margin: 0;
		xmin-width: 0;
		padding: 10px;       
		position: relative;
		border-radius:4px;
		background-color:#f5f5f5;
		padding-left:10px!important;
	}	
	
	legend
	{
		font-size:14px;
		font-weight:bold;
		margin-bottom: 0px; 
		width: 35%; 
		border: 1px solid #ddd;
		border-radius: 4px; 
		padding: 5px 5px 5px 10px; 
		background-color: #ffffff;
	}
	div#ui-datepicker-div {
     z-index: 9999 !important;
    }
	th { font-size: 12px; }
    td { font-size: 12px; }
	.btn-purple {
    color: #fff;
    background-color: #6f42c1;
    border-color: #643ab0;
	}
	#myModal .modal-body div{float:left; width: 100%}
	#myModal .modal-body div p{float:left; width: 35%; font-weight: 600;}
	
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" crossorigin="anonymous" />
<div class="card">
    <div class="card-header">
        Settings
    </div>

    <div class="card-body">
	        <fieldset>
			<legend>Formatos de Factura</legend>
             <button class="btn btn-success mb-2" id="add_format">Agregar Nuevo</button>
             <table class="table table-responsive table-bordered">
				<thead>
				  <tr>
					<th>Sno</th>
					<th>Numero CIA</th>
					<th>Fecha Inicio Emision</th>
					<th>Fecha Final Emision</th>
					<th>Tipo Documento</th>
					<th>Formato Numero</th>
					<th>Numero Inicial</th>
					<th>Numero Final</th>
					<th>Actions</th>
				  </tr>
				</thead>
				<tbody>
				
				</tbody>
			  </table>
			</fieldset>
	      
			<form action="{{ route("admin.settings.store") }}" method="POST" enctype="multipart/form-data">

            @csrf
			
			<fieldset class="col-md-12 mt-3">
			<legend>Admin Email</legend>
      
            <div class="form-group">

            <input type="text" id="admin_email" name="admin_email" class="form-control " value="{{ old('admin_email', isset($admin_email) ? $admin_email : '') }}" />


            </div>
			</fieldset>
			
           	
			<fieldset class="col-md-12 mt-3">
			<legend>SMTP detail</legend>

            <div class="form-group">

              <label for="facebook_url">Driver (ex. smtp)</label>

                <input type="text" id="smtp_driver" name="smtp_driver" class="form-control " value="{{ old('smtp_driver', isset($smtp_driver) ? $smtp_driver : '') }}" />


            </div>
            <div class="form-group">

              <label for="facebook_url">Host</label>

                <input type="text" id="smtp_host" name="smtp_host" class="form-control " value="{{ old('smtp_host', isset($smtp_host) ? $smtp_host : '') }}" />


            </div>
            <div class="form-group">

              <label for="facebook_url">Port</label>

                <input type="text" id="smtp_port" name="smtp_port" class="form-control " value="{{ old('smtp_port', isset($smtp_port) ? $smtp_port : '') }}" />


            </div>
            <div class="form-group">

              <label for="facebook_url">Email or Username</label>

                <input type="text" id="smtp_email" name="smtp_email" class="form-control " value="{{ old('smtp_email', isset($smtp_email) ? $smtp_email : '') }}" />


            </div>
            <div class="form-group">

            <label for="facebook_url">Password</label>

                <input type="text" id="smtp_password" name="smtp_password" class="form-control " value="{{ old('smtp_password', isset($smtp_password) ? $smtp_password : '') }}" />

          
            </div>
            <div class="form-group">

            <label for="facebook_url">Encryption (TLS/SSL)</label>

                <input type="text" id="smtp_encryption" name="smtp_encryption" class="form-control " value="{{ old('smtp_encryption', isset($smtp_encryption) ? $smtp_encryption : '') }}" />

          
            </div>
			<div>

                <input class="btn btn-danger mt-3" type="submit" value="{{ trans('global.save') }}">

            </div>
			</fieldset>
      
			
			
        </form>
    </div>
</div>
<div class="modal fade" id="format_form" tabindex="-1" data-backdrop="static" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header border-bottom-0">
        <h5 class="modal-title" id="exampleModalLabel">Ingrese Detalles</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="form" method="post">
        <div class="modal-body">
          <div class="form-group">
            <label for="cia_number">Numero CIA</label>
            <input type="text" class="form-control" id="cia_number" name="cia_number" aria-describedby="emailHelp">
            <!--small id="emailHelp" class="form-text text-muted">Your information is safe with us.</small-->
          </div>
          <div class="form-group">
            <label for="start_emission_date">Fecha Inicio Emision</label>
            <input type="text" class="form-control input-daterange" id="start_emission_date" name="start_emission_date">
          </div>
          <div class="form-group">
            <label for="end_emission_date">Fecha Final Emision</label>
            <input type="text" class="form-control input-daterange" id="end_emission_date" name="end_emission_date">
          </div>
		  
		  <div class="form-group">
            <label for="document_type">Tipo Documento</label>
			<select class="form-control" name="document_type" id="document_type" required>
				<option value="0">Factura</option>
				<option value="1">Nota de Credito</option>
			</select>
          </div>
		  <div class="form-group">
            <label for="format_number">Formato Numero</label>
            <input type="text" class="form-control" name="format_number" id="format_number">
          </div>
		  <div class="form-group">
            <label for="start_number">Numero Inicial</label>
            <input type="text" class="form-control" id="start_number" name="start_number">
          </div>
		  <div class="form-group">
            <label for="end_number">Numero Final</label>
            <input type="text" class="form-control" id="end_number" name="end_number">
          </div>
        </div>
        <div class="modal-footer border-top-0 d-flex justify-content-center">
          <button type="submit" class="btn btn-success save_submit">Submit</button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="format_form_edit" tabindex="-1" data-backdrop="static" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header border-bottom-0">
        <h5 class="modal-title" id="exampleModalLabel">Editar Detalles</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="form_edit" method="post">
        <div class="modal-body">
          <div class="form-group">
            <label for="cia_number">Numero CIA</label>
            <input type="text" class="form-control" id="cia_number" name="cia_number" aria-describedby="emailHelp">
            <!--small id="emailHelp" class="form-text text-muted">Your information is safe with us.</small-->
          </div>
          <div class="form-group">
            <label for="start_emission_date">Fecha Inicio Emision</label>
            <input type="text" class="form-control input-daterange" id="start_emission_date" name="start_emission_date">
          </div>
          <div class="form-group">
            <label for="end_emission_date">Fecha Final Emision</label>
            <input type="text" class="form-control input-daterange" id="end_emission_date" name="end_emission_date">
          </div>
		  
		  <div class="form-group">
            <label for="document_type">Tipo Documento</label>
			<select class="form-control" name="document_type" id="document_type" required>
				<option value="0">Factura</option>
				<option value="1">Nota de Credito</option>
			</select>
          </div>
		  <div class="form-group">
            <label for="format_number">Formato Numero</label>
            <input type="text" class="form-control" name="format_number" id="format_number">
          </div>
		  <div class="form-group">
            <label for="start_number">Numero Inicial</label>
            <input type="text" class="form-control" id="start_number" name="start_number">
          </div>
		  <div class="form-group">
            <label for="end_number">Numero Final</label>
            <input type="text" class="form-control" id="end_number" name="end_number">
          </div>
		  <input type="hidden" name="id" id="id">
        </div>
        <div class="modal-footer border-top-0 d-flex justify-content-center">
          <button type="submit" class="btn btn-success">Actualizar</button>
        </div>
      </form>
    </div>
  </div>
</div>


<div class="modal fade" id="myModal" role="dialog">
<div class="modal-dialog">
  <!-- Modal content-->
  <div class="modal-content">
    <div class="modal-header">
      <h4 class="modal-title">Detalles</h4>
    </div>
    <div class="modal-body">
      <div class="cia_number"><p>Numero CIA: </p><span></span></div>
      <div class="start_emission_date"><p>Fecha Inicio Emision: </p><span></span></div>
      <div class="end_emission_date"><p>Fecha Final Emision: </p><span></span></div>
      <div class="document_type"><p>Tipo Documento: </p><span></span></div>
      <div class="format_number"><p>Formato Numero: </p><span></span></div>
      <div class="start_number"><p>Numero Inicial: </p><span></span></div>
      <div class="end_number"><p>Numero Final: </p><span></span></div>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
    </div>
  </div>
 </div>
</div>

@endsection

@section('scripts')
@parent
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" crossorigin="anonymous"></script>
<script>


$(function() {
	$('.table').DataTable({
    "bPaginate": false,
    "bLengthChange": false,
    "bFilter": false,
    "bInfo": false,
    "bAutoWidth": false ,
	
	ajax: {
          
          url: "{{ route('admin.settings.index') }}",

           headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                  type: "POST",
              
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
            {data: 'cia_number'}, 
            {data: 'start_emission_date',
				render: function(data){
					return moment(data).format('DD/MM/YYYY');
                }
			}, 
            {data: 'end_emission_date',
				render: function(data){
					return moment(data).format('DD/MM/YYYY');
                }
			}, 
            {data: 'document_type',
				render:function(num){
					if(num == 0){
						return 'Factura';
					}else{
						return 'Nota de Credito';
					}
					
				}
			}, 
            {data: 'format_number'}, 
            {data: 'start_number'}, 
            {data: 'end_number'}, 
            {data: 'id',
				render:function(id){
					return '<button class="btn btn-success btn-xs show_btn"><i class="fas fa-eye"></i></button> <button class="btn btn-info btn-xs edit_btn"><i class="fas fa-edit"></i></button>'
					
				}
			}, 
            
            
             
    ]
	
	
	});
	
	var table = $('.table').DataTable();
    $('.table').on('click', 'tbody .show_btn', function () {
        //console.log(table.row(this).data());
        $("#myModal .modal-body div span").text("");
        $(".cia_number span").text(table.row($(this).closest('tr')).data().cia_number);
        $(".start_emission_date span").text(table.row($(this).closest('tr')).data().start_emission_date);
        $(".end_emission_date span").text(table.row($(this).closest('tr')).data().end_emission_date);
        $(".document_type span").text(table.row($(this).closest('tr')).data().document_type);
        $(".format_number span").text(table.row($(this).closest('tr')).data().format_number);
        $(".start_number span").text(table.row($(this).closest('tr')).data().start_number);
        $(".end_number span").text(table.row($(this).closest('tr')).data().end_number);
        $("#myModal").modal("show");
    });
	
	$('.table').on('click', 'tbody .edit_btn', function () {
        console.log(table.row($(this).closest('tr')).data());
        $("#form_edit").trigger("reset");
        $("#form_edit #cia_number").val(table.row($(this).closest('tr')).data().cia_number);
        $("#form_edit #start_emission_date").val(table.row($(this).closest('tr')).data().start_emission_date);
        $("#form_edit #end_emission_date").val(table.row($(this).closest('tr')).data().end_emission_date);
		$('#form_edit #document_type').val(table.row($(this).closest('tr')).data().document_type).trigger('change');
		// $("#form_edit #document_type option[value='"+table.row($(this).closest('tr')).data().document_type+"']").prop('selected',true);
        $("#form_edit #format_number").val(table.row($(this).closest('tr')).data().format_number);
        $("#form_edit #start_number").val(table.row($(this).closest('tr')).data().start_number);
        $("#form_edit #end_number").val(table.row($(this).closest('tr')).data().end_number);
        $("#form_edit #id").val(table.row($(this).closest('tr')).data().id);
        $("#format_form_edit").modal("show");
    });
	
	
    $('.input-daterange').datepicker({
        dateFormat: 'yy-mm-dd'
  });
  
 
});
$('#format_form').on('hidden.bs.modal', function () {
    $("#form").trigger("reset");
})
$('#add_format').on('click',function(){
	$('#format_form').modal('show');
});


$('#form').on('submit',function(e){
	$('.save_submit').prop('disabled',true);
	e.preventDefault();
	var formData = $(this).serialize();

  $.ajax({
      
      headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
      
      type:'POST',

      url:'{{ route("admin.setting.facturaFormat") }}',

      data: formData,
      
      beforeSend: function() {
        $("#loading-wrapper").fadeIn();
      },
  
      success:function(data){
		    if(data.success == true){
            $('#format_form').modal('hide');
			$('.save_submit').prop('disabled',false);
			$('.table').DataTable().ajax.reload();
			 toastr.success('Factura Format Added !');
			}
      }
       });
    
});

$('#form_edit').on('submit',function(e){
	e.preventDefault();
	var formData = $(this).serialize();

  $.ajax({
      
      headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
      
      type:'POST',

      url:'{{ route("admin.setting.facturaFormatUpdate") }}',

      data: formData,
      
      beforeSend: function() {
        $("#loading-wrapper").fadeIn();
      },
  
      success:function(data){
		    if(data.success == true){
            $('#format_form_edit').modal('hide');
			$('.table').DataTable().ajax.reload();
			 toastr.success('Detalles Format Updated !');
			}
      }
       });
    
});

</script>
@endsection