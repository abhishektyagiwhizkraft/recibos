@extends('layouts.admin')
@section('content')
<style>
/*.select2-dropdown.select2-dropdown--below{
    width: 100% !important;
}*/
.select2-container--classic .select2-selection--single .select2-selection__arrow {
    height: 36px;
}
.select2-container--classic .select2-selection--single .select2-selection__rendered { 
    line-height: 38px;
}
.select2-container--classic .select2-selection--single {
    height: 38px;
}


</style>

<div class="card">
    <div class="card-header">
        Crear orden
    </div>

    <div class="card-body">
        <form action="{{ route('admin.orders.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group {{ $errors->has('client') ? 'has-error' : '' }}">
                <label for="price">Cliente*</label>
                    <select name="client" class="form-control customselect2" required=""> 
                         <option value="" disabled="" selected="">Seleccione Cliente...</option>
                         @foreach($clients as $client)
                         <option value="{{ $client->id }}">{{ $client->name }} || {{$client->direction}}</option>
                         @endforeach
                    </select>
              </div>
            <div class="form-group {{ $errors->has('products') ? 'has-error' : '' }}">
                <label for="products">Productos*</label>
                <div class="push_here">
                  <div class="each">
                    <div class="row">
                        <div class="col-md-7 mt-2">
                          <select name="products[1][id]" id="products" class="form-control sel_pro customselect2" required=""> 
                              <option value="" disabled="" selected="">Seleccione Productos...</option>
                               @foreach($products as $product)
                               <option data-price="{{ $product->price }}" data-array="{{ json_encode($product->prices->toArray()) }}" value="{{ $product->id }}">{{ $product->description }}</option> 
                               @endforeach
                          </select>
                        </div>
						<div class="col-md-2 mt-2">
                          <input type="number" min="1" class="form-control qty" name="products[1][qty]" placeholder="Cantidad" required="">
                        </div>
                         <div class="col-md-2 mt-2">
                          <input type="text" class="form-control unitTotal" placeholder="Tarifa" required="" readonly="">
                        </div>
						<input type="hidden" name="discount" class="discount">
						<input type="hidden" name="product_total" class="product_total">
                        <div class="col-md-1 mt-2">
                           
                        </div>
                    </div>
                  </div>
                </div>
                <button type="button" class="btn btn-success add_btn mt-2"><i class="fa fa-plus " ></i> Agregar</button>
                @if($errors->has('price'))
                    <em class="invalid-feedback">
                        {{ $errors->first('price') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.user.fields.email_helper') }}
                </p>
            </div>
            <div class="form-group {{ $errors->has('amount') ? 'has-error' : '' }}">
                <label for="amount">Cantidad*</label>
                    <input type="text" class="form-control amount-total" name="amount" readonly="">
                @if($errors->has('amount'))
                    <em class="invalid-feedback">
                        {{ $errors->first('amount') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.user.fields.email_helper') }}
                </p>
            </div>
            <div class="form-group {{ $errors->has('tax') ? 'has-error' : '' }}">
                <label for="tax">ISV*</label>
                    <input type="text" class="form-control tax-total" name="tax" readonly="">
                @if($errors->has('tax'))
                    <em class="invalid-feedback">
                        {{ $errors->first('tax') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.user.fields.email_helper') }}
                </p>
            </div>
            <div class="form-group {{ $errors->has('total') ? 'has-error' : '' }}">
                <label for="tax">Total*</label>
                    <input type="text" class="form-control total" name="total" readonly="">
                @if($errors->has('total'))
                    <em class="invalid-feedback">
                        {{ $errors->first('total') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.user.fields.email_helper') }}
                </p>
            </div>
            <div class="form-group {{ $errors->has('description') ? 'has-error' : '' }}">
                <label for="description">Notas</label>
                <textarea id="description" class="form-control" name="description"></textarea>
              
                @if($errors->has('description'))
                    <em class="invalid-feedback">
                        {{ $errors->first('description') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.user.fields.email_helper') }}
                </p>
            </div>
          
            <div>
                <input class="btn btn-danger" type="submit" value="{{ trans('global.save') }}">
            </div>
        </form>


    </div>
</div>
@endsection
@section('scripts')
@parent
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script>

$(function () {
      function selectRefresh() {
        $('.customselect2').select2({

          theme: "classic"

        });
      }

       // $('.select2').on('change', function(e) {
        
       //    var sm = 0;
       //    var per = 0;
       //    $('.select2 option:selected').each(function (index) {
       //        sm += parseFloat($(this).attr("data-price"));
       //        per += parseFloat(percentage($(this).attr("data-price")));
       //    });

       //    $('.amount-total').val(sm.toFixed(2));
       //    $('.tax-total').val(per.toFixed(2));

       //    var total = parseFloat($('.amount-total').val()) + ( parseFloat($('.tax-total').val()));
       //    $('.total').val(total.toFixed(2));

       //  });

     
      var total = 0;
      $(document).on('keyup change','.sel_pro, .qty', function() {
			var sm = 0;
			var tax = 0;
			// var product_id = $(this).closest('.each').find('.sel_pro option:selected').val();
			// var product = $(this).closest('.each').find('.sel_pro option:selected').text();
			// var qty = $(this).closest('.each').find('.qty').val();
			// if(qty != '' && checkStock(product_id,qty)== 0){
				// var avail_qty = localStorage.getItem("avail_qty");
				// if(avail_qty < 0){
					// avail_qty = 0;
				// }
				// swal("Cantidad Disponible : "+avail_qty, "Available quantity of product ( "+product+" ) :: "+avail_qty, "error");
			// }
		  
           $('.each').each(function(index, row) {
             
			 var pro_id = $(row).find('.sel_pro option:selected').val();
			 var pro = $(row).find('.sel_pro option:selected').text();
             var price = $(row).find('.sel_pro option:selected').attr('data-price');
			 
             var qty = $(row).find('.qty').val();
			 var unitTotal = parseFloat(price);
             $(row).find('.unitTotal').val(unitTotal.toFixed(2));
             if(qty == '' || price == '' || pro_id == ''){
				
                return false;
             }
			 var prices = JSON.parse($(row).find('.sel_pro option:selected').attr('data-array'));
			 var newPrice = "";
		     $.each( prices, function( key, value ) {
				
				
					if(qty >= parseInt(value.qty_from) && qty <=  parseInt(value.qty_to)){
						
						var newunitTotal = parseFloat(value.price);
						$(row).find('.unitTotal').val(newunitTotal.toFixed(2));
						$(row).find('.sel_pro option:selected').attr('data-price',newunitTotal)
					}
					if(qty >= parseInt(value.qty_from) && qty >= parseInt(value.qty_to)){
						console.log('this');
						var newunitTotal = parseFloat(value.price);
						$(row).find('.unitTotal').val(newunitTotal.toFixed(2));
						$(row).find('.sel_pro option:selected').attr('data-price',newunitTotal)
					}
					
			
				
			 });
			 
			 price = $(row).find('.sel_pro option:selected').attr('data-price');
			 
			 /* Check Stock */
			 /* if(qty != ''){
				
               if(checkStock(pro_id,qty)== 0){
				   var avail_qty = localStorage.getItem("avail_qty");
				   swal("Cantidad Disponible : "+avail_qty, "Available quantity of product ( "+pro+" ) :: "+avail_qty, "error");
				   // var qty = $(row).find('.qty').val('');
				   // return false;
			   }		
				 
			 } */

             // var unitTotal = parseFloat(price);
             // $(row).find('.unitTotal').val(unitTotal.toFixed(2));
              console.log(price);
              total = parseFloat(price * qty);
			  
              sm += total;
              tax += parseFloat(percentage(total));
             
           });
           
           $('.amount-total').val(sm.toFixed(2));
           $('.tax-total').val(tax.toFixed(2));

           var total = parseFloat($('.amount-total').val()) + ( parseFloat($('.tax-total').val()));
           $('.total').val(total.toFixed(2));
        
      });
	  function priceWithQty(pro_id,qty){
			var status = 1;
			$.ajaxSetup({
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					}
			});
			$.ajax({
				url: "{{ route('admin.orders.getPrice') }}",
				type: "post",
				dataType: 'json',
				   async: false,
				data: { pid : pro_id, qty : qty },
				success: function(data){
					
					console.log($data);
				}
				
			});
			
			return status;
		   
	  }
	  

      function percentage(amount){
         var ret = ((15/100)*(amount));
         return ret;
      }


      var max_fields = 100;
      var wrapper    = $('.push_here');
      var add_btn    = $('.add_btn');

      var x = 1;
      $(add_btn).click(function(e){

        e.preventDefault();
        if(x < max_fields){
         
         x++;

         $(wrapper).append('<div class="each"><div class="row"><div class="col-md-7 mt-2"><select name="products['+x+'][id]" id="products" class="form-control sel_pro customselect2" required=""> <option value="">Seleccione Productos...</option>@foreach($products as $product)<option data-price="{{ $product->price }}" data-array="{{ json_encode($product->prices->toArray()) }}" value="{{ $product->id }}">{{ $product->description }}</option> @endforeach</select></div><div class="col-md-2 mt-2"><input type="number" min="1" class="form-control qty" name="products['+x+'][qty]" placeholder="Cantidad" required=""></div><div class="col-md-2 mt-2"><input type="text" class="form-control unitTotal" placeholder="Tarifa" required="" readonly=""></div><div class="col-md-1 mt-2"><button type="button" class="btn btn-danger remove_field"><i class="fa fa-minus " ></i></button></div></div></div>');
           selectRefresh();

        }

      });
      $(document).ready(function(){
          selectRefresh();
      });

      $(wrapper).on('click','.remove_field',function(e){
        e.preventDefault();
           
           $(this).parent().parent().parent().remove();
           x--;
           var sm = 0;
           var tax = 0;
           $('.each').each(function(index, row) {

             var price = $(row).find('.sel_pro option:selected').attr('data-price');
             var qty = $(row).find('.qty').val();
             if(qty == null || price == null){
                return false;
             }

              total = parseFloat(price * qty);
              sm += total;
              tax += parseFloat(percentage(total));
             
           });
           
           $('.amount-total').val(sm.toFixed(2));
           $('.tax-total').val(tax.toFixed(2));

           var total = parseFloat($('.amount-total').val()) + ( parseFloat($('.tax-total').val()));
           $('.total').val(total.toFixed(2));

      });


  });
</script>
@endsection