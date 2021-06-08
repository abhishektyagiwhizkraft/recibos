@extends('layouts.client')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="row">
					  <div class="col-lg-6 col-6">
						<!-- small box -->
						<div class="small-box bg-info">
						  <div class="inner">
							<h3>{{ Helper::clientTotalOrders(Helper::getClientDetail(auth()->user()->id)->id) }}</h3>

							<p>Tus Ordenes</p>
						  </div>
						  <div class="icon">
							<i class="ion ion-bag"></i>
						  </div>
						  <a href="{{ route('client.orders') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
						</div>
					  </div>
					  <!-- ./col -->
					  <div class="col-lg-6 col-6">
						<!-- small box -->
						<div class="small-box bg-success">
						  <div class="inner">
							<h3>{{ Helper::clientTotalInvoices(Helper::getClientDetail(auth()->user()->id)->id) }}</h3>

							<p>Sus Facturas</p>
						  </div>
						  <div class="icon">
							<i class="ion ion-stats-bars"></i>
						  </div>
						  <a href="{{ route('client.invoices') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
						</div>
					  </div>
          
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
