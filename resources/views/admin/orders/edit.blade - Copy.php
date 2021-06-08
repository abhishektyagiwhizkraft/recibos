@extends('layouts.admin')
@section('content')
<style>
/*.select2-dropdown.select2-dropdown--below{
    width: 100% !important;
}*/



</style>
<div class="card">
    <div class="card-header">
        Editar orden
    </div>

    <div class="card-body">
        <form action="{{ route('admin.orders.update',[$order->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="form-group {{ $errors->has('client') ? 'has-error' : '' }}">
                <label for="price">Cliente*</label>
                    <select name="client" class="form-control customselect2" required=""> 
                         <option value="">Select a Cliente...</option>
                         @foreach($clients as $client)
                         <option value="{{ $client->id }}" {{ ($order->client == $client->id) ? 'selected' : '' }}>{{ $client->name }}</option> 
                         @endforeach
                    </select>
              </div>
            <div class="form-group {{ $errors->has('products') ? 'has-error' : '' }}">
                <label for="products">Productos*</label>
                <div class="push_here">
                     @php $x = 1; @endphp
                     @foreach($order->products as $sel_pro)
                    
                      <div class="each">
                        <div class="row">
                            <div class="col-md-7 mt-2">
                               
                                    <select name="products[{{$x}}][id]" id="products" class="form-control sel_pro customselect2" required=""> 
                                          <option value="">Select Productos...</option>
                                           @foreach($products as $product)
                                           <option data-price="{{ $product->price }}" value="{{ $product->id }}" {{ ($sel_pro->id == $product->id) ? 'selected' : '' }} >{{ $product->description }}</option> 
                                           @endforeach
                                    </select>
                               
                            </div>
                            <div class="col-md-2 mt-2">
                              <input type="number" min="1" class="form-control qty" name="products[{{$x}}][qty]" placeholder="Quantity" value="{{ $sel_pro->pivot->qty }}" required="">
                            </div>
                            <div class="col-md-2 mt-2">
                              <input type="text" class="form-control unitTotal" placeholder="Tarifa" value="{{ \App\Product::getPrice($sel_pro->id) }}" required="" readonly="">
                            </div>
                            
                            <div class="col-md-1 mt-2">
                                @if($x==1)
                                    <!--button type="button" class="btn btn-success add_btn"><i class="fa fa-plus " ></i></button-->
                                @else
                                    <button type="button" class="btn btn-danger remove_field"><i class="fa fa-minus " ></i></button>
                                @endif
                            </div>

                        </div>
                      </div>
                      @php $x++; @endphp
                     @endforeach
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
                    <input type="text" class="form-control amount-total" name="amount" value="{{ $order->amount }}" readonly="">
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
                <label for="tax">Tax*</label>
                    <input type="text" class="form-control tax-total" name="tax" value="{{ $order->tax }}" readonly="">
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
                    <input type="text" class="form-control total" name="total" value="{{ $order->total }}" readonly="">
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
                <textarea id="description" class="form-control" name="description">{{ $order->description }}</textarea>
              
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
           $('.each').each(function(index, row) {

             var price = $(row).find('.sel_pro option:selected').attr('data-price');
             var qty = $(row).find('.qty').val();
             if(qty == null || price == null){
                return false;
             }

              var unitTotal = parseFloat(price);
             $(row).find('.unitTotal').val(unitTotal.toFixed(2));

              total = parseFloat(price * qty);
              sm += total;
              tax += parseFloat(percentage(total));
             
           });
           
           $('.amount-total').val(sm.toFixed(2));
           $('.tax-total').val(tax.toFixed(2));

           var total = parseFloat($('.amount-total').val()) + ( parseFloat($('.tax-total').val()));
           $('.total').val(total.toFixed(2));
        
      });

      function percentage(amount){
         var ret = ((15/100)*(amount));
         return ret;
      }


      var max_fields = 100;
      var wrapper    = $('.push_here');
      var add_btn    = $('.add_btn');

      var x = '{{ $x-1 }}';
      $(add_btn).click(function(e){

        e.preventDefault();
        if(x < max_fields){
         
         x++;

         $(wrapper).append('<div class="each"><div class="row"><div class="col-md-7 mt-2"><select name="products['+x+'][id]" id="products" class="form-control sel_pro customselect2" required=""> <option value="">Select Productos...</option>@foreach($products as $product)<option data-price="{{ $product->price }}" value="{{ $product->id }}">{{ $product->description }}</option> @endforeach</select></div><div class="col-md-2 mt-2"><input type="number" min="1" class="form-control qty" name="products['+x+'][qty]" placeholder="Quantity" required=""></div><div class="col-md-2 mt-2"><input type="text" class="form-control unitTotal" placeholder="Tarifa" required="" readonly=""></div><div class="col-md-1 mt-2"><button type="button" class="btn btn-danger remove_field"><i class="fa fa-minus " ></i></button></div></div></div>');
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