@extends('layouts.admin')
@section('content')
<style>
tr.highlight{
	
	background-color: #ffe6e6;
}
.loader img, .loader p{
	display:none;
    width: 70px;
    position: absolute;
    left: 0;
    right: 0;
    margin: auto;
    top: 45%;
    z-index: 999;
}
.loader p{
	    top: 56% !important;
}
.blur-filter {
    -moz-filter: blur(2px);
    -o-filter: blur(2px);
    backdrop-filter: blur(1px);
    -ms-filter: blur(2px);
    position: absolute;
    z-index: 99;
    filter: blur(2px) !important;
    -webkit-filter: blur(6px) !important;
    width: 100%;
    height: 100%;
}
.loader_imm {
    position: fixed;
    z-index: 9999;
    left: 0;
    right: 0;
    margin: 0 auto;
    top: 0;
    height: 100%;
    background: #ffffff17;
}
</style>
<div class="loader" >
	<div class="blur"></div>
	<img src="{{asset('public/images/preloader.gif')}}" alt="" class="img-fluid" >
	<p>Updating...</p>
</div>
<div class="card">
    <div class="card-header">
        Orden #{{ $order->id }} 
        <a class="btn btn-info" href="{{ url()->previous() }}">
            {{ trans('global.back_to_list') }}
        </a>

        

        
            <a class="btn btn-info" href="{{ route('admin.orders.pdf',$order->id) }}" onclick="return confirm('Are you sure to download PDF ?')">
              Descargar PDF
            </a>
            <button class="btn btn-info as_mail">
              PDF como Correo
            </button>
            <a class="btn btn-info" href="{{ route('admin.invoice.pdf',$order->id) }}" onclick="return confirm('Are you sure to download Factura ?')">
              Descargar Factura
            </a>
            
			@if($order->isApproved == 1 || $order->isApproved == 2)
            @if($order->status == 0)
            <span class="float-right"> @role('administrator') <a href="{{ route('admin.orders.dispatch',$order->id) }}" class="btn btn-primary" onclick="return confirm('Are you sure?')">Envío <i class='fas fa-shipping-fast'></i></a> @endrole @can('shipped_order') <a href="{{ route('admin.orders.dispatch',$order->id) }}" class="btn btn-primary" onclick="return confirm('Are you sure?')">Envío <i class='fas fa-shipping-fast'></i></a> @endcan </span>
            @elseif($order->status == 1)
             <span class="float-right"><a href="#" class="btn btn-success"> <i class="fa fa-check-circle"></i> Enviado</a></span>
             
            @else
             <span class="float-right"><a href="#" class="btn btn-success"> <i class="fa fa-truck"></i> Entregado</a></span>
            
            @endif
             
        @endif

        @if($order->isApproved == 0)
         <span class="float-right">
            @can('approve_order')
                 <a href="{{ route('admin.orders.approve',$order->id) }}" class="btn btn-success" onclick="return confirm('Are you sure?')">Aprobar</a>

                 <a href="{{ route('admin.orders.disapprove',$order->id) }}" class="btn btn-danger" onclick="return confirm('Are you sure?')">Desaprobar</a>
            @endcan
         </span>
        @endif
        @if($order->status != 1)
		 <span class="float-right mr-1"><a href="/admin/orders/{{$order->id}}/edit" class="btn btn-success"> <i class="fa fa-edit"></i> Edit</a></span>
		@endif
    </div>

    <div class="card-body">
        <div class="mb-2">
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>{{ trans('cruds.user.fields.id') }}</th>
                        <td>#{{ $order->id }}</td>
                    </tr>
                    <tr>
                        <th>Cliente</th>
                        <td>{{ $order->clients->name }}  <a href="/admin/clients/profile/{{ $order->client }}" class="btn btn-success btn-xs" target="_blank">View Profile</a></td>
                    </tr>
                    <tr>
                        <th>Subtotal</th>
                        <td>{{ $order->amount }}</td>
                    </tr>
                    <tr>
                        <th>ISV</th>
                        <td>{{ $order->tax }}</td>
                    </tr>
                    <tr>
                        <th>Total</th>
                        <td>{{ $order->total }}</td>
                    </tr>
                    <tr>
                        <th>Notas</th>
                        <td>{{ $order->description }}</td>
                    </tr>
                </tbody>
            </table>
            
        </div>


    </div>
