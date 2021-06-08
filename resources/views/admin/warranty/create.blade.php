@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
       Añadir artículo
    </div>

    <div class="card-body">
        <form action="{{ route("admin.warranty-items.store") }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="form-group append_input {{ $errors->has('item') ? 'has-error' : '' }}">
                <label for="item">Articulo*</label>
				<div class="row ">
				<div class="col-md-3">
                <input type="text" id="item" name="items[1][item]" class="form-control" value="" placeholder="Articulo 1" required>
				</div>
				<div class="col-md-5">
                <input type="text" id="item" name="items[1][fault]" class="form-control" value="" placeholder="Falla*" required>
				</div>
				<div class="col-md-2">
                <input type="number" id="item" name="items[1][qty]" min="1" class="form-control"  value="1" placeholder="qty" required>
				</div>
				<div class="col-md-2">
				<button type="button" class="btn btn-success addinput" ><i class="fas fa-plus"></i></button>
                </div>
				</div>
            </div>
            <!--div class="form-group {{ $errors->has('s_no') ? 'has-error' : '' }}">
                <label for="s_no">Número de serie*</label>
                <input type="text" name="s_no" class="form-control" value="{{ old('s_no', isset($item) ? $item->s_no : '') }}">
                @if($errors->has('s_no'))
                    <em class="invalid-feedback">
                        {{ $errors->first('s_no') }}
                    </em>
                @endif
            </div-->
            <div class="form-group {{ $errors->has('client_name') ? 'has-error' : '' }}">
                <label for="fault">Cliente*</label>
                <input type="text" name="client_name" class="form-control" value="{{ old('client_name', isset($item) ? $item->client_name : '') }}" required="">
                @if($errors->has('client_name'))
                    <em class="invalid-feedback">
                        {{ $errors->first('client_name') }}
                    </em>
                @endif
            </div>
            
            <div class="form-group {{ $errors->has('client_mobile') ? 'has-error' : '' }}">
                <label for="client_mobile">Cliente Whatsapp no*</label>
                <input type="text" name="client_mobile" class="form-control" value="{{ old('client_mobile', isset($item) ? $item->client_mobile : '') }}" >
                @if($errors->has('client_mobile'))
                    <em class="invalid-feedback">
                        {{ $errors->first('client_mobile') }}
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
<script>
$(function () {
	
	var max_fields      = 12; //maximum input boxes allowed
	var wrapper   		= $(".append_input"); //Fields wrapper
	var add_button      = $(".addinput"); //Add button ID
	
	var x = 1; //initlal text box count
	$(add_button).click(function(e){ //on add input button click
	 
		e.preventDefault();
		if(x < max_fields){ //max input box allowed
			x++; 
			$(wrapper).append('<div class="row"><div class="col-md-3 mt-2"><input type="text" id="item" name="items['+x+'][item]" class="form-control" value="" placeholder="Articulo '+x+'" required></div><div class="col-md-5 mt-2"><input type="text" id="item" name="items['+x+'][fault]" class="form-control" value="" placeholder="Falla*" required></div><div class="col-md-2 mt-2"><input type="number" id="item" name="items['+x+'][qty]" min="1" class="form-control"  value="1" placeholder="qty" required></div><div class="col-2 mt-2"><button type="button" class="btn btn-danger remove_field" ><i class="fas fa-minus"></i></button></div></div>'); //add input box
		}
	});
	
	$(wrapper).on("click",".remove_field", function(e){ //user click on remove text
		e.preventDefault(); 
		$(this).parent().parent().remove(); x--;
	});
});

</script>
@endsection