@extends('layouts.admin')
@section('content')
@role('sales man')
<div style="margin-bottom: 10px;" class="row">
    <div class="col-lg-12">
        <a class="btn btn-success" href="{{ route("admin.warranty-items.create") }}">
            Reemplazar solicitud 
        </a>
    </div>
</div>
@endrole
<div class="card">
    <div class="card-header">
        Lista de solicitudes
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-Role">
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>Receipt</th>
                        <th>Articulo</th>
                        <th>Falla</th>
						<th>Cantidad</th>
                        <th>Acción</th>
                    </tr> 
                </thead>
                <tbody>
                    @php $x = 1; @endphp
                    @foreach($items as $key => $item)
                        <tr data-entry-id="{{ $item->id }}">
                            <td>{{$x}}</td>
                            <td>{{ $item->order_num }}</td>
                            <td>{{ $item->item }}</td>
							<td>{{ $item->fault ?? '' }}</td>
							<td>{{ $item->qty ?? '' }}</td>
							
                            <td>
                               
                                  @if($item->status == '1') 
                                <a class="btn btn-primary" href="{{url('/')}}/admin/manager-received/{{$item->order_num}}">Recibido</a>
                                @endif
								@if($item->status == '2') 
									Recibido
                                @endif
								@if($item->status == '3') 
									Cambios entregados por Bodega
                                @endif
								@if($item->status == '5') 
									Cambios entregados a cliente
                                @endif
								
                            </td>

                        </tr>
                         @php $x++; @endphp
                    @endforeach
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
  
 

  // $.extend(true, $.fn.dataTable.defaults, {
  //   order: [[ 1, 'desc' ]],
  //   pageLength: 100,
  // });
  $('.datatable-Role').DataTable();
    $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
        $($.fn.dataTable.tables(true)).DataTable()
            .columns.adjust();
    });

    
})

</script>
@endsection 