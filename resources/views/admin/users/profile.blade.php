@extends('layouts.admin')
@section('content')
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
</style>

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
							<h3>L {{ \App\User::totalEarnings(auth()->user()->id) }}</h3>

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
							<h3>{{ \App\User::ordersCount(auth()->user()->id) }}</h3>

							<p>Orders</p>
						  </div>
						  <div class="icon">
							<i class="ion ion-stats-bars"></i>
						  </div>
						  <a href="{{ route('admin.userOrders') }}" class="small-box-footer">Más Información <i class="fas fa-arrow-circle-right"></i></a>
						</div>
					  </div>
					  <!-- ./col -->
					  <div class="col-lg-4">
						<!-- small box -->
						<div class="small-box bg-warning">
						  <div class="inner">
							<h3>{{ \App\User::salesManClients(auth()->user()->id) }}</h3>

							<p>Clients</p>
						  </div>
						  <div class="icon">
							<i class="ion ion-person-add"></i>
						  </div>
						  <a href="{{ route('admin.userClients') }}" class="small-box-footer">Más Información <i class="fas fa-arrow-circle-right"></i></a>
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
		<table class="table table-bordered">
		  <thead>
			<tr>
			  <th scope="col">#</th>
			  <th scope="col">First</th>
			  <th scope="col">Last</th>
			  <th scope="col">Handle</th>
			</tr>
		  </thead>
		  <tbody>
			<tr>
			  <th scope="row">1</th>
			  <td>Mark</td>
			  <td>Otto</td>
			  <td>@mdo</td>
			</tr>
			<tr>
			  <th scope="row">2</th>
			  <td>Jacob</td>
			  <td>Thornton</td>
			  <td>@fat</td>
			</tr>
			<tr>
			  <th scope="row">3</th>
			  <td colspan="2">Larry the Bird</td>
			  <td>@twitter</td>
			</tr>
		  </tbody>
		</table>
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
@endsection
@section('scripts')
@parent
<script>
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

		// Get the <span> element that closes the modal
		var span = document.getElementsByClassName("close")[0];

		// When the user clicks on <span> (x), close the modal
		span.onclick = function() {
		  modal.style.display = "none";
		}
   

  $( "#upload_with_csv" ).click( function() {
       $('.form_csv').toggleClass('d-inline');
        //$( ".form_csv" ).toggle( 'slow' );
    });
  
  $('.datatable-client').DataTable({
   
    processing:true,
    serverSide: true,
    ajax: {
          
          url: "{{ route('admin.clients.list') }}",

           headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                  type: "POST",
              
        },

    columnDefs: [
        {
           targets: '_all',
           defaultContent: '-'
        }
     ],

    columns: [
        {
                "data": 'DT_RowIndex',
                orderable: false, 
                searchable: false
            },
            {data: 'avatar',
               render: function(data){
                   if(data){
                     return '<img src="{{url('/')}}'+data+'" width="150"/>';

                   }else{
                    return 'N/A';
                   }
                   
                }
            }, 
            {data: 'code'}, 
            {data: 'name'}, 
            {data: 'direction'}, 
            {data: 'mobile'},
            {data: 'fax'},
            {data: 'contact'},
            {data: 'id',
               render: function(data){

                   return '<a href="clients/'+data+'/edit" class="btn btn-success mt-2"><i class="fas fa-edit"></i> Edit</a> <a href="clients/'+data+'/delete" class="btn btn-danger mt-2 confirm"><i class="fas fa-trash"></i></a>';
                }
            },
            
             
    ]

  });
    $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
        $($.fn.dataTable.tables(true)).DataTable()
            .columns.adjust();
    });
});
$(document).on('click','.confirm',function(){
      return confirm('Are You Sure?');
});
</script>
@endsection