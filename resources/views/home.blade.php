@extends('layouts.admin')

@section('content')
 @if(session('permissionerror'))
 <div class="alert alert-danger alert-dismissible fade show">
    <strong>Unauthorized!</strong> {{ session('permissionerror') }}
    <button type="button" class="close" data-dismiss="alert">&times;</button>
</div>
 @else
<div class="row mb-2">
          <div class="col-sm-6">
            <h2 class="m-0 text-dark">Welcome ! !</h2>
          </div><!-- /.col -->
          <div class="col-sm-6">
          </div><!-- /.col -->
        </div>
<div class="row">
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
                <h3>{{ Helper::total_invoices() }}</h3>

                <p>Total Invoices</p>
              </div>
              <div class="icon">
                <i class="ion ion-bag"></i>
              </div>
              <a href="{{ route('admin.invoices.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
              <div class="inner">
                <h3>{{ Helper::total_users() }}</h3>

                <p>Users</p>
              </div>
              <div class="icon">
                <i class="ion ion-stats-bars"></i>
              </div>
              <a href="{{ route('admin.users.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
              <div class="inner">
                <h3>{{ Helper::pending_authorization() }}</h3>

                <p>Pending Cash Authorization</p>
              </div>
              <div class="icon">
                <i class="ion ion-person-add"></i>
              </div>
              <a href="{{ route('admin.receipts.cash') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <!--div class="col-lg-3 col-6">
            
            <div class="small-box bg-danger">
              <div class="inner">
                <h3>65</h3>

                <p>Unique Visitors</p>
              </div>
              <div class="icon">
                <i class="ion ion-pie-graph"></i>
              </div>
              <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div-->
          <!-- ./col -->
        </div>
        @endif
@endsection
@section('scripts')
@parent

@endsection