<!DOCTYPE html>
<html>
<head></head>
<body>
<div style="margin:0 auto;bordera: 1px solid #BDBDBD; max-width: 900px; font-family: Helvetica Neue,sans-serif;">
<table style="margin:0 auto;border-spacing: 0;font-family: Helvetica Neue,sans-serif;" width="100%"  cellspacing="0">
	<tr>
		<td colspan="1" style="padding-bottom: 10px;    width: 10%;">
			<img src="http://dev.recibos.ledison.shop/images/logo.png" width="170px"/>
		</td>
		<td colspan="6" style="text-align:center;padding-bottom: 10px;">			
				<p style="font-family: sans-serif; font-size: 18px; margin-bottom: 0px; margin-top: 0;"><strong>LUMINO-TEC</strong></p>
				<p style="margin-top: 0px; margin-bottom: 0px; font-size: 12px;">lumino-tec@hotmail.com</p>	
				<p style="margin-top: 0px; margin-bottom: 0px; font-size: 12px;">Tel. 2617-1009</p>		   			
		</td>	
	</tr>
</table>
<table style="margin:0 auto;border-spacing: 0;font-family: Helvetica Neue,sans-serif;" width="100%"  cellspacing="0">
	<tr>
		<td colspan="7" style="border-top: 2px solid #000;height: 20px;text-align: center;padding-top: 5px;border-bottom: 1px solid #000;padding-bottom: 40px;font-size: 14px;"><strong>Reporte de Dias de No Compra x Cliente</strong></td>
	</tr>	
	<tr>
		<td style="padding-bottom: 4px;font-size: 14px;border-bottom: 1px solid #000;padding-left: 15px;padding-top: 4px;color: #891325;width: 10%;">
			<span><strong>Codigo</strong></span>
		</td>
		<td style="padding-bottom: 4px;font-size: 14px;border-bottom: 1px solid #000;padding-left: 0;padding-top: 4px;color: #891325;width: 30%;">
			<span><strong>Nombre</strong></span>
		</td>
		<td style="padding-bottom: 4px;font-size: 14px;border-bottom: 1px solid #000;padding-top: 4px;color: #891325;width: 10%;text-align: center;">
			<span><strong>Ult. Factura</strong></span>
		</td>
		<td style="padding-bottom: 4px;font-size: 14px;border-bottom: 1px solid #000;padding-top: 4px;color: #891325;text-align: center;width: 10%;">
			<span><strong>Ult. Fecha</strong></span>
		</td>
		<td style="padding-bottom: 4px;font-size: 14px;border-bottom: 1px solid #000;padding-left: 0;padding-top: 4px;color: #891325;text-align: center;width: 10%;">
			<span><strong>Dias</strong></span>
		</td>		
		<td style="padding-bottom: 4px;font-size: 14px;border-bottom: 1px solid #000;padding-left: 0;padding-top: 4px;color: #891325;text-align: center;width: 40%;">
			<span><strong>Vendedor</strong></span>
		</td>
	</tr>
	@php
	$c = 0;
	@endphp
	@foreach($result as $data)
	<tr>
		<td style="font-size: 12px;padding-left: 15px;padding-bottom: 4px;width: 10%;padding-top: 4px;">
			<span>{{ $data['client_id'] }}</span>
		</td>
		<td style="padding-bottom: 4px;font-size: 12px;padding-top: 4px;width: 30%;">
			<span>{{ $data['client_name'] }}</span>
		</td>
		<td style="padding-bottom: 4px;font-size: 12px;padding-top: 4px;text-align: center;width: 10%;">
			<span>{{ $data['ult_factura'] }}</span>
		</td>
		<td style="padding-bottom: 4px;font-size: 12px;padding-left: 0;padding-top: 4px;text-align: center;width: 10%;">
			<span>{{ $data['date'] }}</span>
		</td>
		<td style="padding-bottom: 4px;font-size: 12px;padding-left: 0;padding-top: 4px;text-align: center;width: 10%;">
			<span>{{ $data['days'] }}</span>
		</td>
		<td style="padding-bottom: 4px;font-size: 12px;padding-top: 4px;text-align: center;width: 40%;">
			<span>{{ $data['vendor'] }}</span>
		</td>
	</tr>
	@php
	 $c+= 1
	@endphp
	@endforeach
	
	<tr>
		<td colspan="6" style="font-size: 14px;padding-left: 15px;padding-bottom: 4px;width: 5%;padding-top: 15px;">
			<span>Total: {{ $c }}</span>
		</td>		
	</tr>	
</table>
</body>
</html>
