<!doctype html>
<html lang="en">
<head>   </head>
<body>
@php
    \Carbon\Carbon::setLocale('es');
@endphp
<div style="border: 1px solid #bdbdbd; max-width: 900px; margin: 5em auto;  padding: 40px; font-family: -apple-system,BlinkMacSystemFont,Segoe UI,Roboto,Helvetica Neue,Arial,sans-serif,Apple Color Emoji,Segoe UI Emoji,Segoe UI Symbol;">
<table style=" border: 2px solid #3f3f3f; width:100%; padding: 10px 10px;"> 
    <tr>
        <td colspan="4">
            <h1 style="color: #0463a7; text-align: center; font-size: 30px;">Recibo de Pago Provisiona</h1>
        </td>
    </tr>    
    <tr>
        <td colspan="2" rowspan="2"><img src="{{asset('images/logo.png')}}" width="340px"></td>
        <td style="color: #3f3f3f;font-size: 16px; border-left: 10px solid transparent;" colspan="2">
            <div style="border-bottom: 2px solid #3f3f3f;line-height: 40px;">
                <strong>No.</strong>&nbsp;{{ $receipt->id }}
            </div>
        </td>
    </tr>
    <tr>        
        <td style="color: #3f3f3f;font-size: 16px; border-left: 10px solid transparent;" colspan="2">
            <div style="border-bottom: 2px solid #3f3f3f;line-height: 40px;">
                <strong>{{ trans('cruds.receipt.invoiceno') }}.</strong>&nbsp;&nbsp;&nbsp; #{{ $receipt->invoice_id }}
            </div>
        </td>
    </tr>
    <tr><td style="padding: 15px 0;" colspan="4"></td></tr> 
    <tr>
        <td colspan="3" style="padding-right: 20px; font-size: 16px; line-height: 40px; width: 70%;">
            <div style="border-bottom: 2px solid #3f3f3f; color: #3f3f3f;">
                <strong>{{ trans('cruds.receipt.date') }}</strong>&nbsp;&nbsp;&nbsp; {{ ucwords($receipt->created_at->translatedFormat('l, jS F Y')) }}
            </div>
        </td>   
        <td style="border: 2px solid #3f3f3f; padding: 12px 0; width: 30%;"rowspan="3"></td>    
    </tr>
    <tr>
        <td colspan="3" style="padding-right: 20px; font-size: 16px; line-height: 40px; width: 70%;">
            <div style="border-bottom: 2px solid #3f3f3f; color: #3f3f3f;">
                <strong>{{ trans('cruds.receipt.from') }}</strong>&nbsp;&nbsp;&nbsp; {{ $receipt->invoice->from }}
            </div>
        </td>           
    </tr>
    <tr><td style="padding: 8px 0;" colspan="3"></td></tr> 
    <tr>
        <td colspan="4" style="border-bottom: 2px solid #3f3f3f; font-size: 16px; line-height: 40px; color: #3f3f3f;">${{ $receipt->invoice->amount }} - {{ucwords(\App\Invoices::numberToWord($receipt->invoice->amount))}}<strong style="float: right;">DOLARES</strong></td>     
    </tr>    
    <tr><td style="padding: 20px 0; " colspan="4"></td></tr>
    <tr style="">
        <td style="padding: 0px; border-right: 0;padding-right: 20px;" colspan="2">
            <div style="padding-right: 0;border: 2px solid #3f3f3f; margin-top: -10px;">
                <div style=" padding-left: 20px; padding: 20px 20px;">
                    <strong style="color: #3f3f3f;">ACCT</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <span style="color: #3f3f3f;"></span>
                </div>
            </div>
        </td>   
        <td colspan="2" style="border-bottom: 2px solid #3f3f3f; font-size: 16px; line-height: 40px; border-top: 2px solid #3f3f3f;">
            <div style=" color: #3f3f3f;">
                <strong>{{ trans('cruds.receipt.from') }}</strong>&nbsp;&nbsp; {{ $receipt->invoice->from }}
            </div>
            <div style="border-top: 2px solid #3f3f3f; color: #3f3f3f;">
                <strong>TO</strong>&nbsp;&nbsp; 
            </div>
        </td>
    </tr>   
    <tr>
        <td style="padding: 0px; border-right: 0;padding-right: 20px;" colspan="2">
            <div style="padding-right: 0;border: 2px solid #3f3f3f; margin-top: -22px; border-top:0px;">
                <div style=" padding-left: 20px; padding: 20px 20px 22px 20px;">
                    <strong style="color: #3f3f3f;">{{ trans('cruds.receipt.paid') }}</strong>&nbsp;&nbsp;&nbsp;
                    <span style="color: #3f3f3f;">${{ $receipt->invoice->amount }}</span>
                </div>
            </div>
        </td>       
        <td colspan="2" style="border-bottom: 2px solid #3f3f3f; font-size: 16px; line-height: 40px; width: 50%;">
            <div style=" color: #3f3f3f;">
                <strong>{{ trans('cruds.receipt.receivedby') }}</strong>&nbsp;&nbsp; {{auth()->user()->name}}
            </div>
        </td>
    </tr>
    <tr>
        <td style="padding: 0px; border-right: 0;padding-right: 20px;" colspan="2">
            <div style="padding-right: 0;border: 2px solid #3f3f3f; margin-top: -10px; border-top:0px;">
                <div style=" padding-left: 20px; padding: 24px 20px 22px 20px;">
                    <strong style="color: #3f3f3f;">{{ trans('cruds.receipt.due') }}</strong>&nbsp;&nbsp;&nbsp;
                    <span style="color: #3f3f3f;">12345</span>
                </div>
            </div>
        </td>   
        <td colspan="2" style="border-bottom: 2px solid #3f3f3f; font-size: 16px; line-height: 40px;">       
            <div style=" color: #3f3f3f;">
                <strong>{{ trans('cruds.receipt.signature') }}</strong>&nbsp;&nbsp; ...
            </div>
        </td>
    </tr>
    <tr><td style="padding: 15px 0;" colspan="4"></td></tr> 
    <tr>
        <td colspan="4" style="border: 2px solid #3f3f3f; font-size: 16px; line-height: 25px; color: #3f3f3f; text-align: center;">Bo. Montevideo 7ave. 4 calle, El Progreso, Yoro<br><strong>Mail:</strong>&nbsp;&nbsp;ventas@ledisonhn.com, <strong>Phone:</strong>&nbsp;&nbsp;2617-1009<br><strong>RTN:</strong>&nbsp;&nbsp;05018012477539</td>        
    </tr>
</table>    
</body>
</html>