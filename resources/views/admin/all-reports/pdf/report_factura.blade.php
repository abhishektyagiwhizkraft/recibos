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
		<td colspan="8" style="text-align:center;padding-bottom: 10px;">			
			<p style="font-family: sans-serif; font-size: 18px; margin-bottom: 0px; margin-top: 0;"><strong>LUMINO-TEC</strong></p>
			<p style="margin-top: 0px; margin-bottom: 0px; font-size: 12px;">Barrio Montevideo 7 Ave. 4 Calle</p>	
			<p style="margin-top: 0px; margin-bottom: 0px; font-size: 12px;">Tel. 2617-1009</p>		   			
		</td>	
	</tr>
</table>
<table style="margin:0 auto;border-spacing: 0;font-family: Helvetica Neue,sans-serif;" width="100%"  cellspacing="0">
	<tr>
		<td colspan="9" style="border-top: 2px solid #000;height: 20px;text-align: center;padding-top: 5px;padding-bottom: 10px;font-size: 16px;"><strong>Reporte Ventas x Factura</strong></td>
	</tr>
	<tr>
		<td colspan="3" style="padding-bottom: 4px;font-size: 12px;border-bottom: 1px solid #000;padding-left: 15px; text-align: left;">
			<span>Reporte del {{ $start }} hasta {{ $end }}</span>
		</td>
		<td colspan="3" style="padding-bottom: 4px;font-size: 12px;border-bottom: 1px solid #000;padding-right: 15px; text-align: center;">
			<span>Tipo: 
			 @if($tipo == 'solocredito')
			 Solo Credito
			 @elseif($tipo == 'solocontado')
			 Solo Contado
			 @else
			 Contado y Credito
			 @endif
			</span>
		</td>
		<td colspan="3" style="padding-bottom: 4px;font-size: 12px;border-bottom: 1px solid #000;padding-right: 15px; text-align: right;">
			<span>Estado: {{ ucfirst($estado) }}</span>
		</td>
	</tr>
	<tr>
		<td style="padding-bottom: 4px;font-size: 14px;border-bottom: 1px solid #000;padding-left: 15px;padding-top: 4px;color: #891325;width: 10%;">
			<span><strong>Facha </strong></span>
		</td>		
		<td style="padding-bottom: 4px;font-size: 14px;border-bottom: 1px solid #000;padding-top: 4px;color: #891325;text-align: center;width: 8%;">
			<span><strong>Fact. #</strong></span>
		</td>
		<td style="padding-bottom: 4px;font-size: 14px;border-bottom: 1px solid #000;padding-top: 4px;color: #891325;text-align: left;width: 24%;">
			<span><strong>Cliente</strong></span>
		</td>
		<td style="padding-bottom: 4px;font-size: 14px;border-bottom: 1px solid #000;padding-left: 0;padding-top: 4px;color: #891325;text-align: center;width: 8%;">
			<span><strong>F. Pago</strong></span>
		</td>
		<td style="padding-bottom: 4px;font-size: 14px;border-bottom: 1px solid #000;padding-left: 0;padding-top: 4px;color: #891325;text-align: center;width: 10%;">
			<span><strong>Tipo</strong></span>
		</td>		
		<td style="padding-bottom: 4px;font-size: 14px;border-bottom: 1px solid #000;padding-left: 0;padding-top: 4px;color: #891325;text-align: center;width: 10%;">
			<span><strong>Total Sin ISV</strong></span>
		</td>
		<td style="padding-bottom: 4px;font-size: 14px;border-bottom: 1px solid #000;padding-left: 0;padding-top: 4px;color: #891325;text-align: center;width: 10%;">
			<span><strong>Total</strong></span>
		</td>
		<td style="padding-bottom: 4px;font-size: 14px;border-bottom: 1px solid #000;padding-left: 0;padding-top: 4px;color: #891325;text-align: center;width: 10%;">
			<span><strong>Saldo</strong></span>
		</td>
		<td style="padding-bottom: 4px;font-size: 14px;border-bottom: 1px solid #000;padding-left: 0;padding-top: 4px;color: #891325;text-align: left;width: 10%;">
			<span><strong>Vendedor</strong></span>
		</td>
	</tr>
	<tr>
		<td colspan="9" style="padding: 5px 0 5px 15px;font-size: 13px;">
			<span><strong>LUMINO-TEC</strong></span>
		</td>		
	</tr>
	@php 
	$without_isv = 0;
	$total = 0;
	@endphp
	@foreach($result as $data)
	<tr>
		<td style="padding-bottom: 4px;font-size: 12px;padding-left: 15px;padding-top: 4px;width: 10%;">
			<span>{{ $data['date'] }}</span>
		</td>		
		<td style="padding-bottom: 4px;font-size: 12px;padding-top: 4px;text-align: center;width: 8%;">
			<span>{{ $data['num'] }}</span>
		</td>
		<td style="padding-bottom: 4px;font-size: 12px;padding-top: 4px;text-align: left;width: 24%;">
			<span>{{ $data['client'] }}</span>
		</td>
		<td style="padding-bottom: 4px;font-size: 12px;padding-left: 0;padding-top: 4px;text-align: center;width: 8%;">
			<span></span>
		</td>
		<td style="padding-bottom: 4px;font-size: 12px;padding-left: 0;padding-top: 4px;text-align: center;width: 10%;">
			<span>{{ $data['tipo'] }}</span>
		</td>		
		<td style="padding-bottom: 4px;font-size: 12px;padding-left: 0;padding-top: 4px;text-align: center;width: 10%;">
			<span>{{ number_format($data['total_sin_isv'], 2, '.', ',') }}</span>
		</td>
		<td style="padding-bottom: 4px;font-size: 12px;padding-left: 0;padding-top: 4px;text-align: center;width: 10%;">
			<span>{{ number_format($data['total'], 2, '.', ',') }}</span>
		</td>
		<td style="padding-bottom: 4px;font-size: 12px;padding-left: 0;padding-top: 4px;text-align: center;width: 10%;">
			<span>{{ number_format($data['saldo'], 2, '.', ',') }}</span>
		</td>
		<td style="padding-bottom: 4px;font-size: 12px;padding-left: 0;padding-top: 4px;text-align: left;width: 10%;">
			<span>{{ $data['vendor'] }}</span>
		</td>
	</tr>
	@php
		$without_isv+= $data['total_sin_isv'];
		$total+= $data['total'];
	@endphp
	@endforeach
	<tr>
		<td style="padding-bottom: 4px;font-size: 12px;padding-left: 15px;padding-top: 4px;width: 10%;">
			<span></span>
		</td>		
		<td style="padding-bottom: 4px;font-size: 12px;padding-top: 4px;text-align: center;width: 8%;">
			<span></span>
		</td>
		<td style="padding-bottom: 4px;font-size: 12px;padding-top: 4px;text-align: left;width: 24%;">
			<span></span>
		</td>
		<td style="padding-bottom: 4px;font-size: 12px;padding-left: 0;padding-top: 4px;text-align: center;width: 8%;">
			<span></span>
		</td>
		<td style="padding-bottom: 4px;font-size: 12px;padding-left: 0;padding-top: 4px;text-align: center;width: 10%;">
			<span></span>
		</td>		
		<td style="font-size: 13px;padding: 5px 0 5px 0;text-align: center;width: 10%;border-top: 1px solid #000;">
			<span><strong>{{ number_format($without_isv, 2, '.', ',') }}</strong></span>
		</td>
		<td style="font-size: 13px;padding: 5px 0 5px 0;text-align: center;width: 10%;border-top: 1px solid #000;">
			<span><strong>{{ number_format($total, 2, '.', ',') }}</strong></span>
		</td>
		<td style="font-size: 13px;padding: 5px 0 5px 0;text-align: center;width: 10%;border-top: 1px solid #000;">
			<span><strong>{{ number_format($total, 2, '.', ',') }}</strong></span>
		</td>
		<td style="padding-bottom: 4px;font-size: 12px;padding-left: 0;padding-top: 4px;text-align: left;width: 10%;">
			<span></span>
		</td>
	</tr>
	
</table>
</body>
</html>
