<!doctype html>

<html lang="en">

<head>   </head>

<body>

    @php

    \Carbon\Carbon::setLocale('es');

@endphp

<div style="bordera: 1px solid #bdbdbd; max-width: 650px; margin: 4em auto;  padding: 10px; font-family: -apple-system,BlinkMacSystemFont,Segoe UI,Roboto,Helvetica Neue,Arial,sans-serif,Apple Color Emoji,Segoe UI Emoji,Segoe UI Symbol;">

<table style=" border: 0px solid #3f3f3f; width:100%;"> 

    <tr>

        <td colspan="2"><img src="{{asset('images/logo.png')}}" width="300px"></td>

        <td style="color: #3f3f3f;font-size: 16px;" colspan="2">

            <div style="text-align: right; line-height: 30px;">

                <strong>TEL.</strong>:&nbsp;&nbsp;&nbsp; 2617-1009<br/><span style="font-size: 14px; font-weight: 600;">Barrio Montevideo, 7 Ave., Calle El Progreso, Yoro</span>

            </div>

        </td>       

    </tr>

    <tr>

        <td style="color: #3f3f3f;font-size: 16px;">

            <!--div style=" line-height: 30px; width:200px;">

			<strong>R.T.N</strong>:&nbsp;&nbsp;&nbsp; {{ $order->clients->code }} 
 
            </div-->

        </td>       

        <td style="color: #3f3f3f;font-size: 16px; width:70px; text-align:center;" colspan="2">

            <div style="padding-left: 30px; aborder-bottom: 2px solid #3f3f3f;line-height: 30px; text-align:center; ">

                <!--strong>RECIBO DE PEDIDO</strong-->

            </div>

        </td>

        <td style="color: #3f3f3f;font-size: 16px; width:170px;">

            <div style=" border-bottom: 2px solid #3f3f3f;line-height: 30px; margin-left:100px; ">

                <strong>No.</strong>:&nbsp;&nbsp;&nbsp; {{ $order->id }}

            </div>

        </td>       

    </tr>

    <tr><td style="padding: 20px 0;" colspan="4"></td></tr> 

    <tr>

        <td colspan="3" style="padding-right: 5px; font-size: 16px; line-height: 40px; width:100px;">

            <div style="border-bottom: 2px solid #3f3f3f; color: #3f3f3f; width:380px;">

                <strong>Cliente</strong>:&nbsp;&nbsp;&nbsp; {{ $order->clients->name }}

            </div>

        </td>

        <td style="font-size: 16px; line-height: 40px; width:280px;">

            <div style="border-bottom: 2px solid #3f3f3f; color: #3f3f3f;">

                <strong>Fecha</strong>:&nbsp;&nbsp;&nbsp; {{ ucwords($order->created_at->translatedFormat('l, jS F Y')) }}

            </div>

        </td>

       

        

    </tr>

    <tr>

        <td colspan="4" style=" font-size: 16px; line-height: 40px;">

            <div style="border-bottom: 2px solid #3f3f3f; color: #3f3f3f;">

                <strong>Generado por</strong>:&nbsp;&nbsp;{{ \App\User::find($order->create_by)->name}}

            </div>

        </td>           

    </tr>

    <tr>

        <td colspan="4" style=" font-size: 16px; line-height: 40px;">

            <div style="border-bottom: 2px solid #3f3f3f; color: #3f3f3f;">

                <strong>TOTAL PARCIAL</strong>:&nbsp;&nbsp;{{ $order->amount }}

            </div>

        </td>           

    </tr>

    <tr>

        <td colspan="4" style=" font-size: 16px; line-height: 40px;">

            <div style="border-bottom: 2px solid #3f3f3f; color: #3f3f3f;">

                <strong>ISV</strong>:&nbsp;&nbsp;{{ $order->tax }}

            </div>

        </td>           

    </tr>

    <tr>

        <td colspan="4" style=" font-size: 16px; line-height: 40px;">

            <div style="border-bottom: 2px solid #3f3f3f; color: #3f3f3f;">

                <strong>TOTAL</strong>:&nbsp;&nbsp;{{ $order->total }}

            </div>

        </td>           

    </tr>

    <tr>

        <td colspan="4" style=" font-size: 16px; line-height: 40px;">

            <div style="border-bottom: 2px solid #3f3f3f; color: #3f3f3f;">

                <strong>NOTAS</strong>:&nbsp;&nbsp;{{ ($order->description) ? $order->description : 'N/A' }}

            </div>

        </td>           

    </tr>

    <tr><td style="padding: 10px 0;" colspan="3"></td></tr>     

</table>

<table style="width:100%;" cellpadding="0" cellspacing="0"> 

    <tr style="background-color: #dadada; height: 50px;">       

        <td style="color: #3f3f3f;font-size: 16px; border: 1px solid #000 !important; border-left: 2px solid #000 !important; border-top: 2px solid #000 !important;">

            <div style="text-align: center; line-height: 30px; padding-bottom:8px;">

                <strong>DESCRIPCIÓN</strong>

            </div>

        </td>

        <td style="color: #3f3f3f;font-size: 16px; border: 1px solid #000 !important; border-left: 2px solid #000 !important; border-top: 2px solid #000 !important;">

            <div style="text-align: center; line-height: 30px; padding-bottom:8px;">

                <strong>CANTIDAD</strong>

            </div>

        </td>      

        <td style="color: #3f3f3f;font-size: 16px; border: 1px solid #000 !important; border-right: 2px solid #000 !important; border-top: 2px solid #000 !important;" colspan="2">

            <div style="text-align: center; line-height: 30px; padding-bottom:8px;">

                <strong>PRECIO</strong>

            </div>

        </td>       

    </tr>

    @foreach($order->products as $product)

    <tr style="height: 35px;">

        <td style="color: #3f3f3f; font-size: 16px; border: 1px solid #000 !important; border-left: 2px solid #000 !important; ">

            <div style="text-align: center; line-height: 30px; padding-bottom:8px;">

                {{$product->description}}

            </div>

        </td> 

        <td style="color: #3f3f3f; font-size: 16px; border: 1px solid #000 !important; border-left: 2px solid #000 !important; ">

            <div style="text-align: center; line-height: 30px; padding-bottom:8px;">

                {{$product->pivot->qty}}

            </div>

        </td>      

        <td style="color: #3f3f3f; font-size: 16px; border: 1px solid #000 !important; border-right: 2px solid #000 !important;" colspan="2">

            <div style="text-align: center; line-height: 30px; padding-bottom:8px;">

                 {{$product->price}}

            </div>

        </td>   

    </tr>

    @endforeach

    





</table>

</div>  

</body>

</html>