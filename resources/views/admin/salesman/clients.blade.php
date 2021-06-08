@extends('layouts.admin')
@section('content')
<style>
  .form_csv {
    display : none;
    margin:10px;
}
</style>


<div class="card">
    <div class="card-header">
        {{ trans('clients.clients') }} {{ trans('clients.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-client">
                <thead>
                    <tr>
                        <th width="10">{{ trans('global.sno') }}</th>
                        <th>Foto</th>
                        <th>RTN</th>
                        <th>Nombre</th>
                        <th>Direccion</th>
                        <th>Telefono</th>
                        <th>Fax</th>
                        <th>Contacto</th>
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
  
  $('.datatable-client').DataTable({
   
    processing:true,
    serverSide: true,
    ajax: {
          
          url: "{{ route('admin.userClients') }}",

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
            {data: 'avatar',
               render: function(data){
                   if(data){
                     return '<img src="{{url('/')}}'+data+'" width="150"/>';

                   }else{
                    return 'N/A';
                   }
                   
                }
            }, 
            {data: 'code'}, 
            {data: 'name'}, 
            {data: 'direction'}, 
            {data: 'mobile'},
            {data: 'fax'},
            {data: 'contact'},
            {data: 'id',
               render: function(data){

                   return '<a href="clients/profile/'+data+'" class="btn btn-success mt-2"><i class="fas fa-user"></i> Profile</a> <a href="clients/'+data+'/delete" class="btn btn-danger mt-2 confirm"><i class="fas fa-trash"></i></a>';
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
</script>
@endsection