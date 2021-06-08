<!doctype html>
<html lang="en">
<head>	 </head>
<body>
	@php
    \Carbon\Carbon::setLocale('es');
@endphp
<div style="bordera: 1px solid #bdbdbd; max-width: 850px; margin: 4em auto;  padding: 40px; font-family: -apple-system,BlinkMacSystemFont,Segoe UI,Roboto,Helvetica Neue,Arial,sans-serif,Apple Color Emoji,Segoe UI Emoji,Segoe UI Symbol;">
<table style=" border: 0px solid #3f3f3f; width:100%;">	
	<tr>
		<td colspan="2"><img src="{{asset('images/logo.png')}}" width="340px"></td>
		<td style="color: #3f3f3f;font-size: 16px;" colspan="2">
			<div style="text-align: right; line-height: 30px;">
				<strong>TEL.</strong>:&nbsp;&nbsp;&nbsp; 2617-1009<br/><span style="font-size: 14px; font-weight: 600;">Barrio Montevideo, 7 Ave., Calle El Progreso, Yoro</span>
			</div>
		</td>		
	</tr>
	<tr>
		<td style="color: #3f3f3f;font-size: 16px;">
			<div style=" line-height: 30px; width:200px;">
				<strong>R.T.N</strong>:&nbsp;&nbsp;&nbsp; 05018012477539 
			</div>
		</td>		
		<td style="color: #3f3f3f;font-size: 16px; width:300px; text-align:center;" colspan="2">
			<div style="padding-left: 95px; aborder-bottom: 2px solid #3f3f3f;line-height: 30px; text-align:center; ">
				<strong>HOJA DE DEVOLUCIONES</strong>
			</div>
		</td>
		<td style="color: #3f3f3f;font-size: 16px; width:170px;">
			<div style=" border-bottom: 2px solid #3f3f3f;line-height: 30px; margin-left:200px; ">
				<strong>No.</strong>:&nbsp;&nbsp;&nbsp; {{ $order }}
			</div>
		</td>		
	</tr>
	<tr><td style="padding: 30px 0;" colspan="4"></td></tr>	
	<tr>
		<td colspan="3" style="padding-right: 5px; font-size: 16px; line-height: 40px; width: 75%;">
			<div style="border-bottom: 2px solid #3f3f3f; color: #3f3f3f; width:500px;">
				<strong>Cliente</strong>:&nbsp;&nbsp;&nbsp; {{ $items[0]->client_name }}
			</div>
		</td>
		<td style="font-size: 16px; line-height: 40px; width:350px;">
			<div style="border-bottom: 2px solid #3f3f3f; color: #3f3f3f;">
				<strong>Fecha</strong>:&nbsp;&nbsp;&nbsp; {{ ucwords($items[0]->created_at->translatedFormat('l, jS F Y')) }}
			</div>
		</td>
		<td style="font-size: 16px; line-height: 40px; width:350px;">
			<div style="border-bottom: 2px solid #3f3f3f; color: #3f3f3f;">
				<strong>Tipo</strong>: Artículo reemplazado
			</div>
		</td>
		
	</tr>
	<tr>
		<td colspan="4" style=" font-size: 16px; line-height: 40px;">
			<div style="border-bottom: 2px solid #3f3f3f; color: #3f3f3f;">
				<strong>Ejecutivo de Ventas</strong>:&nbsp;&nbsp;{{ \App\User::find($items[0]->received_by)->name}}
			</div>
		</td>			
	</tr>
	<tr><td style="padding: 10px 0;" colspan="3"></td></tr>		
</table>
<table style="width:100%;" cellpadding="0" cellspacing="0">	
	<tr style="background-color: #dadada; height: 50px;">		
		<td style="color: #3f3f3f;font-size: 16px; width: 25%; border: 1px solid #000 !important; border-left: 2px solid #000 !important; border-top: 2px solid #000 !important;">
			<div style="text-align: center; line-height: 30px; padding-bottom:8px;">
				<strong>CANTIDAD</strong>
			</div>
		</td>		
		<td style="color: #3f3f3f;font-size: 16px; border: 1px solid #000 !important; border-right: 2px solid #000 !important; border-top: 2px solid #000 !important;" colspan="2">
			<div style="text-align: center; line-height: 30px; padding-bottom:8px;">
				<strong>DETALLE</strong>
			</div>
		</td>		
	</tr>
	@foreach($items as $item)
	<tr style="height: 35px;">
		<td style="color: #3f3f3f; font-size: 16px; width: 25%; border: 1px solid #000 !important; border-left: 2px solid #000 !important; ">
			<div style="text-align: center; line-height: 30px; padding-bottom:8px;">
				{{$item->qty}}
			</div>
		</td>		
		<td style="color: #3f3f3f; font-size: 16px; border: 1px solid #000 !important; border-right: 2px solid #000 !important;" colspan="2">
			<div style="text-align: center; line-height: 30px; padding-bottom:8px;">
				<strong>{{$item->item}}</strong> - {{$item->fault}}
			</div>
		</td>	
	</tr>
	@endforeach
	

	<tr><td style="padding: 8px 0;" colspan="3"></td></tr>	
	<tr>
		<td style="color: #3f3f3f; font-size: 16px; width: 60%; padding-right: 30px;" colspan="2">
			<div style="text-align: left; line-height: 30px; height: 70px; width: 100%; border: 2px solid #000 !important; padding: 7px 10px; padding-right: 0;">
				<strong>Observaciones</strong>:&nbsp;&nbsp;&nbsp;&nbsp; 
			</div>
		</td>		
		<td style="text-align: center; color: #3f3f3f; font-size: 16px; border-bottom: 0px solid #000 !important;">
			<div style=" text-align: center;  border-bottom: 2px solid #000 !important;">
					<div style="text-align: center; font-weight: 600; font-size: 18px; margin-top:20px; margin-bottom:15px; "> </div>
			</div>
		
		</td>	
	</tr>


</table>
</div>	
</body>
</html>