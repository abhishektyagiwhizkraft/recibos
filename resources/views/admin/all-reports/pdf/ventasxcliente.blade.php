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
		<td colspan="6" style="border-top: 2px solid #000;height: 20px;text-align: center;padding-top: 5px;padding-bottom: 10px;font-size: 16px;"><strong>Reporte Ventas x Cliente</strong></td>
	</tr>
	<tr>
		<td colspan="6" style="padding-bottom: 4px;font-size: 12px;border-bottom: 1px solid #000;padding-left: 15px;">
			<span>Reporte del {{ $start }} hasta {{ $end }}</span>
		</td>
	</tr>
	<tr>
		<td style="padding-bottom: 4px;font-size: 14px;border-bottom: 1px solid #000;padding-left: 15px;padding-top: 4px;color: #891325;width: 10%;">
			<span><strong>Codigo</strong></span>
		</td>		
		<td colspan="2" style="padding-bottom: 4px;font-size: 14px;border-bottom: 1px solid #000;padding-top: 4px;color: #891325;width: 25%;">
			<span><strong>Cliente</strong></span>
		</td>
		<td style="padding-bottom: 4px;font-size: 14px;border-bottom: 1px solid #000;padding-top: 4px;color: #891325;text-align: left;width: 22%;padding-left: 25px; padding-right: 15px;">
			<span><strong>Contado</strong></span>
		</td>
		<td style="padding-bottom: 4px;font-size: 14px;border-bottom: 1px solid #000;padding-left: 25px; padding-right: 15px;padding-top: 4px;color: #891325;text-align: left;width: 22%;">
			<span><strong>Credito</strong></span>
		</td>		
		<td style="padding-bottom: 4px;font-size: 14px;border-bottom: 1px solid #000;padding-left: 25px; padding-right: 15px;padding-top: 4px;color: #891325;text-align: left;width: 21%;">
			<span><strong>Total</strong></span>
		</td>
	</tr>
	<tr>
		<td style="font-size: 12px;padding-left: 15px;padding-bottom: 4px;width: 10%;padding-top: 4px;">
			<span>{{ $result['code'] }}</span>
		</td>
		<td colspan="2" style="padding-bottom: 4px;font-size: 12px;padding-top: 4px;width: 25%;">
			<span>{{ $result['name'] }}</span>
		</td>
		<td style="padding-bottom: 4px;font-size: 12px;padding-top: 4px;text-align: left;width: 22%;padding-right: 15px;padding-left: 25px;">
			<span>{{ number_format($result['contado'], 2, '.', ',') }}</span>
		</td>
		<td style="padding-bottom: 4px;font-size: 12px;padding-right: 15px;padding-left: 25px;padding-top: 4px;text-align: left;width: 22%;">
			<span>{{ number_format($result['credito'], 2, '.', ',') }}</span>
		</td>
		<td style="padding-bottom: 4px;font-size: 12px;padding-right: 15px;padding-left: 25px;padding-top: 4px;text-align: left;width: 21%;">
			<span>{{ number_format($result['total'], 2, '.', ',') }}</span>
		</td>		
	</tr>		
	<tr>
		<td colspan="3" style="font-size: 14px;padding-left: 10px;padding-bottom: 4px;width: 44%;text-align: right;padding-right: 15px;">
			<span><strong>Totales:</strong></span>
		</td>
		<td style="font-size: 14px;padding-bottom: 4px;width: 22%;text-align: left;border-top: 1px solid #000;padding-right: 15px;padding-left: 25px;">
			<span><strong>{{ number_format($result['contado'], 2, '.', ',') }}</strong></span>
		</td>
		<td style="font-size: 14px;padding-bottom: 4px;text-align: left;width: 22%;border-top: 1px solid #000;padding-right: 15px;padding-left: 25px;">
			<span><strong>{{ number_format($result['credito'], 2, '.', ',') }}</strong></span>
		</td>
		<td style="font-size: 14px;padding-right: 15px;padding-left: 25px;padding-bottom: 4px;text-align: left;width: 22%;border-top: 1px solid #000;">
			<span><strong>{{ number_format($result['total'], 2, '.', ',') }}</strong></span>
		</td>		
	</tr>
</table>
</body>
</html>
