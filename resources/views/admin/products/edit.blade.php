@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('products.edit') }} {{ trans('products.product') }}
    </div>

    <div class="card-body">
        <form action="{{ route('admin.products.update', [$product->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                <label for="code">Codigo*</label>
                <input type="text" id="code" name="code" class="form-control" value="{{ old('code', isset($product) ? $product->code : '') }}" required>
                @if($errors->has('code'))
                    <em class="invalid-feedback">
                        {{ $errors->first('code') }}
                    </em>
                @endif
                
            </div>
            <div class="prices_div">
			<?php $c = 1;  ?>
			@foreach($product->prices as $price)
			<div class="form-group">
                <label for="price">Precio {{ $c }}</label>
				<div class="row">
				    <div class="col-md-3">
					<input type="text" id="price_{{ $c }}" name="product[{{$c}}][price]" class="form-control mb-1" value="{{ $price->price }}" required>
					</div>
					<div class="col-md-3">
					<input type="number" id="qty_from_{{ $c }}" name="product[{{ $c }}][qty_from]" class="form-control" value="{{ $price->qty_from }}" placeholder="Cantidad de" required>
					</div>
					<span>To</span>
					<div class="col-md-3">
					<input type="number" id="qty_to_{{ $c }}" name="product[{{ $c }}][qty_to]" class="form-control" value="{{ $price->qty_to }}" placeholder="Cantidad a" required>
					</div>
					<input type="hidden" name="product[{{ $c }}][price_id]" value="{{ $price->id }}">
					<div class="col-md-2">
					@if($c > 1)
					<button type="button" class="btn btn-danger remove_field"><i class="fa fa-minus" ></i></button>
					@endif
					</div>
				</div>
				 
            </div>
			<?php $c++;  ?>
			@endforeach
			</div>
			<button type="button" class="btn btn-success add_btn mt-2 mb-2"><i class="fa fa-plus " ></i> Agregar Precio</button>
            <div class="form-group {{ $errors->has('description') ? 'has-error' : '' }}">
                <label for="description">Descripción*</label>
                <textarea id="description" class="form-control" name="description" required>{{ old('price', isset($product) ? $product->description : '') }}</textarea>
              
                @if($errors->has('description'))
                    <em class="invalid-feedback">
                        {{ $errors->first('description') }}
                    </em>
                @endif
                
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
	
	  var max_fields = 100;
      var wrapper    = $('.prices_div');
      var add_btn    = $('.add_btn');

      var x = '{{ $c-1 }}';
      $(add_btn).click(function(e){

        e.preventDefault();
        if(x < max_fields){
         
         x++;

         $(wrapper).append('<div class="form-group"><label for="price">Precio '+x+'</label><div class="row"><div class="col-md-3"><input type="text" id="price_'+x+'" name="product['+x+'][price]" class="form-control mb-1" value="" required></div><div class="col-md-3"><input type="number" id="qty_from_'+x+'" name="product['+x+'][qty_from]" class="form-control" value="" placeholder="Cantidad de" required></div><span>To</span><div class="col-md-3 mb-1"><input type="number" id="qty_to_'+x+'" name="product['+x+'][qty_to]" class="form-control" value="" placeholder="Cantidad a" required></div><div class="col-md-2"><button type="button" class="btn btn-danger remove_field"><i class="fa fa-minus" ></i></button></div></div></div>');
      

        }

      });
	  
	  $(wrapper).on('click','.remove_field',function(e){
        e.preventDefault();
           
           $(this).parent().parent().parent().remove();
           x--;
           

      });
	
});
</script>
@endsection
