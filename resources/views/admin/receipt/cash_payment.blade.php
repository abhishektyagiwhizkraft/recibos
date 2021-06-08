@extends('layouts.admin')
@section('content')

 
<div class="card card-warning imm_invoice_list">
    <div class="card-header">
        {{ trans('cruds.receipt.cash_title') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-User">
                <thead>
                    <tr>
                        <th width="10">
                            {{ trans('global.sno') }}
                        </th>
                        <th>
                            {{ trans('cruds.receipt.invoiceno') }}
                        </th>
                        <th>
                            {{ trans('cruds.invoice.table.from') }}
                        </th>
                        <th>
                            {{ trans('cruds.receipt.amount') }}
                        </th>
                         <th>
                            {{ trans('cruds.receipt.date') }}
                        </th>
                        <th>
                            {{ trans('global.actions') }}
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($receipts as $key => $receipt)
                        <tr data-entry-id="{{ $receipt->id }}">
                            <td>
                                
                            </td>
                            <td>
                                {{ $receipt->invoice_id ?? '' }}
                            </td>
                            <td>
                                {{ $receipt->invoice->from ?? '' }}
                            </td>
                            <td>

                                L {{ $receipt->invoice->amount ?? '' }}
                            </td>
                            <td>
                                {{ date('D, M Y', strtotime($receipt->created_at)) ?? '' }}
                            </td>
                            
                            <td>
                               
                                
                                <a href="{{ route('admin.receipts.authorize',$receipt->id) }}" class="btn btn-xs btn-success">
                                    {{ trans('cruds.receipt.authorize') }}
                                </a>
                               

                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>


    </div>
</div>
 
@endsection
@section('scripts')
@parent
<script>
$(function () {
  
  $('.datatable-User').DataTable({
    "fnRowCallback" : function(nRow, aData, iDisplayIndex){
                $("td:first", nRow).html(iDisplayIndex +1);
               return nRow;
            },
  });
});
</script>
@endsection
