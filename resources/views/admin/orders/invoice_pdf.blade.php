
<table width="700" style="margin:0 auto; font-family: sans-serif; background-color:#fff;">
    <tr>
		    <td colspan="3" >
		        <h5 style="text-align:center;margin-left: -40px;">DOCUMENTO NO VALIDO PARA NINGUN USO</h5>
		    </td>
		  </tr>
   <tr>
     <td  style="vertical-align: top; width: 25%;"><img src="http://receipts.iamwhiz.com/images/logo.png" width="170px"/></td>
	 <td style="text-align:center;  width: 50%;">
	    <div>
		   <h3 style="font-family: sans-serif; font-size: 22px; margin-bottom: 10px;">LUMINO-TEC</h3>
		   <p style="margin-top: 0px; margin-bottom: 8px; font-size: 15px;">Barrio Montevideo 7 Ave. 4 Calle</p>
		   <p style="margin-top: 0px; margin-bottom: 8px; font-size: 15px;">Tel. 2617-1009 lumin-o-tec@hotmail.com</p>
		   <p style="margin-top: 0px; margin-bottom: 8px; font-size: 15px;">RTN: 0521253522</p>
		</div>
	 </td>
	 <td  style="vertical-align: top; width: 25%;text-align:right;"> <img src="http://receipts.iamwhiz.com/images/ledison.png" width="140px"/></td>
   </tr>
   <tr>
		<td style="font-size: 15px; width: 20%; vertical-align: top;"><strong>FETCHA:</strong> {{ date('d/m/yy',strtotime($order->invoice->created_at)) }}</td>
		<td style="font-size: 15px; width: 40%; vertical-align: top;text-align:center;">C.A.I: {{ $order->invoice->format->cia_number }}</td>
		<td style="font-size: 15px; width: 30%; vertical-align: top; padding-left: 8px;text-align:right;"><strong>FACTURA #</strong> {{ $order->invoice->invoice_no }}</td>
   </tr>
    <tr>
	  <td colspan="3">
		  <table width="100%" cellspacing="0">
		   <tr>
				<td style="font-size: 15px; width: 50%;"><strong>CLIENTE:</strong> {{$order->clients->name}}  RTN:{{$order->clients->code}}</td>
				<td style="font-size: 15px; width: 50%; text-align:right;"><strong>VENCE:</strong> {{ date('d/m/yy',strtotime($order->invoice->due_date)) }}</td>
		   </tr>
		    <tr>
				<td style="font-size: 15px; width: 50%; padding: 10px 0px 0px;"><strong>Numero Orden de Compra Exenta:</strong> {{ $order->id }} </td>
				<td style="font-size: 15px; width: 50%; padding: 10px 0px 0px;"><strong>Numero Identification de la S.A.G:</strong> </td> 
		   </tr>
		    <tr>
				<td style="font-size: 15px; width: 50%; padding: 10px 0px;"><strong>Número Constancia del Registro Exonerados:</strong> </td>
				<td style="font-size: 15px; width: 50%; padding: 10px 0px;"><strong>Número Diplomatico: </strong> </td>
		   </tr>
		  </table>
	  </td>
   </tr>
   <tr>
     <td colspan="3">
	   <table width="100%" cellspacing="0" style="text-align: left;">  
	      <tr>
		     <th style="font-size: 15px; text-align: left; border-top: 1px solid #000; border-bottom: 1px solid #000; padding: 10px 0px;">CANT.</th>
		     <th style="font-size: 15px; text-align: left; border-top: 1px solid #000; border-bottom: 1px solid #000; padding: 10px 0px;">CODIGO</th>
		     <th style="font-size: 15px; text-align: left; border-top: 1px solid #000; border-bottom: 1px solid #000; padding: 10px 0px;">DESCRIPTION</th>
		     <th style="font-size: 15px; text-align: left; border-top: 1px solid #000; border-bottom: 1px solid #000; padding: 10px 0px;">UNITARIO</th>
		     <th style="font-size: 15px; text-align: left; border-top: 1px solid #000; border-bottom: 1px solid #000; padding: 10px 0px;">DESCUENTO Y REBAJAS</th>
		     <th style="font-size: 15px; text-align: left; border-top: 1px solid #000; border-bottom: 1px solid #000; padding: 10px 0px;">TOTAL</th>
		  </tr>
		  @foreach($order->products as $product)
		  <tr>
		     <td style="padding: 10px 0px; font-size: 15px; text-align: left;">{{ $product->pivot->qty }}</td>
		     <td style="padding: 10px 0px; font-size: 15px; text-align: left;">{{ $product->code }}</td>
		     <td style="padding: 10px 0px; font-size: 15px; text-align: left;">{{ $product->description }}</td>
		     <td style=" padding: 10px 0px; font-size: 15px; text-align: left;">L. {{ number_format(\App\Order::priceByQty($product->pivot->id),2)  }}</td>
		     <td style="padding: 10px 0px; font-size: 15px; text-align: left;">L. {{ number_format(\App\Order::discountTotalByPro($product->pivot->id)['discount'], 2) }}</td>
		     <td style="padding: 10px 0px; font-size: 15px; text-align: left;">L. {{ number_format(\App\Order::discountTotalByPro($product->pivot->id)['total'],2) }}</td>
		  </tr>
		  @endforeach
	   </table>
	 </td>
   </tr>
    <tr>
     <td colspan="3">
	   <table width="100%" cellspacing="0" style="border-top: 1px solid #000; margin-top: 160px;"> 
          <tr>
		    <td style="font-size:15px; vertical-align: bottom;">
			   <p style="margin: 5px 0px;"><strong>Son:Catorce Mil Ochocientos Treinta Y Cinco Lempiras Exactos</strong></p>
			   <p style="margin: 5px 0px;">Rango Autorizado: {{ $order->invoice->format->format_number }}-{{ $order->invoice->format->start_number }} a la {{ $order->invoice->format->end_number }}</p>
			   <p style="margin: 5px 0px;">Fecha Limite Emision: {{ date('d/m/yy',strtotime($order->invoice->format->end_emission_date)) }} </p>
			   <p style="margin: 5px 0px;">GRACIAS POR SU COMPRA</p>
			   
			</td>
		    <td style="font-size:15px; vertical-align: bottom; text-align:right;">
			  <p style="margin: 5px 0px;">DESCUENTO Y REBAJAS</p>
			   <p style="margin: 5px 0px;">IMPORTE EXONERADO</p>
			   <p style="margin: 5px 0px;">IMPORTE EXENTO</p>
			   <p style="margin: 5px 0px;">IMPORTE GRAVADO 15%</p>
			   <p style="margin: 5px 0px;">IMPORTE GRAVADO 18%</p>
			   <p style="margin: 5px 0px;">ISV 15%</p>
			   <p style="margin: 5px 0px;">ISV 18%</p>
			   <p style="margin: 5px 0px; font-size:16px;"><strong>TOTAL A PAGAR</strong></p>
			</td>
		    <td style="vertical-align: bottom; font-size:15px; text-align:right; padding-left: 10px;">
			  <p style="margin: 5px 0px;">L. {{ number_format(\App\Order::totalAndTax($order->id)['discount'],2) }}</p>
			  <p style="margin: 5px 0px;">L. 0.00</p>
			  <p style="margin: 5px 0px;">L. 0.00</p>
			  <p style="margin: 5px 0px;">L. {{ number_format(\App\Order::totalAndTax($order->id)['total'],2) }}</p>
			  <p style="margin: 5px 0px;">L. 0.00</p>
			  <p style="margin: 5px 0px;">L. {{ number_format(\App\Order::totalAndTax($order->id)['tax'],2) }}</p>
			  <p style="margin: 5px 0px;">L. 0.00</p>
			  <p style="margin: 5px 0px; font-size:16px;"><strong>L. {{ number_format($order->invoice->amount,2) }}</strong></p>
			</td>
		  </tr>
		  <tr>
		    <td colspan="3">
			<p style="margin: 5px 0px;">
			    <span style="font-style:italic;">"La factura es beneficio de todos, exijala"</span> 
			    <span style="margin-left: 10px;"><strong>Original:</strong> Cliente</span>
			    <span style="margin-left: 10px;"><strong>Copia:</strong> Obligado Tributario</span>
			    <span style="margin-left: 10px;">Recibido Por __________</span>
			   </p>
		    </td>
		  </tr>
		  
		  
	   </table>
	 </td>
	</tr>
</table>