@extends('components.app')

@section('content')

{{-- Title Header --}}
<div class="card bg-dark shadow-none mb-4">
    <div class="card-body">
        <div class="d-flex align-items-center">
            <i class='bx bxs-car-garage text-white' style="font-size: 24px;">&nbsp;</i>
            <h4 class="text-white mb-0">Vehicle Inventory</h4>
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
                        <div class="card shadow-none border custom-card">
                            <div class="card-body">
                                <h5>Incoming Units</h5>
                                <div class="table-responsive">
                                    <table id="incomingUnitsTable" class="table table-bordered table-hover" style="width:100%">
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
                                <h1 class="text-primary"><b>Total Inventory</b></h1>
                                <h1 class="text-primary" style="font-size: clamp(15rem, 6vw, 3rem);"><b id="totalInventory" >0</b></h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


{{-- Vehicle Form --}}
<div class="row mb-4 d-none">
    <div class="col-md">
        <div class="card" id="vehicleFormCard"">
            <div class="card-header">
                <h5 class="text-primary card-title">Vehicle Form</h5>
            </div>
            <div class="card-body">
                <form id="vehicleFormData">
                    <div class="row">
                        <div class="col-md">
                            hello
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-label-danger" id="cancelInquiryFormButton">Cancel</button>
                            <button type="submit" class="btn btn-success">Add to Inventory</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Trigger Inquiry Form Button --}}
<div class="row mb-2">
    <div class="col-md d-flex justify-content-end">
        <button class="btn btn-primary" id="addVehicleButton">Add Vehicle</button>
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
                <div class="table-responsive">
                    <table id="vehicleInventoryTable" class="table table-striped table-hover" style="width:100%">
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

    function totalInventory() {
        $.ajax({
            url: '{{ route("vehicle.inventory.getTotalInventory") }}', // Adjust the route as necessary
            type: 'GET',
            success: function(response) {
                if (response.totalInventory !== undefined) {
                    $('#totalInventory').text(response.totalInventory); // Update the count in the HTML
                }
            },
            error: function(xhr) {
                console.error('Error fetching transaction count:', xhr);
            }
        });
    }

    totalInventory();

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
                    vehicleInventoryTable.ajax.reload(null, false);
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
                vehicleInventoryTable.ajax.reload(null, false); // Reload the tables
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

    const incomingUnitsTable = $('#incomingUnitsTable').DataTable({
        processing: true,
        serverSide: false, // Use client-side processing since we're providing static data
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
            { for: "Invoice", quantity: 5 },
            { for: "Pull-Out", quantity: 3 },
            { for: "Transit", quantity: 2 },
        ],
        columns: [
            { data: 'for', name: 'for', title: 'For' },
            { data: 'quantity', name: 'quantity', title: 'Quantity' },
        ],
        order: [[0, 'desc']],  // Sort by 'unit' column by default
        columnDefs: [
            {
                targets: [0, 1], // Columns to apply additional formatting (if needed)
            }
        ],
    });

    const vehicleInventoryTable = $('#vehicleInventoryTable').DataTable({
        processing: true,
        serverSide: true, // Use client-side processing since we're providing static data
        ajax: {
            url: '{{ route("vehicle.inventory.list") }}',
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
        //     { unit: "Unit1", model: "Model 1", color: "Red", cs_number: "CS001", actual_invoice_date: "2020-01-01", delivery_date: "2020-01-15", invoice_no: "INV001", tags: "Tag 1", team: "Team 1", date_assigned: "2020-01-01", age: "1 year", status: "Active", remarks: "Remark 1", action: "<button class='process-btn'>Process</button>" },
        //     { unit: "Unit2", model: "Model 2", color: "Blue", cs_number: "CS002", actual_invoice_date: "2021-02-01", delivery_date: "2021-02-15", invoice_no: "INV002", tags: "Tag 2", team: "Team 2", date_assigned: "2021-02-01", age: "2 years", status: "Active", remarks: "Remark 2", action: "<button class='process-btn'>Process</button>" },
        //     { unit: "Unit3", model: "Model 3", color: "Green", cs_number: "CS003", actual_invoice_date: "2022-03-01", delivery_date: "2022-03-15", invoice_no: "INV003", tags: "Tag 3", team: "Team 3", date_assigned: "2022-03-01", age: "3 years", status: "Active", remarks: "Remark 3", action: "<button class='process-btn'>Process</button>" },
        //     { unit: "Unit4", model: "Model 4", color: "Yellow", cs_number: "CS004", actual_invoice_date: "2023-04-01", delivery_date: "2023-04-15", invoice_no: "INV004", tags: "Tag 4", team: "Team 4", date_assigned: "2023-04-01", age: "4 years", status: "Active", remarks: "Remark 4", action: "<button class='process-btn'>Process</button>" },
        //     { unit: "Unit5", model: "Model 5", color: "Purple", cs_number: "CS005", actual_invoice_date: "2024-05-01", delivery_date: "2024-05-15", invoice_no: "INV005", tags: "Tag 5", team: "Team 5", date_assigned: "2024-05-01", age: "5 years", status: "Active", remarks: "Remark 5", action: "<button class='process-btn'>Process</button>" },
        // ],
        columns: [
            { data: 'unit', name: 'unit', title: 'Unit' },
            { data: 'model', name: 'model', title: 'Model' },
            { data: 'color', name: 'color', title: 'Color' },
            { data: 'cs_number', name: 'cs_number', title: 'CS Number' },
            { data: 'actual_invoice_date', name: 'actual_invoice_date', title: 'Actual Invoice Date' },
            { data: 'delivery_date', name: 'delivery_date', title: 'Delivery Date' },
            { data: 'invoice_number', name: 'invoice_number', title: 'Invoice No.' },
            { data: 'tags', name: 'tags', title: 'TAGs' },
            // { data: 'team', name: 'team', title: 'Team' },
            // { data: 'date_assigned', name: 'date_assigned', title: 'Date Assigned' },
            { data: 'age', name: 'age', title: 'Age' },
            { data: 'status', name: 'status', title: 'Status' },
            { data: 'remarks', name: 'remarks', title: 'Remarks' },
            {
                data: 'id',
                name: 'id',
                title: 'Action',
                orderable: false,
                searchable: false,
                render: function(data, type, row) {
                        return `<div class="d-flex">
                                    <button type="button" class="btn btn-icon me-2 btn-success edit-btn" data-bs-toggle="modal" data-bs-target="#editInquiryFormModal" data-id="">
                                    <span class="tf-icons bx bx-pencil bx-22px"></span>
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


    // datatables button tabs
    $(document).ready(function() {

        $('.btn-group .btn').on('click', function() {
            $('.btn-group .btn').removeClass('active');
            $(this).addClass('active');
            $('#date-range-picker').val(''); // Clear the date range input
            vehicleInventoryTable.ajax.reload(null, false); // Reload the table without resetting the paging

        });
    });
</script>


@endsection
