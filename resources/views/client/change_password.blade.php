

@extends('layouts.client')
@section('content')

<div class="container">
<div class="card">
    <div class="card-header">
        Cambia la Contraseña
    </div>

    <div class="card-body">
        <form action="{{ route('client.change-password') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="form-group {{ $errors->has('current_password') ? 'has-error' : '' }}">
                <label for="current_password">Contraseña Actual *</label>
                <input type="password" id="current_password" name="current_password" class="form-control {{ $errors->has('current_password') ? ' is-invalid' : '' }}" >
                @if($errors->has('current_password'))
                    <em class="invalid-feedback">
                        {{ $errors->first('current_password') }}
                    </em>
                @endif
            </div>
            <div class="form-group {{ $errors->has('new_password') ? 'has-error' : '' }}">
                <label for="new_password">Nueva Contraseña *</label>
                <input type="password" id="new_password" name="new_password" class="form-control {{ $errors->has('new_password') ? ' is-invalid' : '' }}" >
                @if($errors->has('new_password'))
                    <em class="invalid-feedback">
                        {{ $errors->first('new_password') }}
                    </em>
                @endif
            </div>
            <div class="form-group {{ $errors->has('new_password_confirmation') ? 'has-error' : '' }}">
                <label for="new_password_confirmation">Nueva Confirmación de Contraseña *</label>
                <input type="password" id="new_password_confirmation" name="new_password_confirmation" class="form-control {{ $errors->has('new_password_confirmation') ? ' is-invalid' : '' }}" >
                @if($errors->has('new_password_confirmation'))
                    <em class="invalid-feedback">
                        {{ $errors->first('new_password_confirmation') }}
                    </em>
                @endif
            </div>
            <div>
                <input class="btn btn-danger" type="submit" value="{{ trans('global.save') }}">
            </div>
        </form>


    </div>
</div>
</div>
@endsection