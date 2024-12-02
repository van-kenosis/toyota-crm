@extends('components.app')

@section('content')

<div class="row mb-4">
    <div class="col">
        <div class="card" style="background-color: #000000;">
            <div class="card-body">
                <div class="d-flex justify-content-center">
                    <div class="">
                        <div class="d-flex justify-content-center">
                            <img src="{{ asset('assets/myimg/logo.png') }}" class="logo-container" alt="Login" style="width: 20%;">
                        </div>
                        <h1 class="text-white d-flex justify-content-center" style="font-size: 64px;"><b>Toyota Albay</b></h1>
                        <h6 class="text-white d-flex justify-content-center  ">Customer Relation Management System</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- <div class="row g-6">
    <!-- Card Border Shadow -->
    <div class="col-lg-3 col-sm-6">
      <div class="card card-border-shadow-primary h-100">
        <div class="card-body">
          <div class="d-flex align-items-center mb-2">
            <div class="avatar me-4">
              <span class="avatar-initial rounded bg-label-primary"><i class="bx bxs-truck bx-lg"></i></span>
            </div>
            <h4 class="mb-0">42</h4>
          </div>
          <p class="mb-2">On route vehicles</p>
          <p class="mb-0">
            <span class="text-heading fw-medium me-2">+18.2%</span>
            <span class="text-muted">than last week</span>
          </p>
        </div>
      </div>
    </div>
    <div class="col-lg-3 col-sm-6">
      <div class="card card-border-shadow-warning h-100">
        <div class="card-body">
          <div class="d-flex align-items-center mb-2">
            <div class="avatar me-4">
              <span class="avatar-initial rounded bg-label-warning"><i class='bx bx-error bx-lg'></i></span>
            </div>
            <h4 class="mb-0">8</h4>
          </div>
          <p class="mb-2">Vehicles with errors</p>
          <p class="mb-0">
            <span class="text-heading fw-medium me-2">-8.7%</span>
            <span class="text-muted">than last week</span>
          </p>
        </div>
      </div>
    </div>
    <div class="col-lg-3 col-sm-6">
      <div class="card card-border-shadow-danger h-100">
        <div class="card-body">
          <div class="d-flex align-items-center mb-2">
            <div class="avatar me-4">
              <span class="avatar-initial rounded bg-label-danger"><i class='bx bx-git-repo-forked bx-lg'></i></span>
            </div>
            <h4 class="mb-0">27</h4>
          </div>
          <p class="mb-2">Deviated from route</p>
          <p class="mb-0">
            <span class="text-heading fw-medium me-2">+4.3%</span>
            <span class="text-muted">than last week</span>
          </p>
        </div>
      </div>
    </div>
    <div class="col-lg-3 col-sm-6">
      <div class="card card-border-shadow-info h-100">
        <div class="card-body">
          <div class="d-flex align-items-center mb-2">
            <div class="avatar me-4">
              <span class="avatar-initial rounded bg-label-info"><i class='bx bx-time-five bx-lg'></i></span>
            </div>
            <h4 class="mb-0">13</h4>
          </div>
          <p class="mb-2">Late vehicles</p>
          <p class="mb-0">
            <span class="text-heading fw-medium me-2">-2.5%</span>
            <span class="text-muted">than last week</span>
          </p>
        </div>
      </div>
  </div> --}}



@endsection


@section('components.specific_page_scripts')
@endsection


