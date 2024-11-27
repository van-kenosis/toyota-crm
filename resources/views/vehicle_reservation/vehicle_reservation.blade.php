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


{{-- Datatables --}}
<div class="row mb-4">
    <div class="col-md">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md">
                        <div class="card shadow-none border custom-card">
                            <div class="card-body">
                                <h5>Available Units</h5>
                                <div class="table-responsive">
                                    <table id="availableUnitsTable" class="table table-bordered table-hover" style="width:100%">
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
                        <div class="card shadow-none bg-transparent w-100 h-100 d-flex justify-content-center align-items-center">
                            <div class="card-body text-center">
                                <h1 class="text-primary"><b>Total Vehicle Reservation</b></h1>
                                <h1 class="text-primary" style="font-size: clamp(15rem, 6vw, 3rem);"><b id="count" ></b></h1>
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
                            <button type="button" class="btn btn-label-dark active" data-route="{{ route("vehicle.reservation.pending.list") }}">Reservation</button>
                            <button type="button" class="btn btn-label-dark" data-route="{{ route("vehicle.reservation.released.list") }}">For Release</button>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="vehicleReservationTable" class="table table-striped table-hover" style="width:100%">
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

    function reservedCount() {
        $.ajax({
            url: '{{ route("vehicle.reservation.getReservedCount") }}', // Adjust the route as necessary
            type: 'GET',
            success: function(response) {
                if (response.count !== undefined) {
                    $('#count').text(response.count); // Update the count in the HTML
                }
            },
            error: function(xhr) {
                console.error('Error fetching transaction count:', xhr);
            }
        });
    }

    reservedCount();

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
                        vehicleReservationTable.ajax.reload(null, false);
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
                    vehicleReservationTable.ajax.reload(null, false); // Reload the tables
                });

                // Add event listener to close the calendar
                closeButton.addEventListener("click", function() {
                    instance.close(); // Close the flatpickr calendar
                });
            }
        });

    // DataTable initialization
    const availableUnitsTable = $('#availableUnitsTable').DataTable({
        processing: true,
        serverSide: true, // Use client-side processing since we're providing static data
        ajax: {
                    url: '{{ route("vehicle.reservation.units.list") }}',

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
            url: '{{ route("vehicle.reservation.reservationPerTeam") }}',
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

    const vehicleReservationTable = $('#vehicleReservationTable').DataTable({
        processing: true,
        serverSide: true, // Use client-side processing since we're providing static data
        ajax: {
                url: '{{ route("vehicle.reservation.pending.list") }}',
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
        // data: [
        //     { unit: "Unit1", customer_name: "Customer 1", year_model: "2020", variant: "Variant 1", color: "Red", cs_number: "CS001", trans_type: "Type 1", trans_bank: "Bank 1", agent: "Agent 1", team: "Team 1", date_assigned: "2020-01-01", remarks: "Remark 1", action: "<button class='process-btn'>Process</button>" },
        //     { unit: "Unit2", customer_name: "Customer 2", year_model: "2021", variant: "Variant 2", color: "Blue", cs_number: "CS002", trans_type: "Type 2", trans_bank: "Bank 2", agent: "Agent 2", team: "Team 2", date_assigned: "2021-02-01", remarks: "Remark 2", action: "<button class='process-btn'>Process</button>" },
        //     { unit: "Unit3", customer_name: "Customer 3", year_model: "2022", variant: "Variant 3", color: "Green", cs_number: "CS003", trans_type: "Type 3", trans_bank: "Bank 3", agent: "Agent 3", team: "Team 3", date_assigned: "2022-03-01", remarks: "Remark 3", action: "<button class='process-btn'>Process</button>" },
        //     { unit: "Unit4", customer_name: "Customer 4", year_model: "2023", variant: "Variant 4", color: "Yellow", cs_number: "CS004", trans_type: "Type 4", trans_bank: "Bank 4", agent: "Agent 4", team: "Team 4", date_assigned: "2023-04-01", remarks: "Remark 4", action: "<button class='process-btn'>Process</button>" },
        //     { unit: "Unit5", customer_name: "Customer 5", year_model: "2024", variant: "Variant 5", color: "Purple", cs_number: "CS005", trans_type: "Type 5", trans_bank: "Bank 5", agent: "Agent 5", team: "Team 5", date_assigned: "2024-05-01", remarks: "Remark 5", action: "<button class='process-btn'>Process</button>" },
        // ],
        columns: [
            { data: 'unit', name: 'unit', title: 'Unit' },
            { data: 'client_name', name: 'client_name', title: 'Customer Name' },
            { data: 'year_model', name: 'year_model', title: 'Year Model' },
            { data: 'variant', name: 'variant', title: 'Variant' },
            { data: 'color', name: 'color', title: 'Color' },
            { data: 'cs_number', name: 'cs_number', title: 'CS Number' },
            { data: 'trans_type', name: 'trans_type', title: 'Type' },
            { data: 'trans_bank', name: 'trans_bank', title: 'Trans Bank' },
            { data: 'agent', name: 'agent', title: 'Agent' },
            { data: 'team', name: 'team', title: 'Team' },
            { data: 'date_assigned', name: 'date_assigned', title: 'Date Assigned' },
            {
                data: 'id',
                name: 'id',
                title: 'Action',
                orderable: false,
                searchable: false,
                render: function(data, type, row) {
                        return `<div class="d-flex">
                                    <button type="button" class="btn btn-icon me-2 btn-primary processing-btn" data-id="">
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

    $('.btn-group .btn').on('click', function(e) {
        e.preventDefault();
            // Clear the date range picker
        $('#date-range-picker').val(''); // Clear the date range input
        vehicleReservationTable.ajax.reload(null, false); // Reload the table without resetting the paging
        var route = $(this).data('route'); // Get the route from the clicked button
        vehicleReservationTable.ajax.url(route).load();
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
</script>


@endsection
