@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('clients.create') }} {{ trans('clients.client') }}
    </div>

    <div class="card-body">
        <form action="{{ route('admin.clients.store') }}" method="POST" enctype="multipart/form-data" >
            @csrf
            <div class="form-group {{ $errors->has('code') ? 'has-error' : '' }}">
                <label for="code">RTN*</label>
                <input type="text" id="code" name="code" class="form-control" value="{{ old('code', isset($user) ? $user->code : '') }}" >
                @if($errors->has('code'))
                    <em class="invalid-feedback">
                        {{ $errors->first('code') }}
                    </em>
                @endif
               
            </div>
            <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                <label for="name">{{ trans('cruds.user.fields.name') }}*</label>
                <input type="text" id="name" name="name" class="form-control" value="{{ old('name', isset($user) ? $user->name : '') }}" >
                @if($errors->has('name'))
                    <em class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </em>
                @endif
               
            </div>
            <div class="form-group {{ $errors->has('direction') ? 'has-error' : '' }}">
                <label for="direction">Direccion</label>
                <input type="text" id="direction" name="direction" class="form-control" value="{{ old('direction', isset($user) ? $user->direction : '') }}" >
                @if($errors->has('direction'))
                    <em class="invalid-feedback">
                        {{ $errors->first('direction') }}
                    </em>
                @endif
               
            </div>

            <div class="form-group {{ $errors->has('mobile') ? 'has-error' : '' }}">
                <label for="mobile">Telefono</label>
                <input type="text" id="mobile" name="mobile" class="form-control" value="{{ old('mobile', isset($user) ? $user->mobile : '') }}" >
                @if($errors->has('mobile'))
                    <em class="invalid-feedback">
                        {{ $errors->first('mobile') }}
                    </em>
                @endif
                
            </div>
            <div class="form-group {{ $errors->has('fax') ? 'has-error' : '' }}">
                <label for="fax">Fax</label>
                <input type="text" id="fax" name="fax" class="form-control" value="{{ old('fax', isset($user) ? $user->fax : '') }}" >
                @if($errors->has('fax'))
                    <em class="invalid-feedback">
                        {{ $errors->first('fax') }}
                    </em>
                @endif
               
            </div>
            <div class="form-group {{ $errors->has('contact') ? 'has-error' : '' }}">
                <label for="contact">Contacto</label>
                <input type="text" id="contact" name="contact" class="form-control" value="{{ old('contact', isset($user) ? $user->contact : '') }}" >
                @if($errors->has('contact'))
                    <em class="invalid-feedback">
                        {{ $errors->first('contact') }}
                    </em>
                @endif

            </div>

            <div class="form-group {{ $errors->has('avatar') ? 'has-error' : '' }}">
                <label for="avatar">Solicitud de Credito</label>
                <input type="file" id="avatar" name="avatar" class="form-control" value="{{ old('avatar', isset($user) ? $user->avatar : '') }}" onchange="return validateFile()">
                @if($errors->has('avatar'))
                    <em class="invalid-feedback">
                        {{ $errors->first('avatar') }}
                    </em>
                @endif
                <p class="helper-block error">
                    
                </p>
            </div>
            
            <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
                <label for="contact">Password</label>
                <input type="password" id="password" name="password" class="form-control" value="" >
                @if($errors->has('password'))
                    <em class="invalid-feedback">
                        {{ $errors->first('password') }}
                    </em>
                @endif

            </div>
            <div class="form-group ">
                <label for="password-confirm">Confirm Password</label>
                <input type="password" id="password-confirm" name="password_confirmation" class="form-control" value="" >

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
   function validateFile() 
        {
            var allowedExtension = ['jpeg', 'jpg','png'];
            var fileExtension = document.getElementById('avatar').value.split('.').pop().toLowerCase();
            var isValidFile = false;

                for(var index in allowedExtension) {

                    if(fileExtension === allowedExtension[index]) {
                        isValidFile = true; 
                        break;
                    }
                }

                if(!isValidFile) {
                    $('#avatar').val('');
                    $('.error').html('Allowed Image Format are : ' + allowedExtension.join(', '));
                }else{
                    $('.error').empty();
                }
                
                return isValidFile;
        }
</script>
@endsection