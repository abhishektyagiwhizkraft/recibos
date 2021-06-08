<!DOCTYPE html>
<html>
<head>
<title>{{ $nota->clients->name }}</title>
</head>
<body>

<div style="margin:0 auto;bordera: 1px solid #bdbdbd; max-width: 900px; font-family: Helvetica Neue,sans-serif;">
<table style="margin:0 auto;border-spacing: 0;font-family: Helvetica Neue,sans-serif;" width="100%"  cellspacing="0">
	<tr>
		<td colspan="1" style="padding-bottom: 10px;">
			<img src="http://dev.recibos.ledison.shop/images/logo.png" width="170px"/>
		</td>
		<td colspan="5" style="text-align:center;padding-bottom: 10px;">			
				<p style="font-family: sans-serif; font-size: 18px; margin-bottom: 0px; margin-top: 0;"><strong>LUMINO-TEC</strong></p>
				<p style="margin-top: 0px; margin-bottom: 0px; font-size: 12px;">lumino-tec@hotmail.com</p>		   			
		</td>	 
	</tr>
	<tr>
		<td colspan="2" style="padding-top: 40px;padding-left: 15px;padding-bottom: 10px;border-bottom: 2px solid #000;font-size: 12px;"><strong>RTN: {{ $nota->clients->code }}</strong></td>
		<td colspan="4" style="text-align: right;padding-right: 20px;padding-top: 40px;padding-bottom: 10px;border-bottom: 2px solid #000;font-size: 12px;"><strong>C.A.I.: {{ $nota->cia->cia_number }}</strong></td>
	</tr>	
	<tr>
		<td colspan="2" style="padding-bottom: 15px;font-size: 12px;padding-left: 15px;padding-top: 10px;">
			<span><strong>FECHA:</strong> {{ date('d/m/Y',strtotime($nota->created_at)) }}</span>
		</td>
		<td colspan="2" style="padding-top: 10px;padding-bottom: 15px;font-size: 12px;text-align: center;padding-left: 15px;">
			<span><strong>Nota de Credito</strong></span>
		</td>
		<td colspan="2" style="padding-top: 10px;padding-bottom: 15px;font-size: 12px;padding-left: 15px;text-align: right;">
			<span><strong>No.</strong> {{ $nota->note_credit_id }}</span>
		</td>
	</tr>	
	<tr>
		<td colspan="6" style="padding-bottom: 15px;font-size: 12px;padding-left: 15px;">
			<span><strong>CLIENTE: </strong>{{ $nota->clients->name }}</span>
		</td>
	</tr>
	<tr>
		<td colspan="2" style="padding-bottom: 15px;font-size: 12px;padding-left: 15px;">
			<span><strong>RTN:</strong> {{ $nota->clients->code }}</span>
		</td>
		<td colspan="2" style="padding-bottom: 15px;font-size: 12px;text-align: center;padding-left: 15px;">
			<span><strong>TELEFONO:</strong> {{ $nota->clients->mobile }}</span>
		</td>
		<td colspan="2" style="padding-bottom: 15px;font-size: 12px;padding-left: 15px;text-align: right;">
			<span><strong>Vendedor: </strong>{{ $nota->vendor->name }}</span>
		</td>
	</tr>		
	<tr>
		<td style="padding-bottom: 4px;font-size: 12px;border: 1px solid #000;padding-left: 15px;padding-top: 4px;width: 20%;border-right: 0;">
			<span><strong>CODIGO</strong></span>
		</td>
		<td style="padding-bottom: 4px;font-size: 12px;border: 1px solid #000;padding-top: 4px;border-right: 0;border-left: 0;width: 5%;text-align: center;">
			<span><strong>CANT.</strong></span>
		</td>
		<td colspan="2" style="padding-bottom: 4px;font-size: 12px;border: 1px solid #000;padding-top: 4px;border-right: 0;border-left: 0;padding-left: 10px;width: 40%;">
			<span><strong>DESCRIPCION</strong></span>
		</td>		
		<td style="padding-bottom: 4px;font-size: 12px;border: 1px solid #000;padding-left: 0;padding-top: 4px;text-align: center;width: 15%;border-right: 0;border-left: 0;">
			<span><strong>PRECIO UNIT.</strong></span>
		</td>		
		<td style="padding-bottom: 4px;font-size: 12px;border: 1px solid #000;padding-left: 0;padding-top: 4px;border-left: 0;text-align: center;width: 15%;">
			<span><strong>TOTAL</strong></span>
		</td>
	</tr>
	@foreach($nota->products as $product)
	<tr>
		<td style="font-size: 12px;padding-left: 15px;padding-bottom: 4px;width: 20%;padding-top: 4px;">
			<span>{{ $product->code }}</span>
		</td>
		<td style="padding-bottom: 4px;font-size: 14px;text-align: center;padding-top: 4px;width: 5%;">
			<span>{{ $product->pivot->initial_order_qty }}</span>
		</td>
		<td colspan="2"style="padding-bottom: 4px;font-size: 14px;padding-top: 4px;width: 40%;text-align: left;padding-left: 10px;">
			<span>{{ $product->description }}</span>
		</td>
		<td style="padding-bottom: 4px;font-size: 12px;padding-top: 4px;text-align: right;width: 15%;">
			<span>L. {{ $product->price }}</span>
		</td>
		<td style="padding-bottom: 4px;font-size: 12px;padding-top: 4px;text-align: right;width: 15%;padding-right: 15px;padding-left: 0px;">
			<span>L. {{ ($product->price*$product->pivot->initial_order_qty) }}</span>
		</td>
	</tr>
	@endforeach
