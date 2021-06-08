@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        Añadir Producto
    </div>

    <div class="card-body">
        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group {{ $errors->has('code') ? 'has-error' : '' }}">
                <label for="code">Codigo*</label>
                <input type="text" id="code" name="code" class="form-control" value="{{ old('code') }}" required>
                @if($errors->has('code'))
                    <em class="invalid-feedback">
                        {{ $errors->first('code') }}
                    </em>
                @endif
            </div>
			<div class="prices_div">
			<div class="form-group">
                <label for="price">Precio 1</label>
				<div class="row">
				    <div class="col-md-3">
					<input type="text" id="price_1" name="product[1][price]" class="form-control mb-1" value="{{ old('product[1][price]') }}" required>
					</div>
					<div class="col-md-3">
					<input type="number" id="qty_from_1" name="product[1][qty_from]" class="form-control" value="{{ old('qty_from_b') }}" placeholder="Cantidad de" required>
					</div>
					<span>To</span>
					<div class="col-md-3">
					<input type="number" id="qty_to_1" name="product[1][qty_to]" class="form-control" value="{{ old('product[1][qty_to]') }}" placeholder="Cantidad a" required>
					</div>
					<div class="col-md-3">
					
					</div>
				</div>
				 
            </div>
			</div>
			<button type="button" class="btn btn-success add_btn mt-2 mb-2"><i class="fa fa-plus " ></i> Agregar Precio</button>
			<div class="form-group {{ $errors->has('qty') ? 'has-error' : '' }}">
                <label for="qty">Cantidad*</label>
                <input type="number" id="qty" name="qty" class="form-control" value="{{ old('qty') }}" required>
                @if($errors->has('qty'))
                    <em class="invalid-feedback">
                        {{ $errors->first('qty') }}
                    </em>
                @endif
            </div>
            <div class="form-group {{ $errors->has('description') ? 'has-error' : '' }}">
                <label for="description">Descripción*</label>
                <textarea id="description" class="form-control" name="description"></textarea>
              
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

      var x = 1;
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
