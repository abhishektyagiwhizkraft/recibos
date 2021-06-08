@extends('layouts.admin')
@section('content')
<style>
  .form_csv {
    display : none;
    margin:10px;
}
</style>

    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.products.create')}}">
                Añadir Producto
            </a>
            <button class="btn btn-success" id="upload_with_csv">Importar archivo CSV</button>
            <span class="form_csv">
            <form method="post" action="{{ route('admin.importProductCSV') }}" id="form_csv" class="form-inline d-inline" enctype='multipart/form-data'>
              @csrf
              <input type="file" class="form-control" style="width:30%" name="csv">
              <button type="submit" class="btn btn-info import">Import</button>
            </form>
          </span>
        </div>
    </div>
<div class="card">
    <div class="card-header">
       Lista de productos
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-products">
                <thead>
                    <tr>
                        <th width="10">{{ trans('global.sno') }}</th>
                        <th>Codigo</th>
                        <th>Descripción</th>
                        <th>Precio</th>
                        <th>Disponible</th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                   
                </tbody>
            </table>
        </div>


    </div>
</div>
<div class="modal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Ingrese la cantidad</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <input type="number" min="1" id="update_qty" class="form-control" />
        <input type="hidden" id="productid" />
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="saveqtyinput">Actualizar</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
@endsection
@section('scripts')
@parent
<script>
$(function () {
  
  
  $(document).on('click','.add_qty', function() {
	  var id = $(this).data('id');
	  $('#productid').val(id);
	  $('.modal').modal('show');
  });
  
  $(document).on('click','#saveqtyinput', function() {
	  
	  var qty = $('#update_qty').val();
	  var pro = $('#productid').val();
	  
	  $.ajax({
      
      headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
      
      type:'POST',

      url:'{{ route("admin.products.updateqty") }}',

      data:{id:pro,qty : qty},
      
      beforeSend: function() {
        $("#loading-wrapper").fadeIn();
      },
  
      success:function(data){
		 
		
        $('.modal').modal('hide'); 
		$('#update_qty').val('');
        $('.datatable-products').DataTable().ajax.reload();		

      }
      });
	  
  });
  
	  
	  
  $( "#upload_with_csv" ).click( function() {
       $('.form_csv').toggleClass('d-inline');
        //$( ".form_csv" ).toggle( 'slow' );
    });
  
  $('.datatable-products').DataTable({
   
    processing:true,
    serverSide: true,
    ajax: {
          
          url: "{{ route('admin.products.list') }}",

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
            {data: 'code'}, 
            {data: 'description'}, 
            //{data: 'tax'}, 
            {data: 'price'},
            {data: 'qty'},
            {data: 'id',
               render: function(data){

                   return '<a href="products/'+data+'/edit" class="btn btn-success mt-2"><i class="fas fa-edit"></i> Edit</a> <a href="products/'+data+'/delete" class="btn btn-danger mt-2 confirm"><i class="fas fa-trash"></i></a> <button data-id="'+data+'" class="add_qty btn btn-info mt-2">Actualizar Cantidad</button>';
                }
            },
            
             
    ]

  });
    $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
        $($.fn.dataTable.tables(true)).DataTable()
            .columns.adjust();
    });
});
$(document).on('click','.confirm',function(){
      return confirm('Are You Sure?');
});
$('.perm_button').on('click',function(){

  $.ajax({
      
      headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
      
      type:'POST',

      url:'{{ route("admin.load.perm") }}',

      data:{user_id:$(this).data('id')},
      
      beforeSend: function() {
        $("#loading-wrapper").fadeIn();
      },
  
      success:function(data){
            $('.append_perm').html(data);

            $('#modalPoll-1').modal('show');
      }
       });
    
});

$('#modalPoll-1').on('click','.perm_checkbox',function(){
  
  var ischecked= $(this).is(':checked');
    if(!ischecked){
    var url = '{{route("admin.perm.unselect")}}';
  }else{
    var url = '{{route("admin.perm.select")}}';
  }
    var id = $(this).val();
    
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