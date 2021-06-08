@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.user.title_singular') }}
    </div>

    <div class="card-body">
        <form action="{{ route("admin.users.update", [$user->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                <label for="name">{{ trans('cruds.user.fields.name') }}*</label>
                <input type="text" id="name" name="name" class="form-control" value="{{ old('name', isset($user) ? $user->name : '') }}" required>
                @if($errors->has('name'))
                    <em class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.user.fields.name_helper') }}
                </p>
            </div>
            <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
                <label for="email">{{ trans('cruds.user.fields.email') }}*</label>
                <input type="email" id="email" name="email" class="form-control" value="{{ old('email', isset($user) ? $user->email : '') }}" required>
                @if($errors->has('email'))
                    <em class="invalid-feedback">
                        {{ $errors->first('email') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.user.fields.email_helper') }}
                </p>
            </div>
            <div class="form-group {{ $errors->has('mobile') ? 'has-error' : '' }}">
                <label for="email">{{ trans('cruds.user.fields.mobile') }}*</label>
                <input type="text" id="mobile" name="mobile" class="form-control" value="{{ old('mobile', isset($user) ? $user->mobile : '') }}" required>
                @if($errors->has('mobile'))
                    <em class="invalid-feedback">
                        {{ $errors->first('mobile') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.user.fields.mobile_helper') }}
                </p>
            </div>
			
			<div class="form-group {{ $errors->has('roles') ? 'has-error' : '' }}">
                <label for="roles">{{ trans('cruds.user.fields.roles') }}*</label>
                <select name="roles[]" id="roles" class="form-control"  required>
                    @foreach($roles as $id => $roles)
                        <option value="{{ $id }}" {{ (in_array($id, old('roles', [])) || isset($user) && $user->roles()->pluck('name', 'id')->contains($id)) ? 'selected' : '' }}>{{ $roles }}</option>
                    @endforeach
                </select>
                @if($errors->has('roles'))
                    <em class="invalid-feedback">   
                        {{ $errors->first('roles') }}
                    </em>
                @endif
                
            </div>
	
			@if($user->roles[0]->name == 'sales man' || $user->roles[0]->name == 'sales person')
			<div class="form-group comision_field">
                <label for="comision">Comision(%)*</label>
                <input type="number" id="comision" name="comision" class="form-control" value="{{ old('comision', isset($user) ? $user->comision : '') }}" placeholder="Enter Comision (%)"  required> 
                
            </div>
			<input type="hidden" class="hiddenVal" value="{{ $user->comision }}" />
			@endif
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
	$('#roles').on('change',function(){
		var value = $(this).val();
		if(value == 'sales man' || value == 'sales person'){
			$('#comision').prop('required',true);
			$('.comision_field').show();
		}else{
			$('#comision').prop('required',false);
			$('#comision').val('');
			$('.comision_field').hide();
		}
	});
});
</script>

@endsection