</table>
<table style="margin:0px;padding-top: 100px;" width="100%" cellspacing="0">
	<tr>
		<td rowspan="4"  colspan="3" style="font-size: 12px;vertical-align: baseline; border-top: 1px solid #000;padding: 10px 0 10px 15px;">
			<span><strong>Son: Sesenta Y Un Lempiras Con 99/100</strong></span>
		</td>		
		<td colspan="2" style="font-size: 12px;width: 20%;border-top: 1px solid #000;padding: 10px 0 4px 15px;border-left: 1px solid #000;">
			<span>SUBTOTAL EXENTO</span>
		</td>
		<td style="font-size: 12px;padding: 10px 0 4px 15px;text-align: right;padding-right: 15px;border-top: 1px solid #000;border-right: 1px solid #000;">
			<span>L. 0.00</span>
		</td>
	</tr>
	<tr>			
		<td colspan="2" style="font-size: 12px;width: 20%;padding: 4px 0 4px 15px;border-left: 1px solid #000;">
			<span>SUBTOTAL ISV 15%</span>
		</td>
		<td style="font-size: 12px;padding: 4px 0 4px 15px;text-align: right;padding-right: 15px;border-right: 1px solid #000;">
			<span>L. {{ $nota->amount }}</span>
		</td>
	</tr>
	<tr>			
		<td colspan="2" style="font-size: 12px;width: 20%;padding: 4px 0 4px 15px;border-left: 1px solid #000;">
			<span>ISV 15%</span>
		</td>
		<td style="font-size: 12px;padding: 4px 0 4px 15px;text-align: right;padding-right: 15px;border-right: 1px solid #000;">
			<span>L. {{ $nota->tax }}</span>
		</td>
	</tr>
	<tr>			
		<td colspan="2" style="font-size: 12px;width: 20%;border-bottom: 1px solid #000;padding: 10px 0 10px 15px;border-left: 1px solid #000;">
			<span><strong>T O T A L</strong></span>
		</td>
		<td style="font-size: 12px;width: 20%;border-bottom: 1px solid #000; text-align: right;padding: 10px 15px 10px 0px;border-right: 1px solid #000;">
			<span><strong>L. {{ $nota->total }}</strong></span>
		</td>
	</tr>
	<tr>			
		<td style="font-size: 12px;text-align: center;width: 10%;padding: 10px 0 10px 0px;width: 25%">
			<div style="border-top: 1px solid #000; margin-right: 10px;">
				<span>Elaborado Por</span>
			</div>
		</td>
		<td style="font-size: 12px;text-align: center;padding: 10px 0 10px 0px;">
			<div style="border-top: 1px solid #000; margin-right: 5px;">
				<span>Autorizado Por</span>
			</div>
		</td>
		<td style="font-size: 12px;text-align: center;padding: 10px 0 10px 15px;">
			<div style="border-top: 1px solid #000; margin-right: 5px;">
				<span>Recibido Por</span>
			</div>
		</td>		
		<td colspan="3" style="">
			<span></span>
		</td>
	</tr>
	<tr>			
		<td colspan="6" style="font-size: 12px;padding: 10px 0 2px 0px;">
			<span>Rango Autorizado: {{ $nota->cia->format_number }}-{{ $nota->cia->start_number }} a la {{ $nota->cia->end_number }}</span>
		</td>		
	</tr>
	<tr>			
		<td colspan="6" style="font-size: 12px;padding: 0px 0 0px 0px;">
			<span>Fecha Limite Emision: {{ date('d/m/Y',strtotime($nota->cia->end_emission_date)) }}</span>
		</td>		
	</tr>
</table>
</div>
</body>
</html>
