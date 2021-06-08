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
				<p style="margin-top: 0px; margin-bottom: 0px; font-size: 12px;">Barrio Montevideo 7 Ave. 4 Calle</p>	
				<p style="margin-top: 0px; margin-bottom: 0px; font-size: 12px;">Tel. 2617-1009</p>		   			
		</td>	
	</tr>
</table>
<table style="margin:0 auto;border-spacing: 0;font-family: Helvetica Neue,sans-serif;" width="100%"  cellspacing="0">
	<tr>
		<td colspan="7" style="border-top: 2px solid #000;height: 20px;text-align: center;padding-top: 5px;padding-bottom: 10px;font-size: 16px;"><strong>Reporte Detallado x Producto</strong></td>
	</tr>
	<tr>
		<td colspan="4" style="padding-bottom: 4px;font-size: 12px;border-bottom: 1px solid #000;padding-left: 15px;">
			<span>Reporte del {{ $start }} hasta {{ $end }}</span>
		</td>
		<td colspan="3" style="padding-bottom: 4px;font-size: 12px;border-bottom: 1px solid #000;padding-right: 15px; text-align: right;">
			<span>LUMINO-TEC</span>
		</td>
	</tr>
	<tr>
		<td style="padding-bottom: 4px;font-size: 14px;border-bottom: 1px solid #000;padding-left: 15px;padding-top: 4px;color: #891325;width: 35%;">
			<span><strong>Producto </strong></span>
		</td>		
		<td style="padding-bottom: 4px;font-size: 14px;border-bottom: 1px solid #000;padding-top: 4px;color: #891325;width: 10%;text-align: left;">
			<span><strong>Fecha</strong></span>
		</td>
		<td style="padding-bottom: 4px;font-size: 14px;border-bottom: 1px solid #000;padding-top: 4px;color: #891325;text-align: left;width: 10%;">
			<span><strong>Doc #</strong></span>
		</td>
		<td style="padding-bottom: 4px;font-size: 14px;border-bottom: 1px solid #000;padding-left: 0;padding-top: 4px;color: #891325;text-align: left;width: 10%;">
			<span><strong>Cliente</strong></span>
		</td>
		<td style="padding-bottom: 4px;font-size: 14px;border-bottom: 1px solid #000;padding-left: 10px;padding-top: 4px;color: #891325;width: 5%;">
			<span></span>
		</td>
		<td style="padding-bottom: 4px;font-size: 14px;border-bottom: 1px solid #000;padding-left: 0;padding-top: 4px;color: #891325;text-align: right;width: 10%;">
			<span><strong>Cantidad</strong></span>
		</td>
		<td style="padding-bottom: 4px;font-size: 14px;border-bottom: 1px solid #000;padding-left: 0;padding-top: 4px;color: #891325;text-align: right;width: 10%;">
			<span><strong>Total Lps</strong></span>
		</td>
	</tr>
	
	<!------ Single Section Started Here ---------->
		<tr><!------ Main Heading Started Here ---------->
			<td colspan="7" style="padding: 5px 0 5px 15px;font-size: 13px;">
				<span><strong>LUMINO-TEC</strong></span>
			</td>		
		</tr><!------ Main Heading End Here ---------->
	
		<!------ Inner Section Content Started Here ---------->
		<?php 
			$all_qty = 0;  
			$total = 0;  
		?>
		@foreach($result as $key => $data)
		@if(count($data)>0)
		<tr>
			<td colspan="7" style="padding: 10px 0 5px 15px;font-size: 13px; color: #891325;">
				<span><strong>{{ $data[0]['code'] }} - {{ $data[0]['pro_name'] }}</strong></span>
			</td>		
		</tr>
        <?php 
		
		$total_qty = 0;
		$total_sub = 0;
		foreach($data as $pro) {	
		?>
		<tr>
			<td style="font-size: 12px;padding-left: 15px;padding-bottom: 4px;width: 35%;padding-top: 4px;">
				<span>{{ $pro['pro_name'] }} </span>
			</td>		
			<td style="padding-bottom: 4px;font-size: 12px;padding-top: 4px;width: 10%;text-align: left;">
				<span>{{ $pro['date'] }}</span>
			</td>
			<td style="padding-bottom: 4px;font-size: 12px;padding-top: 4px;text-align: left;width: 10%;">
				<span>FAC {{ $pro['fac'] }}</span>
			</td>
			<td style="padding-bottom: 4px;font-size: 12px;padding-left: 0;padding-top: 4px;text-align: left;width: 20%;">
				<span>{{ $pro['client_name'] }}</span>
			</td>
			<td style="padding-bottom: 4px;font-size: 12px;padding-left: 10px;padding-top: 4px;width: 5%;">
				<span></span>
			</td>
			<td style="padding-bottom: 4px;font-size: 12px;padding-left: 0;padding-top: 4px;text-align: right;width: 10%;">
				<span>{{ $pro['qty'] }}</span>
			</td>
			<td style="padding-bottom: 4px;font-size: 12px;padding-left: 0;padding-top: 4px;text-align: right;width: 10%;padding-right: 15px;">
				<span>{{ number_format(($pro['qty']*$pro['price']), 2, '.', ',') }}</span>
			</td>
		</tr>
		
		<?php 
		$total_qty+= $pro['qty'] ;
		$total_sub+= ($pro['qty']*$pro['price']) ;
		}  
		?>
		
		<tr>
			<td style="font-size: 14px;padding-left: 10px;padding-bottom: 4px;width: 35%;">
				<span></span>
			</td>
			<td style="font-size: 14px;padding-left: 10px;padding-bottom: 4px;width: 10%;">
				<span></span>
			</td>
			<td style="font-size: 14px;padding-left: 10px;padding-bottom: 4px;width: 10%;">
				<span></span>
			</td>
			<td style="font-size: 14px;padding-left: 10px;padding-bottom: 4px;width: 10%;">
				<span></span>
			</td>
			<td style="font-size: 14px;color: #891325;padding: 5px 15px 5px 10px;width: 5%;text-align: right;">
				<span><strong>Total:</strong></span>
			</td>		
			<td style="font-size: 14px;padding: 5px 0 5px 0;text-align: right;width: 10%;border-top: 1px solid #000;">
				<span>{{ $total_qty }}</span>
			</td>
			<td style="font-size: 14px;padding: 5px 15px 5px 0;text-align: right;width: 10%;border-top: 1px solid #000;">
				<span>{{ number_format($total_sub, 2, '.', ',') }}</span>
			</td>
		</tr>
		<?php
		$all_qty+= $total_qty;
		$total+= $total_sub;
		?>
		@endif
		@endforeach
		
		<tr>
			<td style="font-size: 14px;padding-left: 10px;padding-bottom: 4px;width: 25%;">
				<span></span>
			</td>
			<td style="font-size: 14px;padding-left: 10px;padding-bottom: 4px;width: 10%;">
				<span></span>
			</td>
			<td style="font-size: 14px;padding-left: 10px;padding-bottom: 4px;width: 10%;">
				<span></span>
			</td>
			<td style="font-size: 14px;padding-left: 10px;padding-bottom: 4px;width: 10%;">
				<span></span>
			</td>
			<td style="font-size: 14px;color: #891325;padding: 5px 15px 5px 10px;width: 5%;text-align: right;">
				<span><strong>Total:</strong></span>
			</td>		
			<td style="font-size: 14px;padding: 5px 0 5px 0;text-align: right;width: 15%;border-top: 1px solid #000;">
				<span>{{ $all_qty }}-</span>
			</td>
			<td style="font-size: 14px;padding: 5px 15px 5px 0;text-align: right;width: 15%;border-top: 1px solid #000;">
				<span>{{ number_format($total, 2, '.', ',')  }}</span>
			</td>
		</tr>
		
		
</table>
</body>
</html>
