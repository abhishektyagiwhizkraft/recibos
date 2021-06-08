@extends('layouts.admin')
@section('content')
 @can('pick_faulty_items')
<div style="margin-bottom: 10px;" class="row">
    <div class="col-lg-12">
        <a class="btn btn-success" href="{{ route("admin.warranty-items.create") }}">
            Reemplazar solicitud 
        </a>
    </div>
</div>
@endcan
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
                        <th>Clinte</th>
                        <th>Receipt</th>
                        <th>Articulo</th>
                        
                        <th>Estado</th>
                        
                        <th>Acción</th>
                        @can('replace_faulty_items')
                        <th> </th>
                        @endcan
                    </tr>
                </thead>
                <tbody>
                    @php $x = 1; @endphp
                    @foreach($items as $key => $item)
                        <tr data-entry-id="{{ $item->id }}">
                            <td>{{$x}}</td>
                            <td>{{ $item->client_name }}</td>
                            <td>#{{$item->order_num}}</td>
                            <td>{{ \App\ReplaceWarrantyItem::items($item->order_num) }}</td>
                            
                            <td>
                                 
                               @if($item->status == '1')
                                   <button class="btn btn-xs btn-warning">Cambios recogidos del cliente</button>
                                
								@elseif($item->status == '2') 
                                   <button class="btn btn-xs btn-success">Producto recibido por bodega</button> 
								@elseif($item->status == '3') 
                                   <button class="btn btn-xs btn-success">Cambios entregados por Bodega</button> 
                              
								@elseif($item->status == '5') 
                                   <button class="btn btn-xs btn-success"><i class="fa fa-check"></i> Cambios entregados a cliente</button> 
                                @endif
                            </td>
                            
                            <td>
                               @can('pick_faulty_items')
                                @if($item->mail_sent == '0' && $item->status == 1)
                                
                                <button data-id="{{$item->order_num}}" class="mb-2 btn btn-success send_as_mail">Enviar Hoja de Devoluciones</button>
                                
                                @endif
                                @if($item->mail_status < 2)
                                <a href="https://api.whatsapp.com/send?phone={{$item->client_mobile}}&text={{url('/')}}/download-document/{{$item->order_num}}/{{$item->unique_url}}" class="btn btn-primary">Send on Whatsapp</a>
                                @endif
								@if($item->mail_sent == '1' && $item->status == 1)
                                Documento enviado
                                @endif
                                @if($item->status == '2') 
									<a class="btn btn-success" href="{{url('/')}}/admin/sales_man-received/{{$item->order_num}}">Cambios recibidos de Bodega</a>
                                @endif
								@if($item->status == '3') 
									
								<a class="btn btn-primary" href="https://api.whatsapp.com/send?phone={{$item->client_mobile}}&text={{url('/')}}/replacement-confirmation/{{$item->order_num}}/{{$item->unique_url}}">Enviar link recibido cliente</a>
								
                                @endif
								
								@if($item->status == '5') 
                                   Cambios entregados a cliente
                                @endif
                                @endcan
                                @can('replace_faulty_items')
                                @if($item->status == '1') 
                                <a class="btn btn-primary" href="{{url('/')}}/admin/manager-received/{{$item->order_num}}">Recibido</a>
                                @endif
								
                               
                                @if($item->status > 1 )
                                <a href="{{url('/')}}/admin/preview-items/{{$item->order_num}}" class="btn btn-info" target="_blank"><i class="fas fa-eye"></i></a>
                                @endif
                                
								@if($item->status == '2') 
									Recibido
                                @endif
								@if($item->status == '5') 
									Cambios entregados a cliente
                                @endif
                                
                                @endcan
                            </td>
                             @can('replace_faulty_items')
                             <td>
                                 <a href="{{url('/')}}/admin/item-delete/{{$item->order_num}}" class="btn btn-danger" onclick="return confirm('Are you sure?')"><i class="fas fa-trash"></i></a>
                             </td>
                             @endcan
                             
                        </tr>
                         @php $x++; @endphp
                    @endforeach
                </tbody>
            </table>
        </div>


    </div>
</div>
<!-- Modal: modalPoll -->
<div class="modal fade right" id="mailModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
  aria-hidden="true" data-backdrop="false">
<div class="modal-dialog modal-full-height modal-right modal-notify modal-info" role="document">
  <form method="post" action="{{ route('admin.items.emailaspdf') }}" >
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
          <input type="hidden" name="order_num" class="receiptt" />
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

<div class="modal fade right" id="replacedmailModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
  aria-hidden="true" data-backdrop="false">
<div class="modal-dialog modal-full-height modal-right modal-notify modal-info" role="document">
  <form method="post" action="{{ route('admin.items.emailreplaced') }}" >
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
          <input type="hidden" name="order_num" class="receiptreplaced" />
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
  
 

  // $.extend(true, $.fn.dataTable.defaults, {
  //   order: [[ 1, 'desc' ]],
  //   pageLength: 100,
  // });
  $('.datatable-Role').DataTable();
    $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
        $($.fn.dataTable.tables(true)).DataTable()
            .columns.adjust();
    });

});

$('.datatable-Role').on('click','.send_as_mail',function(){
  $('.receiptt').val($(this).data('id'));
  $('#mailModal').modal('show');
});

$('.datatable-Role').on('click','.send_replaced_mail',function(){
  $('.receiptreplaced').val($(this).data('id'));
  $('#replacedmailModal').modal('show');
});

</script>
@endsection