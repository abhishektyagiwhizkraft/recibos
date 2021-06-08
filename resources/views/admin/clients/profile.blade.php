@extends('layouts.admin')
@section('content')
<style>
.loader img, .loader p{
	display:none;
    width: 70px;
    position: absolute;
    left: 0;
    right: 0;
    margin: auto;
    top: 45%;
    z-index: 999;
}
.loader p{
	    top: 56% !important;
}
.blur-filter {
    -moz-filter: blur(2px);
    -o-filter: blur(2px);
    backdrop-filter: blur(1px);
    -ms-filter: blur(2px);
    position: absolute;
    z-index: 99;
    filter: blur(2px) !important;
    -webkit-filter: blur(6px) !important;
    width: 100%;
    height: 100%;
}
.loader_imm {
    position: fixed;
    z-index: 9999;
    left: 0;
    right: 0;
    margin: 0 auto;
    top: 0;
    height: 100%;
    background: #ffffff17;
}
.main-body {
    padding: 15px;
}
.card {
    box-shadow: 0 1px 3px 0 rgba(0,0,0,.1), 0 1px 2px 0 rgba(0,0,0,.06);
}

.card {
    position: relative;
    display: flex;
    flex-direction: column;
    min-width: 0;
    word-wrap: break-word;
    background-color: #fff;
    background-clip: border-box;
    border: 0 solid rgba(0,0,0,.125);
    border-radius: .25rem;
}

.card-body {
    flex: 1 1 auto;
    min-height: 1px;
    padding: 1rem;
}

.gutters-sm {
    margin-right: -8px;
    margin-left: -8px;
}

.gutters-sm>.col, .gutters-sm>[class*=col-] {
    padding-right: 8px;
    padding-left: 8px;
}
.mb-3, .my-3 {
    margin-bottom: 1rem!important;
}

.bg-gray-300 {
    background-color: #e2e8f0;
}
.h-100 {
    height: 100%!important;
}
.shadow-none {
    box-shadow: none!important;
}

.text-secondary {
    color: #828282!important;
}

/* Style the Image Used to Trigger the Modal */
#myImg {
  border-radius: 5px;
  cursor: pointer;
  transition: 0.3s;
}

#myImg:hover {opacity: 0.7;}

/* The Modal (background) */
.modal {
  display: none; /* Hidden by default */
  position: fixed; /* Stay in place */
  z-index: 9999; /* Sit on top */
  padding-top: 100px; /* Location of the box */
  left: 0;
  top: 0;
  width: 100%; /* Full width */
  height: 100%; /* Full height */
  overflow: auto; /* Enable scroll if needed */
  background-color: rgb(0,0,0); /* Fallback color */
  background-color: rgba(0,0,0,0.9); /* Black w/ opacity */
}

/* Modal Content (Image) */
.modal-content {
  margin: auto;
  display: block;
  width: 80%;
  max-width: 700px;
}

/* Caption of Modal Image (Image Text) - Same Width as the Image */
#caption {
  margin: auto;
  display: block;
  width: 80%;
  max-width: 700px;
  text-align: center;
  color: #ccc;
  padding: 10px 0;
  height: 150px;
}

/* Add Animation - Zoom in the Modal */
.modal-content, #caption {
  animation-name: zoom;
  animation-duration: 0.6s;
}

@keyframes zoom {
  from {transform:scale(0)}
  to {transform:scale(1)}
}

/* The Close Button */
.close {
  position: absolute;
  top: 15px;
  right: 35px;
  color: #f1f1f1;
  font-size: 40px;
  font-weight: bold;
  transition: 0.3s;
}

.close:hover,
.close:focus {
  color: #bbb;
  text-decoration: none;
  cursor: pointer;
}

/* 100% Image Width on Smaller Screens */
@media only screen and (max-width: 700px){
  .modal-content {
    width: 100%;
  }
}
.row.gutters-sm {
    overflow-y: hidden;
}
</style>
<div class="loader" >
	<div class="blur"></div>
	<img src="{{asset('public/images/preloader.gif')}}" alt="" class="img-fluid" >
	<p>Uploading...</p>
