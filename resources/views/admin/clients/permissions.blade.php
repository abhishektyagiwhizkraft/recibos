 <div class="row">
    @foreach($permissions as $key => $permission)
        <div class="col-md-4 mb-3">
          <div class="form-group">
            <div class="custom-control custom-checkbox">
              <input class="custom-control-input perm_checkbox" name="
              perm_id" type="checkbox" id="customCheckbox{{$key}}" value="{{$permission->id}}" {{ (in_array($permission->id,$selected)) ? 'checked' : '' }}>
              <label for="customCheckbox{{$key}}" class="custom-control-label">{{trans('cruds.permission.'.$permission->name)}}</label>
            </div>
          </div>
        </div>
    @endforeach
</div>