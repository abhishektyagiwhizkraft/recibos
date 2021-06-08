@extends('layouts.client')
@section('content')

<div class="container">
<div class="row">
	<a href="{{ url('client/home') }}" class="btn btn-info ml-3 mb-2">Back</a>
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
                        <th>Total parcial</th>
                        <th>Tax</th>
                        <th>Total</th>
                        <th>Estado</th>
                        <th>Aprobar</th>
                        <th>Creado en</th>

                    </tr>
                </thead>
                <tbody>
                   
                </tbody>
            </table>
        </div>


    </div>
</div>
</div>
@endsection
@section('scripts')
@parent
<script>
$(function () {
  
$('.datatable-products').DataTable({
   
    processing:true,
    serverSide: true,
    ajax: {
          
          url: "{{ route('client.orders') }}",

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