</div>
<div class="row gutters-sm">
            <div class="col-md-3 mb-3">
              <div class="card">
                <div class="card-body">
                  <div class="d-flex flex-column align-items-center text-center">
                    <img id="myImg" src="{{ '/public'.$client->avatar ?? 'https://bootdey.com/img/Content/avatar/avatar7.png' }}" alt="{{ $client->name ?? 'N/A' }}" class="rounded-circle" width="150">
                    <div class="mt-3">
                      <h4>{{ $client->name }}</h4>
                      <p class="text-secondary mb-1">{{ $client->code ?? 'N/A' }}</p>
                      <p class="text-muted font-size-sm">{{ $client->contact ?? 'N/A' }}</p>
                      <p class="text-muted font-size-sm">Salesman: {{ \App\User::clientSalesMan($client->id) }}</p>
                      <a href="{{ url('/')}}/admin/clients/{{ $client->id }}/edit" class="btn btn-primary">Edit</a>
                      <a href="{{ url('/')}}/admin/clients/{{ $client->id }}/delete" class="btn btn-danger confirm">Delete</a>
                    </div>
					
					
                  </div>
				  
				  <!--ul class="list-group list-group-unbordered mb-3">
                  <li class="list-group-item">
                    <b>Total Invoices</b> <a class="float-right">{{ Helper::clientTotalInvoices($client->id) }}</a>
                  </li>
                  <li class="list-group-item">
                    <b>Pending Invoices</b> <a class="float-right">{{ Helper::clientPendingInvoices($client->id) }}</a>
                  </li>
                  <li class="list-group-item">
                    <b>Pending Payment</b> <a class="float-right">{{ Helper::clientPendingPayment($client->id) }}</a>
                  </li>
                </ul-->
                </div>
              </div>
              
            </div>
            <div class="col-md-9">
			<div class="card">
              <div class="card-header p-2">
                <ul class="nav nav-pills">
                  <li class="nav-item"><a class="nav-link active" href="#record" data-toggle="tab">Registros</a></li>
                  <li class="nav-item"><a class="nav-link" href="#timeline" data-toggle="tab">Detalles</a></li>
                  <li class="nav-item"><a class="nav-link active webcam ml-2" style="background-color:#4dbd74" href="#" ><i class="fa fa-map-marker"></i> Check-in</a></li>
                 </ul>
              </div><!-- /.card-header -->
              <div class="card-body">
                <div class="tab-content">
                  <div class="tab-pane active" id="record">
                    <div class="row">
					  <div class="col-lg-4">
						<!-- small box -->
						<div class="small-box bg-info">
						  <div class="inner">
							<h3>{{ Helper::clientTotalInvoices($client->id) }}</h3>

							<p>Facturas Totales</p>
						  </div>
						  <div class="icon">
							<i class="ion ion-bag"></i>
						  </div>
						  <a href="{{ route('admin.client.invoices',$client->id)}}?type=all" class="small-box-footer">Más Información <i class="fas fa-arrow-circle-right"></i></a>
						</div>
					  </div>
					  <!-- ./col -->
					  <div class="col-lg-4">
						<!-- small box -->
						<div class="small-box bg-success">
						  <div class="inner">
							<h3>{{ Helper::clientPendingInvoices($client->id) }}</h3>

							<p>Facturas Pendientes</p>
						  </div>
						  <div class="icon">
							<i class="ion ion-stats-bars"></i>
						  </div>
						  <a href="{{ route('admin.client.invoices',$client->id)}}?type=pending" class="small-box-footer">Más Información <i class="fas fa-arrow-circle-right"></i></a>
						</div>
					  </div>
					  <!-- ./col -->
					  <div class="col-lg-4">
						<!-- small box -->
						<div class="small-box bg-warning">
						  <div class="inner">
							<h3>L {{ Helper::clientPendingPayment($client->id) }}</h3>

							<p>Pago Pendiente</p>
						  </div>
						  <div class="icon">
							<i class="ion ion-person-add"></i>
						  </div>
						  <a href="{{ route('admin.client.invoices',$client->id)}}?type=pending" class="small-box-footer">Más Información <i class="fas fa-arrow-circle-right"></i></a>
						</div>
					  </div>
					 
					</div>
					<div class="row">
					 <!-- ./col -->
					  <div class="col-lg-4">
						<!-- small box -->
						<div class="small-box bg-danger">
						  <div class="inner">
							<h3>{{ Helper::clientOrders($client->id) }}</h3>

							<p>Pedidos</p>
						  </div>
						  <div class="icon">
							<i class="ion ion-pie-graph"></i>
						  </div>
						  <a href="{{ route('admin.client.orders',$client->id)}}?type=all" class="small-box-footer">Más Información <i class="fas fa-arrow-circle-right"></i></a>
						</div>
					  </div>
					  <!-- ./col -->
					  <!-- ./col -->
					  <div class="col-lg-4">
						<!-- small box -->
						<div class="small-box bg-success">
						  <div class="inner">
							<h3>{{ Helper::clientPendingOrders($client->id) }}</h3>

							<p>Ordenes Pendientes</p>
						  </div>
						  <div class="icon">
							<i class="ion ion-pie-graph"></i>
						  </div>
						  <a href="{{ route('admin.client.orders',$client->id)}}?type=pending" class="small-box-footer">Más Información <i class="fas fa-arrow-circle-right"></i></a>
						</div>
					  </div>
					  <!-- ./col -->
					  <!-- ./col -->
					  <div class="col-lg-4">
						<!-- small box -->
						<div class="small-box bg-primary">
						  <div class="inner">
							<h3>{{ Helper::clientProducts($client->id) }}</h3>

							<p>Productos</p>
						  </div>
						  <div class="icon">
							<i class="ion ion-pie-graph"></i>
						  </div>
						  <a href="{{ route('admin.client.products',$client->id)}}" class="small-box-footer">Más Información <i class="fas fa-arrow-circle-right"></i></a>
						</div>
					  </div>
					  <!-- ./col -->
					</div>
                  </div>
                  <!-- /.tab-pane -->
                  <div class="tab-pane" id="timeline">
                    <!-- The timeline -->
                    <div class="timeline timeline-inverse">
                      <div class="row">
						<div class="col-sm-3">
						  <h6 class="mb-0">RTN</h6>
						</div>
						<div class="col-sm-9 text-secondary">
						  {{ $client->code ?? 'N/A' }}
						</div>
					  </div>
					  <hr>
					  <div class="row">
						<div class="col-sm-3">
						  <h6 class="mb-0">Full Name</h6>
						</div>
						<div class="col-sm-9 text-secondary">
						  {{ $client->name ?? 'N/A' }}
						</div>
					  </div>
					  <hr>
					  <div class="row">
						<div class="col-sm-3">
						  <h6 class="mb-0">Email</h6>
						</div>
						<div class="col-sm-9 text-secondary">
						  {{ $client->email ?? 'N/A' }}
						</div>
					  </div>
					  <hr>
					 <div class="row">
						<div class="col-sm-3">
						  <h6 class="mb-0">Telefono</h6>
						</div>
						<div class="col-sm-9 text-secondary">
						  {{ $client->mobile ?? 'N/A' }}
						</div>
					  </div>
					  <hr>
					  <div class="row">
						<div class="col-sm-3">
						  <h6 class="mb-0">Fax</h6>
						</div>
						<div class="col-sm-9 text-secondary">
						  {{ $client->fax ?? 'N/A' }}
						</div>
					  </div>
					  <hr>
					  <div class="row">
						<div class="col-sm-3">
						  <h6 class="mb-0">Direccion</h6>
						</div>
						<div class="col-sm-9 text-secondary">
						{{ $client->direction ?? 'N/A' }}
						</div>
					  </div>
					  <hr>
					  <div class="row">
						<div class="col-sm-3">
						  <h6 class="mb-0">Contacto</h6>
						</div>
						<div class="col-sm-9 text-secondary">
						  {{ $client->contact ?? 'N/A' }}
						</div>
					  </div>
                    </div>
                  </div>
                  <!-- /.tab-pane -->

                  <!-- /.tab-pane -->
                </div>
                <!-- /.tab-content -->
              </div><!-- /.card-body -->
            </div>
              
              
            </div>
          </div>
		  <div class="row">
		  <div class="col-md-12 mt-3">
		    <div class="card">
				<div class="card-header">
					Historial de Check-in
				</div>

				<div class="card-body">
					<div class="table-responsive">
						<table class=" table table-bordered table-striped table-hover datatable datatable-products">
							<thead>
								<tr>
									<th>Chech-in By</th>
									<th>Image</th>
									<th>Fecha de visita</th>
								</tr>
							</thead>
							<tbody>
							   @foreach($client->checkins as $checkin)
							    <tr>
									<td>{{ \App\User::getUserName($checkin->check_in_by) }}</td>
									<td><img class="myImg1" src="{{asset('public/uploads')}}/{{ $checkin->image }}" width="200" height="100" /></td>
									<td>{{ date('d-m-Y H:i A',strtotime($checkin->created_at)) }}</td>
								</tr>
							   @endforeach
							</tbody>
						</table>
					</div>


				</div>
			</div>
			</div>
		  </div>
        
	<!-- The Modal -->
	<div id="myModal" class="modal">

	  <!-- The Close Button -->
	  <span class="close">&times;</span>

	  <!-- Modal Content (The Image) -->
	  <img class="modal-content" id="img01">

	  <!-- Modal Caption (Image Text) -->
	  <div id="caption"></div>
	</div>
	<form id="web_cam_img" method="post" action="" enctype="multipart/form-data">
		@csrf
		<input type="file" name="web_img" class="web_img" accept="image/*" capture="camera" style="display:none">
		<input type="hidden" name="client" value="{{ $client->id }}" >
	</form>	