</div>

<div class="card">
    <div class="card-header">
        Productos
    </div>

    <div class="card-body">
        <div class="mb-2">
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <th>Codigo</th>
                        <th>Descripción</th>
                        <th>Precio</th>
                        <th>Cantidad</th>
                        <th>Total</th>
                        <th>Action</th>
                    </tr>
                    @foreach($order->products as $product)
					
					<?php //echo "<pre>"; print_r($product); echo "</pre>"; ?>
					
                    <tr class="{{ ($product->pivot->need > 0) ? 'highlight' : '' }}">
                        <td>{{ $product->code }}</td>
                        <td>{{ $product->description }}</td>
                        <td>{{ \App\Order::priceByQty($product->pivot->id) }}</td>
                        <td>
							<div class="edit_field_<?php echo $product->pivot->id ?>">
								<span class="update_quantity_<?php echo $product->pivot->id ?>">{{ $product->pivot->qty }}</span> 
								<span class="btn btn-xs btn-primary" style="float:right;" onclick="edit_quantity({{$product->pivot->id}});">Edit</span>
							</div>
							<div class="control-group" style="display:none" id="edit_field_<?php echo $product->pivot->id ?>">
								<input type="number" id="quantity<?php echo $product->pivot->id ?>" class="form-comtrol" style="width: 47px;" value="{{ $product->pivot->qty }}" name="quantity">
                             								
								<button type="submit" onclick="update_quantity({{$product->pivot->id}});" class="btn btn-xs btn-success">Save</button>						
							</div>
						</td>
                        <td>L. {{ number_format(\App\Order::discountTotalByPro($product->pivot->id)['total'],2) }}</td>
                        <td><a href="{{ $product->pivot->id }}/delete-product"><button type="button" class="btn btn-danger">Delete</button></a></td>
                       
                    </tr>
                   @endforeach
				   <tr>
				    <th colspan="5" class="text-right">TOTAL A PAGAR : {{ $order->total }}</th>
				   </tr>
                </tbody>
            </table>
            
        </div>


    </div>
</div>

 <!-- Modal: modalPoll -->
<div class="modal fade right" id="mailModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
  aria-hidden="true" data-backdrop="false">
<div class="modal-dialog modal-full-height modal-right modal-notify modal-info" role="document">
  <form method="post" action="{{ route('admin.orders.emailpdf',$order->id) }}" >
    @csrf
    <div class="modal-content">
      <!--Header-->
      <div class="modal-header justify-content-center">
        <h4>Correo electrónico del cliente</h4>
      </div>

      <!--Body-->
      <div class="modal-body">
        <div class="form-group">
          <input type="input" name="email" class="form-control mb-2" placeholder="Enter Email" required="" />
          
        </div>
      </div>

      <!--Footer-->
      <div class="modal-footer justify-content-center">
        <a type="button" class="btn btn-outline-primary waves-effect" data-dismiss="modal">{{ trans('global.close') }}</a>
        <button type="submit" class="btn btn-primary waves-effect next_btn">Enviar</button>
      </div>
    </div>
  </form>
  </div>
</div>

<!-- Modal: modalPoll -->
@endsection
@section('scripts')
<script>


$('.as_mail').on('click',function(){
  
  $('#mailModal').modal('show');
});


function edit_quantity(id){
	$("#edit_field_"+id).show();
	$(".edit_field_"+id).hide();
}

function update_quantity(id){
	   $(".blur").addClass("blur-filter");
       $(".loader").addClass("loader_imm");
       $(".loader_imm img").show();
       $(".loader_imm p").show();
	var quantity = $('#quantity'+id).val();
	
	
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	$.ajax({
		url: "/admin/orders/update-quantity",
		type: "POST",
		data: { quantity : quantity, id : id }, 
		success: function(data){
			$('.update_quantity_'+id).text(quantity);
			$(".edit_field_"+id).show();
			$("#edit_field_"+id).hide();
			window.location.reload();
		}
	});
}
</script>
@endsection

