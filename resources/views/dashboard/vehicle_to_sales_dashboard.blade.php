@extends('components.app')
@section('content')

{{-- Title Header --}}
<div class="card bg-dark mb-5">
    <div class="card-body">
        <div class="row mb-3">
            <div class="col-md d-flex align-items-center">
                <i class='bx bxs-dashboard text-white' style="font-size: 24px;">&nbsp;</i>
                <h4 class="text-white mb-0">Vehicle to Sales</h4>
            </div>
        </div>
    </div>
</div>

{{-- Navlink Include --}}
@include('dashboard.dashboard_navlink')

{{-- Start Date - End Date Filter Group --}}
{{-- <div class="row mb-4">
    <div class="col-md d-flex justify-content-end gap-4">
        <div class="form-group text-end">
            <label for="defaultFormControlInput" class="form-label"><small>Select Start to End Date</small></label>
            <input type="text" id="date-range-picker" class="form-control form-control-sm" placeholder="Filter Date">
        </div>
    </div>
</div> --}}

{{-- Filtered Date Card --}}
{{-- <div class="row mb-4">
    <div class="col-md">
        <div class="card h-100">
            <div class="card-body">
                <div class="mb-4">
                    <div class="fw-bold text-secondary">Date</div>
                </div>
                <ul class="p-0 m-0">
                    <li class="d-flex align-items-center border-bottom mb-5">
                        <div class="avatar flex-shrink-0 me-3">
                            <span class="avatar-initial rounded bg-dark"><i class="icon-base bx bx-calendar"></i></span>
                        </div>
                        <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                        <div class="me-2">
                            <h6 class="mb-0">MONTH</h6>
                            <small>Selected Month</small>
                        </div>
                        <div class="user-progress">
                            <h5 class="mb-0" style="color: #ff0055;" id="monthRange">Present</h5>
                        </div>
                    </li>
                    <li class="d-flex align-items-center border-bottom mb-5">
                        <div class="avatar flex-shrink-0 me-3">
                            <span class="avatar-initial rounded bg-dark"><i class="icon-base bx bx-calendar-star"></i></span>
                        </div>
                        <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                        <div class="me-2">
                            <h6 class="mb-0">YEAR</h6>
                            <small>Selected Year</small>
                        </div>
                        <div class="user-progress">
                            <h5 class="mb-0" style="color: #ff0055;" id="year">2025</h5>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div> --}}

{{-- Card Deliveries Releases --}}
<div class="row mb-4">
    <div class="col-md">
        <div class="card mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="">
                        <label class="fs-4 fw-bold" style="color: #ff0055">Total Deliveries</label><br>
                        <small>Total number of Deliveries</small>
                    </div>
                    <h1 class="fw-bold" id="deliveriesCountCard" style="color: #ff0055">0</h1>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="">
                        <label class="fs-4 fw-bold" style="color: #ff0055">Total Releases</label><br>
                        <small>Total number of Releases (Posted & Released)</small>
                    </div>
                    <h1 class="fw-bold" id="releasesCountCard" style="color: #ff0055">0</h1>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection



@section('components.specific_page_scripts')
<script>
 // Initialize flatpickr for date range picker
 flatpickr("#date-range-picker", {
            mode: "range",
            dateFormat: "m/d/Y",
            onChange: function (selectedDates, dateStr, instance) {
                if (selectedDates.length === 2) {
                    const startDate = selectedDates[0];
                    const endDate = selectedDates[1];

                    showLoader();

                    if (selectedDates[1] <= selectedDates[0]) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Warning!',
                            text: 'Please select a valid date range.',
                        });
                    } else {

                        fetchInquiriesData();
                        fetchInquiriesCount();
                        fetchReservationCount();
                        fetchVehicleQuantity();

                    }

                    // Update the month and year display
                    const startMonth = startDate.toLocaleString('default', { month: 'short' });
                    const endMonth = endDate.toLocaleString('default', { month: 'short' });
                    const startYear = startDate.getFullYear();
                    const endYear = endDate.getFullYear();

                    if (startMonth === endMonth && startYear === endYear) {
                        document.getElementById('monthRange').textContent = startMonth;
                    } else {
                        const monthRange = `${startMonth} - ${endMonth}`;
                        document.getElementById('monthRange').textContent = monthRange;
                    }

                    if (startYear === endYear) {
                        document.getElementById('year').textContent = startYear;
                    } else {
                        document.getElementById('year').textContent = `${startYear} - ${endYear}`;
                    }

                    hideLoader();
                }
            },
            onReady: function (selectedDates, dateStr, instance) {
                // Create a "Clear" button
                const clearButton = document.createElement("button");
                clearButton.innerHTML = "Clear";
                clearButton.classList.add("clear-btn");

                // Create a "Close" button
                const closeButton = document.createElement("button");
                closeButton.innerHTML = "Close";
                closeButton.classList.add("close-btn");

                // Append the buttons to the flatpickr calendar
                instance.calendarContainer.appendChild(clearButton);
                instance.calendarContainer.appendChild(closeButton);

                // Add event listener to clear the date and reload the tables
                clearButton.addEventListener("click", function () {
                    instance.clear(); // Clear the date range

                    fetchInquiriesData();
                    fetchInquiriesCount();
                    fetchReservationCount();
                    fetchVehicleQuantity();


                });

                // Add event listener to close the calendar
                closeButton.addEventListener("click", function () {
                    instance.close(); // Close the flatpickr calendar
                });
            }
        });
</script>
@endsection