@endsection
@section('scripts')
@parent
<script>

$('.close').on('click',function(){
	 $('#myModal').modal('hide');
	 $('#myModal').css('display','none');
});
		
$(function () {
  
        // Get the modal
		var modal = document.getElementById("myModal");

		// Get the image and insert it inside the modal - use its "alt" text as a caption
		var img = document.getElementById("myImg");
		var modalImg = document.getElementById("img01");
		var captionText = document.getElementById("caption");
		img.onclick = function(){
		  modal.style.display = "block";
		  modalImg.src = this.src;
		  captionText.innerHTML = this.alt;
		}
		
		$('.myImg1').on('click',function(){
			 $('#img01').attr('src',$(this).attr('src'));
			 $('#myModal').modal('show');
		});
		
});


$('.webcam').on('click',function(){
  
   $('.web_img').trigger('click');
});
$('.web_img').on('change',function(){
	
 $('#web_cam_img').submit();
   
});

$.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

   $('#web_cam_img').submit(function(e) {
	   $(".blur").addClass("blur-filter");
       $(".loader").addClass("loader_imm");
       $(".loader_imm img").show();
       $(".loader_imm p").show();
       e.preventDefault();
       let formData = new FormData(this);
     

       $.ajax({
          type:'POST',
          url:'{{ route("upload_img") }}',
           data: formData,
           contentType: false,
           processData: false,
           success: (response) => {
               console.log(response);
               window.location.reload();
               return false;
             if (response) {
               this.reset();
               alert('Image has been uploaded successfully');
             }
           },
           error: function(response){
              console.log(response);
                $('#image-input-error').text(response.responseJSON.errors.file);
           }
       });
   });
   
   $('.datatable-products').DataTable();
</script>

@endsection