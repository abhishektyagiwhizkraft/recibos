@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        Comision Setting
    </div>

    <div class="card-body">
        @foreach($users as $user)
		<div class="row data-list">
			<div class="col-md-5 mb-1"><strong>{{ $user->name }}</strong> ({{ $user->email }})</div>
			<div class="col-md-2 mb-1"><input type="number" max="30" maxlength="2" name="comision" class="form-control com_input" value="{{ ($user->comision > 0) ? $user->comision : '' }}" placeholder="Enter Comision in (%)"  oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" /></div><span>%</span>
			<div class="col-md-3 mb-1 text-right"><button class="btn btn-success update_com" data-id="{{ $user->id }}">Update</button></div>
		</div>
		<hr>
		@endforeach
	
    </div>
</div>
@endsection

@section('scripts')
@parent
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script>
   $(document).on('click','.update_com', function() {
	   var com_value = $(this).closest('.data-list').find('.com_input').val();
	   var user_id = $(this).data('id');
	   
	   $.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				}
		});
		
        $.ajax({
			url: "{{ url('admin/setting/comision') }}",
			type: "post",
			data: { user_id : user_id, com_value : com_value },
			success: function(data){
				
				if(data.success == true){
					
				  swal("DONE !", "Comision Updated!", "success");
				}
			}
			
		});
	   
   });

</script>
@endsection