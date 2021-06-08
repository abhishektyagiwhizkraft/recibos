@extends('layouts.admin')
@section('content')<style>.loader img, .loader p{	display:none;    width: 70px;    position: absolute;    left: 0;    right: 0;    margin: auto;    top: 45%;    z-index: 999;}.loader p{	    top: 56% !important;}.blur-filter {    -moz-filter: blur(2px);    -o-filter: blur(2px);    backdrop-filter: blur(1px);    -ms-filter: blur(2px);    position: absolute;    z-index: 99;    filter: blur(2px) !important;    -webkit-filter: blur(6px) !important;    width: 100%;    height: 100%;}.loader_imm {    position: fixed;    z-index: 9999;    left: 0;    right: 0;    margin: 0 auto;    top: 0;    height: 100%;    background: #ffffff17;}</style>
<div class="loader" >	<div class="blur"></div>	<img src="{{asset('public/images/preloader.gif')}}" alt="" class="img-fluid" >	<p>Uploading...</p></div>
<div class="card card-warning">

    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.invoice.title_singular') }}
    </div>

    <div class="card-body">
        <form id="gen_invoice" action="{{ route('admin.orders.invoice.store',$order->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-5">
				
                    <div class="form-group {{ $errors->has('invoice_no') ? 'has-error' : '' }}">
                        <label for="invoice_no">{{ trans('cruds.invoice.fields.invoice_number') }}*</label>
                        <input type="text" id="invoice_no" name="invoice_no" value="{{ $generatedData['generated_id'] }}" class="form-control"  required>
						<input type="hidden" name="number" value="{{ $generatedData['number'] }}">
						<input type="hidden" name="format" value="{{ $generatedData['format']}}">
                        @if($errors->has('invoice_no'))
                            <em class="invalid-feedback">
                                {{ $errors->first('invoice_no') }}
                            </em>
                        @endif
                        
                    </div>
                    
                    <div class="form-group {{ $errors->has('amount') ? 'has-error' : '' }}">
                        <label for="amount">{{ trans('cruds.invoice.fields.amount') }}*</label>
                        <input type="text" id="amount" name="amount" value="{{ $order->total }}" class="form-control" required>
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
                $('#gen_invoice').submit(function(){										 $(".blur").addClass("blur-filter");                     $(".loader").addClass("loader_imm");                     $(".loader_imm img").show();                     $(".loader_imm p").show();				});
				
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