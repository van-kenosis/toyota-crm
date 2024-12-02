@extends('components.app')

@section('content')

{{-- Title Header --}}
<div class="card bg-dark shadow-none mb-4">
    <div class="card-body">
        <div class="d-flex align-items-center">
            <i class='bx bxs-car text-white' style="font-size: 24px;">&nbsp;</i>
            <h4 class="text-white mb-0">Vehicle Reservation</h4>
        </div>
    </div>
</div>

{{-- Header Datatables --}}
<div class="row mb-4">
    <div class="col-md">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md">
                        <div class="card shadow-none border custom-card">
                            <div class="card-body">
                                <h5>Releases Units</h5>
                                <div class="table-responsive">
                                    <table id="releasedUnitsTable" class="table table-bordered table-hover" style="width:100%">
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md">
                        <div class="card shadow-none border custom-card">
                            <div class="card-body">
                                <h5>Status</h5>
                                <div class="table-responsive">
                                    <table id="statusTable" class="table table-bordered table-hover" style="width:100%">
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md">
                        <div class="card shadow-none bg-transparent border d-flex justify-content-center align-items-center mb-2">
                            <div class="card-body text-center">
                                <h3 class="text-primary"><b>Total Vehicle Released</b></h3>
                                <h1 class="text-primary" style="font-size: clamp(8rem, 6vw, 3rem);"><b id="releasedCount" >0</b></h1>
                            </div>
                        </div>
                        <div class="card shadow-none bg-transparent border d-flex justify-content-center align-items-center">
                            <div class="card-body text-center">
                                <h3 class="text-primary"><b>Pending Vehicle for Releases</b></h3>
                                <h1 class="text-primary" style="font-size: clamp(8rem, 6vw, 3rem);"><b id="pendingForReleaseCount" >0</b></h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mb-2">
    <div class="col">
        <div class="card custom-card">
            <div class="card-body">
                <div class="row">
                    <div class="d-flex w-50 gap-2">
                        <div class="mb-3">
                            <div class="input-group">
                                <input type="text" id="date-range-picker" class="form-control" placeholder="Select date range">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md">
                        <div class="btn-group w-100" role="group" aria-label="Basic example">
                            <button id="forRelease" type="button" class="btn btn-label-dark active" data-route="{{ route("vehicle.releases.pending.list") }}">For Release Units</button>
                            <button id="released" type="button" class="btn btn-label-dark" data-route="{{ route("vehicle.releases.list") }}">Released Units</button>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="vehicleReleasesTable" class="table table-striped table-hover" style="width:100%">
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection


@section('components.specific_page_scripts')

