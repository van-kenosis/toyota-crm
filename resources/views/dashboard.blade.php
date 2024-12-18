@extends('components.app')

@section('content')

{{-- Title Header --}}
<div class="card bg-dark shadow-none mb-4">
    <div class="card-body">
        <div class="row mb-3">
            <div class="col-md d-flex align-items-center">
                <i class='bx bxs-dashboard text-white' style="font-size: 24px;">&nbsp;</i>
                <h4 class="text-white mb-0">Dashboard</h4>
            </div>
        </div>
        <div class="row">
            <div class="col-md">
                <div class="d-flex justify-content-between">
                    <div class="d-flex align-items-center gap-1">
                        {{-- <i class='bx bx-calendar fs-4 text-warning'></i> --}}
                        <div id="liveDate" class="text-warning fs-6"></div>
                    </div>
                    <div class="d-flex align-items-center gap-1">
                        {{-- <i class='bx bx-time-five fs-4 text-warning'></i> --}}
                        <div id="liveTime" class="text-warning fs-6"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card bg-label-warning shadow-none">
    <div class="card-body">
        <div class="row mb-2">
            <div class="col-md d-flex justify-content-center">
                <div class="row"><i class='bx bxs-dashboard fs-1'></i></div>
            </div>
        </div>
        <div class="row">
            <div class="col-md d-flex justify-content-center">
                <div class="row"><h1 class="text-warning">"Dashboard Under Development"</h1></div>
            </div>
        </div>
    </div>
</div>

{{-- <div class="row mb-4">
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
</div> --}}



@endsection


@section('components.specific_page_scripts')
<script>
    function updateTimeAndDate() {
    const now = new Date();

    // Format time (HH:MM:SS)
    const time = now.toLocaleTimeString();

    // Format date (e.g., Monday, December 16, 2024)
    const dateOptions = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    const date = now.toLocaleDateString(undefined, dateOptions);

    // Update the DOM using jQuery
    $('#liveTime').text(time);
    $('#liveDate').text(date);
}

// Update time and date every second
setInterval(updateTimeAndDate, 1000);

// Initial call to display immediately
updateTimeAndDate();

</script>
@endsection


