@extends('layouts.client')
@section('content')
<div class="container">
<div class="row">
	<a href="{{ url('client/home') }}" class="btn btn-info ml-3 mb-2">Back</a>
</div>
<div class="card">
    <div class="card-header">
        {{ trans('cruds.invoice.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-User">
                <thead>
                    <tr>
                        <th width="10">{{ trans('global.sno') }}</th>
                        <th>{{ trans('cruds.invoice.table.id') }}</th>
						<th>Orden ID</th>
                        <th>{{ trans('cruds.invoice.table.amount') }}</th>
                        <th>Estado de pago</th>
                        <th>{{ trans('cruds.invoice.table.date') }}</th>
                        <th>Fecha de Creación</th>
                    </tr>
                </thead>
                <tbody>
				    <?php $s = 1; ?>
                    @foreach($invoices as $key => $invoice)
                        <tr>
                            <td>{{ $s }}</td>
                            <td>{{ $invoice->invoice_no ?? '' }}</td>
							<td>{{ $invoice->order_id ?? 'N/A' }}</td>
                            <td>L {{ $invoice->amount ?? '' }}</td>
                            <td>
                              @if(Helper::paid($invoice) > 0)
                                <button class="btn btn-xs btn-warning">Pendiente</button> (L {{ Helper::paid($invoice) }})
                                 
                                @else
                                <button class="btn btn-xs btn-success">Terminado</button>
                                @endif
                            </td>
                            <td>{{ date('D, d M Y', strtotime($invoice->due_date)) ?? '' }}</td>
							<td>{{ date('D, d M Y', strtotime($invoice->created_at)) ?? '' }}</td>
                          </tr>
						<?php $s++;  ?>
                    @endforeach
                </tbody>
            </table>
        </div>


    </div>
</div>

</div>
<input type="hidden" id="invoice">
<!-- Modal: modalPoll -->
@endsection
@section('scripts')
@parent
<script>
$(function () {
  
  $('.datatable-User').DataTable({
	
  });
   
});


</script>

@endsection