<script>

    function releasedCount() {
        $.ajax({
            url: '{{ route("vehicle.releases.getReleasedCount") }}', // Adjust the route as necessary
            type: 'GET',
            success: function(response) {
                if (response.releasedCount !== undefined) {
                    $('#releasedCount').text(response.releasedCount); // Update the count in the HTML
                    $('#pendingForReleaseCount').text(response.pendingForReleaseCount); // Update the count in the HTML
                }
            },
            error: function(xhr) {
                console.error('Error fetching transaction count:', xhr);
            }
        });
    }

    releasedCount();

    //Date filter
    flatpickr("#date-range-picker", {
        mode: "range",
        dateFormat: "m/d/Y",
        onChange: function(selectedDates, dateStr, instance) {
            // Check if both start and end dates are selected
            if (selectedDates.length === 2) {
                // Check if the end date is earlier than or equal to the start date
                if (selectedDates[1] <= selectedDates[0]) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Warning!',
                        text: 'Please select a valid date range.',
                    });
                } else {
                    // Reload the tables if a valid range is selected
                    vehicleReleasesTable.ajax.reload(null, false);
                }
            }
        },
        // Add clear button
        onReady: function(selectedDates, dateStr, instance) {
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
            clearButton.addEventListener("click", function() {
                instance.clear(); // Clear the date range
                vehicleReleasesTable.ajax.reload(null, false); // Reload the tables
            });

            // Add event listener to close the calendar
            closeButton.addEventListener("click", function() {
                instance.close(); // Close the flatpickr calendar
            });
        }
    });

    // datatables button tabs
    $(document).ready(function() {
        $('.btn-group .btn').on('click', function() {
            // Remove 'active' class from all buttons in the group
            $('.btn-group .btn').removeClass('active');
            // Add 'active' class to the clicked button
            $(this).addClass('active');
        });
     });

    // DataTable initialization
    const releasedUnitsTable = $('#releasedUnitsTable').DataTable({
        processing: true,
        serverSide: true, // Use client-side processing since we're providing static data
        ajax: {
            url: '{{ route("vehicle.releases.units.list") }}',
        },
        pageLength: 10,
        paging: true,
        responsive: true,
        dom: '<"top"lf>rt<"bottom"ip>',
        language: {
            search: "",
            searchPlaceholder: "Search...",
            info: "", // Remove "Showing X to Y of Z entries"
            infoEmpty: "", // Removes the message when there's no data
            infoFiltered: "", // Removes the "filtered from X entries" part
        },
        columns: [
            { data: 'unit', name: 'unit', title: 'Unit' },
            { data: 'quantity', name: 'quantity', title: 'Quantity' },
        ],
        order: [[0, 'desc']],  // Sort by 'unit' column by default
        columnDefs: [
            {
                targets: [0, 1], // Columns to apply additional formatting (if needed)
            }
        ],
    });

    const statusTable = $('#statusTable').DataTable({
        processing: true,
        serverSide: true, // Use client-side processing since we're providing static data
        ajax: {
            url: '{{ route("vehicle.releases.releasedPerTeam") }}',
        },
        pageLength: 10,
        paging: true,
        responsive: true,
        dom: '<"top"lf>rt<"bottom"ip>',
        language: {
            search: "",
            searchPlaceholder: "Search...",
            info: "", // Remove "Showing X to Y of Z entries"
            infoEmpty: "", // Removes the message when there's no data
            infoFiltered: "", // Removes the "filtered from X entries" part
        },
        data: [
            { team: "EOV", quantity: 5 },
            { team: "JDS", quantity: 3 },
            { team: "IBT", quantity: 2 },
            { team: "EDJ", quantity: 4 },
            { team: "JLB", quantity: 1 },
        ],
        columns: [
            { data: 'team', name: 'team', title: 'Team' },
            { data: 'quantity', name: 'quantity', title: 'Quantity' },
        ],
        order: [[0, 'desc']],  // Sort by 'unit' column by default
        columnDefs: [
            {
                targets: [0, 1], // Columns to apply additional formatting (if needed)
            }
        ],
    });

    const vehicleReleasesTable = $('#vehicleReleasesTable').DataTable({
        processing: true,
        serverSide: true, // Use client-side processing since we're providing static data
        ajax: {
            url: '{{ route("vehicle.releases.pending.list") }}',
            data: function(d) {
                d.date_range = $('#date-range-picker').val();
            },
        },
        pageLength: 10,
        paging: true,
        responsive: true,
        dom: '<"top"lf>rt<"bottom"ip>',
        language: {
            search: "",
            searchPlaceholder: "Search..."
        },

        columns: [
            { data: 'unit', name: 'unit', title: 'Unit' },
            { data: 'customer_name', name: 'customer_name', title: 'Customer Name' },
            { data: 'year_model', name: 'year_model', title: 'Year Model' },
            { data: 'variant', name: 'variant', title: 'Variant' },
            { data: 'color', name: 'color', title: 'Color' },
            { data: 'cs_number', name: 'cs_number', title: 'CS Number' },
            { data: 'trans_type', name: 'trans_type', title: 'Trans Type' },
            { data: 'trans_bank', name: 'trans_bank', title: 'Trans Bank' },
            { data: 'agent', name: 'agent', title: 'Agent' },
            { data: 'team', name: 'team', title: 'Team' },
            { data: 'date_assigned', name: 'date_assigned', title: 'Date Assigned' },
            { data: 'status', name: 'status', title: 'Status' },
            // { data: 'remarks', name: 'remarks', title: 'Remarks' },
            {
                data: 'id',
                name: 'id',
                title: 'Action',
                orderable: false,
                searchable: false,
                visible:false,
                render: function(data, type, row) {
                        return `<div class="d-flex">
                                    <button type="button" class="btn btn-icon me-2 btn-primary processing-btn" data-id="${data}">
                                        <span class="tf-icons bx bxs-check-circle bx-22px"></span>
                                    </button>
                                </div>`;
                    }
            },
        ],
        order: [[0, 'desc']],  // Sort by 'unit' column by default
        columnDefs: [
            {
                targets: [0, 1], // Columns to apply additional formatting (if needed)
            }
        ],
    });

    // button group active tabs
    $('.btn-group .btn').on('click', function(e) {
        e.preventDefault();
        $('#date-range-picker').val('');

        // Toggle column visibility based on the active tab
        const isFoReleasedTab = $(this).text().trim() === 'For Release Units';
        vehicleReleasesTable.column(12).visible(isFoReleasedTab);
        var route = $(this).data('route');
        vehicleReleasesTable.ajax.url(route).load();
    });


    // datatables button tabs
    $(document).ready(function() {
        $('.btn-group .btn').on('click', function() {
            // Remove 'active' class from all buttons in the group
            $('.btn-group .btn').removeClass('active');
            // Add 'active' class to the clicked button
            $(this).addClass('active');

            $('#date-range-picker').val(''); // Clear the date range input
            vehicleReleasesTable.ajax.reload(null, false); // Reload the table without resetting the paging
            //  var route = $(this).data('route'); // Get the route from the clicked button
            // vehicleReleasesTable.ajax.url(route).load();
        });
    });

    //Process Data
    $(document).on('click', '.processing-btn', function() {
        const appID = $(this).data('id');

        Swal.fire({
            title: 'Are you sure?',
            text: "Do you want to proceed this transaction?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, proceed it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ route("vehicle.releases.processing") }}',
                    type: 'POST',
                    data: {
                        id: appID
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire(
                                'Updated!',
                                response.message,
                                'success'
                            );
                            vehicleReleasesTable.ajax.reload();
                            statusTable.ajax.reload();
                            releasedUnitsTable.ajax.reload();
                            releasedCount();
                        }
                    },
                    error: function(xhr) {
                        Swal.fire(
                            'Error!',
                            xhr.responseJSON?.message || 'Something went wrong!',
                            'error'
                        );
                    }
                });
            }
        });
    });
</script>


@endsection
