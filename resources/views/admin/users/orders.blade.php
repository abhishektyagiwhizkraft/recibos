@extends('layouts.admin')
@section('content')
<style>
  .form_csv {
    display : none;
    margin:10px;
}
</style>

    <div style="margin-bottom: 10px;" class="row">
      @can('manage_order')
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.orders.create')}}">
                Crear Orden
            </a>
            <!--button class="btn btn-success" id="upload_with_csv">Import CSV file</button>
            <span class="form_csv">
            <form method="post" action="{{ route('admin.importProductCSV') }}" id="form_csv" class="form-inline d-inline" enctype='multipart/form-data'>
              @csrf
              <input type="file" class="form-control" style="width:30%" name="csv">
              <button type="submit" class="btn btn-info import">Import</button>
            </form>
          </span-->
        </div>
        @endcan
    </div>
<div class="card">
    <div class="card-header">
        Lista de pedidos
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-products">
                <thead>
                    <tr>
                        <th width="10">{{ trans('global.sno') }}</th>
                        <th>Order ID</th>
                        <th>Cliente</th>
                        <th>Total parcial</th>
                        <th>Tax</th>
                        <th>Total</th>
                        <th>Estado</th>
                        <th>Aprobar</th>
                        <th>Creado en</th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                   
                </tbody>
            </table>
        </div>


    </div>
</div>

@endsection
@section('scripts')
@parent
<script>
$(function () {
  

  $( "#upload_with_csv" ).click( function() {
       $('.form_csv').toggleClass('d-inline');
        //$( ".form_csv" ).toggle( 'slow' );
    });
  
  $('.datatable-products').DataTable({
   
    processing:true,
    serverSide: true,
    ajax: {
          
          url: "{{ route('admin.salesOrders',$id) }}",

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
            {data: 'id'}, 
            {data: 'clients.name'}, 
            {data: 'amount'}, 
            {data: 'tax'}, 
            {data: 'total'}, 
            {data: 'status',
               render: function(status){
                 if(status == 0){

                   return '<span class="badge badge-warning">Pendiente</span>';

                 }
                 if(status == 1){

                   return '<span class="badge badge-primary">Enviado</span>';

                 }
                 if(status == 2){

                   return '<span class="badge badge-success">Entregado</span>';

                 }
               }
            }, 
            {data: 'isApproved',
               render: function(status){
                 if(status == 0){

                   return '<span class="badge badge-warning">Necesita Aprobacion</span>';

                 }
                 if(status == 1){

                   return '<span class="badge badge-success">Aprobado</span>';

                 }

                 if(status == 2){

                   return '<span class="badge badge-danger">Desaprobado</span>';

                 }
                 
               }
            }, 
			{data: 'created_at',
			    render: function(data){
					return moment(data).format('DD-MM-YYYY');
				}
			}, 
            {data: 'id',
               render: function(id,type,data){
                   
                   var btns = '<a href="orders/'+id+'" class="btn btn-info mt-2" title="Mostrar"><i class="fa fa-eye"></i></a> <a href="orders/'+id+'/edit" class="btn btn-info mt-2" title="Editar"><i class="fa fa-edit"></i></a> <a href="orders/'+id+'/delete" class="btn btn-danger mt-2 confirm"><i class="fas fa-trash"></i></a> <a href="orders/'+id+'/generate-invoice" class="btn btn-success mt-2 ">convertir a factura</a>';
                   if(data.status == 1){

                      return btns + '@role('sales man') <a href="orders/deliver/'+id+'" class="btn btn-info mt-2">Envío</a>@endrole @role('sales person') <a href="orders/deliver/'+id+'" class="btn btn-info mt-2">Envío</a>@endrole';
                   }else{
                      
                        return btns;

                   }
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