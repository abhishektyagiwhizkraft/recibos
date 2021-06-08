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
		<td colspan="5" style="text-align:center;padding-bottom: 10px;">			
				<p style="font-family: sans-serif; font-size: 18px; margin-bottom: 0px; margin-top: 0;"><strong>LUMINO-TEC</strong></p>
				<p style="margin-top: 0px; margin-bottom: 0px; font-size: 12px;">Barrio Montevideo 7 Ave. 4 Calle</p>	
				<p style="margin-top: 0px; margin-bottom: 0px; font-size: 12px;">Tel. 2617-1009</p>		   			
		</td>	
	</tr>
</table>
<table style="margin:0 auto;border-spacing: 0;font-family: Helvetica Neue,sans-serif;" width="100%"  cellspacing="0">
	<tr>
		<td colspan="6" style="border-top: 2px solid #000;height: 20px;text-align: center;padding-top: 5px;padding-bottom: 40px;font-size: 16px;"><strong>Reporte Comisiones</strong></td>
	</tr>
	<tr>
		<td colspan="6" style="padding-bottom: 4px;font-size: 12px;border-bottom: 1px solid #000;padding-left: 15px;">
			<span>Reporte del {{ $start }} hasta {{ $end }}</span>
		</td>
	</tr>
	<tr>
		<td style="padding-bottom: 4px;font-size: 14px;border-bottom: 1px solid #000;padding-left: 15px;padding-top: 4px;color: #891325;width: 20%;">
			<span><strong>Vendedor</strong></span>
		</td>
		<td style="padding-bottom: 4px;font-size: 14px;border-bottom: 1px solid #000;padding-left: 10px;padding-top: 4px;color: #891325;width: 15%;">
			<span></span>
		</td>
		<td style="padding-bottom: 4px;font-size: 14px;border-bottom: 1px solid #000;padding-top: 4px;color: #891325;width: 15%;text-align: center;">
			<span><strong>Contado</strong></span>
		</td>
		<td style="padding-bottom: 4px;font-size: 14px;border-bottom: 1px solid #000;padding-top: 4px;color: #891325;text-align: center;width: 15%;">
			<span><strong>Credito</strong></span>
		</td>
		<td style="padding-bottom: 4px;font-size: 14px;border-bottom: 1px solid #000;padding-left: 0;padding-top: 4px;color: #891325;text-align: center;width: 15%;">
			<span><strong>Total</strong></span>
		</td>
		<td style="padding-bottom: 4px;font-size: 14px;border-bottom: 1px solid #000;padding-left: 0;padding-top: 4px;color: #891325;text-align: center;width: 20%;">
			<span><strong>Comisión</strong></span>
		</td>
	</tr>
	@foreach($result as $data)
	<tr>
		<td style="font-size: 12px;padding-left: 15px;padding-bottom: 4px;width: 20%;padding-top: 4px;">
			<span>{{ $data['name'] }}</span>
		</td>
		<td style="padding-bottom: 4px;font-size: 12px;padding-left: 10px;padding-top: 4px;width: 15%;">
			<span></span>
		</td>
		<td style="padding-bottom: 4px;font-size: 12px;padding-top: 4px;width: 15%;text-align: right;">
			<span>{{ $data['contado'] }}</span>
		</td>
		<td style="padding-bottom: 4px;font-size: 12px;padding-top: 4px;text-align: right;width: 15%;">
			<span>{{ $data['credito'] }}</span>
		</td>
		<td style="padding-bottom: 4px;font-size: 12px;padding-left: 0;padding-top: 4px;text-align: right;width: 15%;">
			<span>{{ $data['total'] }}</span>
		</td>
		<td style="padding-bottom: 4px;font-size: 12px;padding-left: 0;padding-top: 4px;text-align: right;width: 20%;padding-right: 15px;">
			<span>{{ $data['commision'] }}</span>
		</td>
	</tr>
	@endforeach
	<tr>
		<td style="font-size: 14px;padding-left: 15px;padding-bottom: 4px;width: 20%;">
			<span></span>
		</td>
		<td style="font-size: 14px;padding-left: 10px;padding-bottom: 4px;width: 15%;text-align: right;padding-right: 15px;">
			<span><strong>Totales:</strong></span>
		</td>
		<td style="font-size: 14px;padding-bottom: 4px;width: 15%;text-align: right;border-top: 1px solid #000;">
			<span><strong>{{ number_format($result['contado_total'], 2, '.', ',') }}</strong></span>
		</td>
		<td style="font-size: 14px;padding-bottom: 4px;text-align: right;width: 15%;border-top: 1px solid #000;">
			<span><strong>{{ number_format($result['credito_total'], 2, '.', ',') }}</strong></span>
		</td>
		<td style="font-size: 14px;padding-left: 0;padding-bottom: 4px;text-align: right;width: 15%;border-top: 1px solid #000;">
			<span><strong>{{ number_format($result['total_total'], 2, '.', ',') }}</strong></span>
		</td>
		<td style="font-size: 14px;padding-left: 0;padding-bottom: 4px;text-align: right;width: 20%;padding-right: 15px;border-top: 1px solid #000;">
			<span><strong>{{ number_format($result['comision_total'], 2, '.', ',') }}</strong></span>
		</td>
	</tr>
</table>


</body>
</html>
