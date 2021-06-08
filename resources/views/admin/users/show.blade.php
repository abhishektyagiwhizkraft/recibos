@extends('layouts.admin')
@section('content')
@if($user->roles[0]->name == 'sales man' || $user->roles[0]->name == 'sales person')
<style>
 
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
</style>

<div class="loader" >
	<div class="blur"></div>
	<img src="{{asset('public/images/preloader.gif')}}" alt="" class="img-fluid" >
</div>
<div class="row gutters-sm">
            <div class="col-md-3 mb-3">
              <div class="card">
                <div class="card-body">
                  <div class="d-flex flex-column align-items-center text-center">
                    <img id="myImg" src="{{ $user->avatar ?? 'https://bootdey.com/img/Content/avatar/avatar7.png' }}" alt="{{ $user->name ?? 'N/A' }}" class="rounded-circle" width="150">
                    <div class="mt-3">
                      <h4>{{ $user->name }}</h4>
                      <p class="text-secondary mb-1">{{ $user->roles[0]->name ?? 'N/A' }}</p>
                      
                    </div>
					
					
                  </div>
				  
				 
                </div>
              </div>
              
            </div>
            <div class="col-md-9">
			<div class="card">
              <div class="card-header p-2">
                <ul class="nav nav-pills">
                  <li class="nav-item"><a class="nav-link active" href="#record" data-toggle="tab">Registros</a></li>
                  <li class="nav-item"><a class="nav-link" href="#timeline" data-toggle="tab">Detalles</a></li>
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
							<h3>L {{ \App\User::totalEarnings($user->id) }}</h3>

							<p>Total Earnings</p>
						  </div>
						  <div class="icon">
							<i class="ion ion-bag"></i>
						  </div>
						  <a href="" class="small-box-footer">Más Información <i class="fas fa-arrow-circle-right"></i></a>
						</div>
					  </div>
					  <!-- ./col -->
					  <div class="col-lg-4">
						<!-- small box -->
						<div class="small-box bg-success">
						  <div class="inner">
							<h3>{{ \App\User::ordersCount($user->id) }}</h3>

							<p>Orders</p>
						  </div>
						  <div class="icon">
							<i class="ion ion-stats-bars"></i>
						  </div>
						  <a href="{{ route('admin.salesOrders',$user->id) }}" class="small-box-footer">Más Información <i class="fas fa-arrow-circle-right"></i></a>
						</div>
					  </div>
					  <!-- ./col -->
					  <div class="col-lg-4">
						<!-- small box -->
						<div class="small-box bg-warning">
						  <div class="inner">
							<h3>{{ \App\User::salesManClients($user->id) }}</h3>

							<p>Clients</p>
						  </div>
						  <div class="icon">
							<i class="ion ion-person-add"></i>
						  </div>
						  <a href="{{ route('admin.salesClient',$user->id) }}" class="small-box-footer">Más Información <i class="fas fa-arrow-circle-right"></i></a>
						</div>
					  </div>
					 
					</div>
					
                  </div>
                  <!-- /.tab-pane -->
                  <div class="tab-pane" id="timeline">
                    <!-- The timeline -->
                    <div class="timeline timeline-inverse">
                      
					  <div class="row">
						<div class="col-sm-3">
						  <h6 class="mb-0">Full Name</h6>
						</div>
						<div class="col-sm-9 text-secondary">
						  {{ $user->name ?? 'N/A' }}
						</div>
					  </div>
					  <hr>
					  <div class="row">
						<div class="col-sm-3">
						  <h6 class="mb-0">Email</h6>
						</div>
						<div class="col-sm-9 text-secondary">
						  {{ $user->email ?? 'N/A' }}
						</div>
					  </div>
					  <hr>
					 <div class="row">
						<div class="col-sm-3">
						  <h6 class="mb-0">Telefono</h6>
						</div>
						<div class="col-sm-9 text-secondary">
						  {{ $user->mobile ?? 'N/A' }}
						</div>
					  </div>
					  <hr>
					  
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
		  <div class="row gutters-sm">
		        <div class="col-md-12">
					<div class="card">
					  <div class="card-header p-2">
						<ul class="nav nav-pills">
						  <li class="nav-item"><strong>Commision History ({{ date('Y') }})</strong></li> &nbsp;&nbsp;&nbsp;&nbsp;
						  <li class="nav-item">Filter By Month : </li> &nbsp;&nbsp;&nbsp;&nbsp;
						  <li class="nav-item">
							<select name="year" id="comm_year" class="form-control">
								<option value="2020">2020</option>
								<option value="2021" selected>2021</option>
							</select>
						  </li>
						  
						  &nbsp;&nbsp;&nbsp;&nbsp;
						  <li class="nav-item">
								<select name="month" id="comm_month" class="form-control">
								
								@for ( $i = 1; $i <= 12; $i ++ )
								
								<option value="{{ $i }}"  {{ ($i == date('m')) ? 'selected' : '' }} >{{ date( 'F', strtotime( "$i/12/10" ) ) }}</option>
								
								@endfor
								
								</select>
						  </li>
						 </ul>
					  </div>
					<div class="table-responsive">
					<table class="table table-bordered">
					  <thead>
						<tr>
						  <th class="text-center">Month</th>
						  <th  class="text-center" >Earning</th>
						</tr>
					  </thead>
					  <tbody>
						<tr>
						  <td align="center" id="append_month">{{ date('F') }}</td>
						  <td align="center" id="append_earning">L {{ \App\User::totalEarnings($user->id) }} </td>
						</tr>
						
					  </tbody>
					</table>
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
@else


<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.user.title') }}
    </div>

    <div class="card-body">
        <div class="mb-2">
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.user.fields.id') }}
                        </th>
                        <td>
                            {{ $user->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.user.fields.name') }}
                        </th>
                        <td>
                            {{ $user->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.user.fields.email') }}
                        </th>
                        <td>
                            {{ $user->email }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <a style="margin-top:20px;" class="btn btn-default" href="{{ url()->previous() }}">
                {{ trans('global.back_to_list') }}
            </a>
        </div>


    </div>
</div>
@endif
<input type="hidden" id="user_id" value="{{ $user->id }}" />
@endsection
@section('scripts')
<script>
$(function () {
	
	$(document).on('change','#comm_month, #comm_year',function(){
		$(".blur").addClass("blur-filter");
        $(".loader").addClass("loader_imm");
		$(".loader img").show();
       
		//var val = $(this).val();
		
		$.ajax({
      
		  headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
		  
		  type:'POST',

		  url:'{{ route("admin.filterComm") }}',

		  data:{user_id:$('#user_id').val() , month : $('#comm_month').val() , year : $('#comm_year').val()},
		 
		  success:function(data){
			  
				$('#append_month').text(data.month);
				$('#append_earning').text('L '+data.com);
				
				$(".blur").removeClass("blur-filter");
				$(".loader").removeClass("loader_imm");
				$(".loader img").hide();
				
		  }
		});
	});
	
});
</script>
@endsection