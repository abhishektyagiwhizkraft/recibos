<!DOCTYPE html>
<html>
<head></head>
<body>
<div style="margin:0 auto;bordera: 1px solid #BDBDBD; max-width: 900px; font-family: Helvetica Neue,sans-serif;">
	<table style="margin:0 auto;border-spacing: 0;font-family: Helvetica Neue,sans-serif;" width="100%" cellspacing="0">
		<tr>
			<td colspan="1" style="padding-bottom: 10px;    width: 10%;">
				<img src="http://dev.recibos.ledison.shop/images/logo.png" width="170px">
			</td>
			<td colspan="6" style="text-align:center;padding-bottom: 10px;">			
				<p style="font-family: sans-serif; font-size: 18px; margin-bottom: 0px; margin-top: 0;"><strong>LUMINO-TEC</strong></p>
				<p style="margin-top: 0px; margin-bottom: 0px; font-size: 12px;">Barrio Montevideo 7 Ave. 4 Calle</p>	
				<p style="margin-top: 0px; margin-bottom: 0px; font-size: 12px;">Tel. 2617-1009</p>		   			
			</td>	
		</tr>
	</table>

	<table style="margin:0 auto;border-spacing: 0;font-family: Helvetica Neue,sans-serif;" width="100%" cellspacing="0">
		<tr>
			<td colspan="7" style="border-top: 2px solid #000;height: 20px;text-align: center;padding-top: 5px;padding-bottom: 10px;font-size: 16px;"><strong>Reporte Diario De Ventas</strong></td>
		</tr>
		<tr>
			<td colspan="4" style="font-size: 12px;border-bottom: 1px solid #000;text-align: left;padding: 0px 0px 4px 15px;">
				<span>Reporte del {{ $start }} hasta {{ $end }}</span>
			</td>		
			<td colspan="3" style="font-size: 12px;border-bottom: 1px solid #000;text-align: right;padding: 0px 15px 4px 0px;">
				<span>Tienda: LUMINO-TEC</span>
			</td>
		</tr>
		<tr>
			<td style="font-size: 14px;border-bottom: 1px solid #000;color: #891325;padding: 4px 0px 4px 15px;">
				<span><strong>Facha</strong></span>
			</td>		
			<td style="font-size: 14px;border-bottom: 1px solid #000;color: #891325;padding: 4px 0px 4px 0px;">
				<span><strong>Facturas</strong></span>
			</td>
			<td style="font-size: 14px;border-bottom: 1px solid #000;color: #891325;text-align: right;padding: 4px 0px 4px 0px;">
				<span><strong>Gravable</strong></span>
			</td>
			<td style="font-size: 14px;border-bottom: 1px solid #000;color: #891325;text-align: right;padding: 4px 0px 4px 0px;">
				<span><strong>Exento</strong></span>
			</td>
			<td style="font-size: 14px;border-bottom: 1px solid #000;color: #891325;text-align: right;padding: 4px 0px 4px 0px;">
				<span><strong>Subtotal</strong></span>
			</td>		
			<td style="font-size: 14px;border-bottom: 1px solid #000;color: #891325;text-align: right;padding: 4px 0px 4px 0px;">
				<span><strong>ISV</strong></span>
			</td>
			<td style="font-size: 14px;border-bottom: 1px solid #000;color: #891325;text-align: right;padding: 4px 15px 4px 0px;">
				<span><strong>Total</strong></span>
			</td>		
		</tr>
		@php
		 $total_grav = 0;
		 $total_ex = 0;
		 $total_sub = 0;
		 $total_isv = 0;
		 $total_total = 0;
		 
		@endphp
		@foreach($result as $data)
		<tr>
			<td style="font-size: 12px;padding: 4px 0px 4px 15px;">
				<span>{{ date('d/m/Y',strtotime($data['date'])) }}</span>
			</td>		
			<td style="font-size: 12px;padding: 4px 0px 4px 0px;">
				<span>{{ $data['num_first'] }} - {{ $data['num_last'] }}</span>
			</td>
			<td style="font-size: 12px;text-align: right;padding: 4px 0px 4px 0px;">
				<span>{{ number_format($data['gravable'], 2, '.', ',') }}</span>
			</td>
			<td style="font-size: 12px;text-align: right;padding: 4px 0px 4px 0px;">
				<span>{{ number_format($data['exento'], 2, '.', ',') }}</span>
			</td>
			<td style="font-size: 12px;text-align: right;padding: 4px 0px 4px 0px;">
				<span>{{ number_format($data['subtotal'], 2, '.', ',') }}</span>
			</td>		
			<td style="font-size: 12px;text-align: right;padding: 4px 0px 4px 0px;">
				<span>{{ number_format($data['isv'], 2, '.', ',') }}</span>
			</td>
			<td style="font-size: 12px;padding: 4px 15px 4px 0px;text-align: right;">
				<span>{{ number_format($data['total'], 2, '.', ',') }}</span>
			</td>		
		</tr>
		@php
		 $total_grav+= $data['gravable'];
		 $total_ex+= $data['exento'];
		 $total_sub+= $data['subtotal'];
		 $total_isv+= $data['isv'];
		 $total_total+= $data['total'];
		@endphp
		@endforeach
		
		<tr>
			<td style="font-size: 12px;padding: 4px 0px 4px 15px;">
				<span></span>
			</td>		
			<td style="font-size: 12px;padding: 4px 0px 4px 0px;">
				<span></span>
			</td>
			<td style="font-size: 13px;text-align: right;padding: 4px 0px 4px 0px;border-top: 1px solid #000;">
				<span><strong>{{ number_format($total_grav, 2, '.', ',') }}</strong></span>
			</td>
			<td style="font-size: 13px;text-align: right;padding: 4px 0px 4px 0px;border-top: 1px solid #000;">
				<span><strong>{{ number_format($total_ex, 2, '.', ',') }}</strong></span>
			</td>		
			<td style="font-size: 13px;padding: 4px 0px 4px 0px;text-align: right;border-top: 1px solid #000;">
				<span><strong>{{ number_format($total_sub, 2, '.', ',') }}</strong></span>
			</td>
			<td style="font-size: 13px;padding: 4px 0px 4px 0px;text-align: right;border-top: 1px solid #000;">
				<span><strong>{{ number_format($total_isv, 2, '.', ',') }}</strong></span>
			</td>
			<td style="font-size: 13px;padding: 4px 15px 4px 0px;text-align: right;border-top: 1px solid #000;">
				<span><strong>{{ number_format($total_total, 2, '.', ',') }}</strong></span>
			</td>
		</tr>
	</table>
</div>
</body>
</html>
