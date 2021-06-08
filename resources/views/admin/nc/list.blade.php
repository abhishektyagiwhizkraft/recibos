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
        Lista de Nota de Credito
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-products">
                <thead>
                    <tr>
                        <th width="10">{{ trans('global.sno') }}</th>
                        <th>No</th>
                        <th>Cliente</th>
                        <th>Total</th> 
						<th>Creado en</th>
                       
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
          
          url: "{{ route('admin.notacredit.list') }}",

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
            {data: 'note_credit_id'}, 
            {data: 'clients.name'}, 
            {data: 'total'}, 	
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