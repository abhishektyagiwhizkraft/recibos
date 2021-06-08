@extends('layouts.admin')
@section('content')

<div class="card card-warning">

    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.invoice.title_singular') }}
    </div>

    <div class="card-body">
        <form action="{{ route('admin.invoices.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-6">
				
                    <div class="form-group {{ $errors->has('invoice_no') ? 'has-error' : '' }}">
                        <label for="invoice_no">{{ trans('cruds.invoice.fields.invoice_number') }}*</label>
                        <input type="text" id="invoice_no" name="invoice_no" class="form-control" value=""  required>
						
                        @if($errors->has('invoice_no'))
                            <em class="invalid-feedback">
                                {{ $errors->first('invoice_no') }}
                            </em>
                        @endif
                        
                    </div>
                    
                    <div class="form-group {{ $errors->has('amount') ? 'has-error' : '' }}">
                        <label for="amount">{{ trans('cruds.invoice.fields.amount') }}*</label>
                        <input type="text" id="amount" name="amount" class="form-control" required>
                        @if($errors->has('amount'))
                            <em class="invalid-feedback">
                                {{ $errors->first('amount') }}
                            </em>
                        @endif
                        
                    </div>
                    <div class="form-group {{ $errors->has('due_date') ? 'has-error' : '' }}">
                        <label for="due_date">Fecha de vencimiento*</label>
                        <input type="text" id="due_date" name="due_date" class="form-control" required>
                        @if($errors->has('due_date'))
                            <em class="invalid-feedback">
                                {{ $errors->first('due_date') }}
                            </em>
                        @endif
                        
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group {{ $errors->has('from') ? 'has-error' : '' }}">
                        <label for="from">Cliente*</label>
                        <input type="text" id="from" name="from" class="form-control" value="" required>
						<p class="invalid-feedback"  id="autocomplete_err">
                                
                        </p>
                        @if($errors->has('from'))
                            <em class="invalid-feedback">
                                {{ $errors->first('from') }}
                            </em>
                        @endif
                    </div>
					<div class="form-group {{ $errors->has('order_id') ? 'has-error' : '' }}">
                        <label for="order_id">Orden ID*</label>
                        <input type="text" id="order_id" name="order_id" class="form-control" value="" required>
                        @if($errors->has('order_id'))
                            <em class="invalid-feedback">
                                {{ $errors->first('order_id') }}
                            </em>
                        @endif
                    </div>
                    
                </div>
                   
            <input type="hidden" id="client_id" name="client_id" />
               
            </div>
            <div>
                <input class="btn btn-danger" type="submit" value="{{ trans('global.save') }}">
            </div>
        </form>


    </div>
</div>
<style>
.ui-autocomplete 
{
  max-height: 300px;
  overflow-y: auto;
}
</style>
@endsection
@section('scripts')
@parent
<script type="text/javascript">
            $(function () {
                $('#due_date').datepicker({
                     dateFormat: 'yy-mm-dd'
                });
				$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				}
				});
				$( "#from" ).autocomplete({
					selectFirst: true,
					maxLength: 8,
					source : function(request, response) {
						$.post("{{ route('admin.client.search') }}", request, response);
					},
					
					response: function(event, ui) {
						if (ui.content.length === 0) {
							$("#autocomplete_err").show();
							$( "#from" ).val('');
							$("#autocomplete_err").text("Client not found. You need to create client.");
						} else {
							$("#autocomplete_err").empty();
						}
					},
					select: function( event, ui) {
						$( "#client_id" ).val( ui.item.value );
						$( "#from" ).val( ui.item.label );
						//orders(ui.item.value);
						return false;
					}
                     
					
					
					
					
				});
				
				// function orders(client){
		  
					
					// $.ajaxSetup({
							// headers: {
								// 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
							// }
					// });
					// $.ajax({
						// url: "/admin/orders/ajax/"+client,
						// type: "post",
						// data: { client : client },
						// success: function(data){
							
							// console.log(data);
						// }
						
					// });
					
					
				  // }
				
            });
            
</script>
@endsection