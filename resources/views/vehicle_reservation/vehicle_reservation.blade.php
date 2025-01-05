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

{{-- Edit Reservation Modal --}}
<div class="modal fade" id="editReservationFormModal" tabindex="-1" aria-labelledby="largeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="largeModalLabel">Reservation Details</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="editReservationFormData">
            <div class="mb-4">
                {{-- Inquiry Type Field --}}
                <div class="row mb-2">
                    <div class="col-md">
                        <label for="edit_inquiry_type" class="form-label required">Select Inquiry Type</label>
                        <input type="text" class="form-control" id="edit_inquiry_type" name="edit_inquiry_type" placeholder="" / disabled>
                        <small class="text-danger" id="validateInquiryType">Please Select Inquiry Type</small>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-md">
                        <input type="hidden" class="form-control" id="edit_id" name="id" />
                        <label for="edit_first_name" class="form-label required">First Name</label>
                        <input type="text" class="form-control" id="edit_first_name" name="first_name" placeholder="" />
                        <small class="text-danger" id="validateFirstname">Enter Customer First Name</small>
                    </div>
                    <div class="col-md">
                        <label for="edit_last_name" class="form-label required">Last Name</label>
                        <input type="text" class="form-control" id="edit_last_name" name="last_name" placeholder="" />
                        <small class="text-danger" id="validateLastname">Enter Customer Last Name</small>
                    </div>
                </div>

                 {{-- Fleet Field --}}
                 <div class="row mb-2 d-none" id="editFleetColumnField">
                    <div class="col-md">
                        <label for="edit_fleet" class="form-label required">Fleet</label>
                        <input type="text" class="form-control" id="edit_fleet" name="fleet" placeholder="" />
                        <small class="text-danger" id="validateFleet">Enter Fleet Name</small>
                    </div>
                </div>
                {{-- Company Field --}}
                <div class="row mb-2 d-none" id="editCompanyColumnField">
                    <div class="col-md">
                        <label for="edit_company" class="form-label required">Company</label>
                        <input type="text" class="form-control" id="edit_company" name="company" placeholder="" />
                        <small class="text-danger" id="validateCompany">Enter Company Name</small>
                    </div>
                </div>
                {{-- Government Field --}}
                <div class="row mb-2 d-none" id="editGovernmentColumnField">
                    <div class="col-md">
                        <label for="edit_government" class="form-label required">Government</label>
                        <input type="text" class="form-control" id="edit_government" name="government" placeholder="" />
                        <small class="text-danger" id="validateGovernment">Enter Government Agency</small>
                    </div>
                </div>
                {{-- Gender and Age Field --}}
                <div class="row mb-2">
                    <div class="col-md" id="editGenderColumnField">
                        <label for="edit_gender" class="form-label required">Gender</label>
                        <select class="form-control" id="edit_gender" name="gender">
                            <option value="">Select Gender</option>
                            <option value="Female">Female</option>
                            <option value="Male">Male</option>
                        </select>
                        <small class="text-danger" id="validateGender">Please Select Gender</small>
                    </div>
                    <div class="col-md" id="editAgeColumnField">
                        <label for="edit_age" class="form-label required">Age</label>
                        <input type="number" class="form-control" id="edit_age" name="age" placeholder="" />
                        <small class="text-danger" id="validateLastname">Enter Customer Age</small>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-md">
                        <label for="mobile_number" class="form-label required">Mobile Number</label>
                        <input type="text" class="form-control" id="edit_mobile_number" name="mobile_number" placeholder="09" />
                        <small class="text-danger" id="validateMobileNumber">Enter Valid Mobile Number</small>
                    </div>
                    <div class="col-md">
                        <label for="edit_address" class="form-label required">Address</label>
                        <input type="text" class="form-control" id="edit_address" name="address" placeholder="" />
                        <small class="text-danger" id="validateAddress">Enter <Address></Address></small>
                    </div>
                </div>
            </div>

            <div class="mb-4">
                <div class="row mb-2">
                    <div class="col-md">
                        <label for="car_unit" class="form-label required">Unit</label>
                        <select class="form-control" id="edit_car_unit" name="car_unit">
                            <option value="">Select Unit</option>
                        </select>
                        <small class="text-danger" id="validateUnit">Please Select Unit</small>
                    </div>
                    <div class="col-md">
                        <label for="car_variant" class="form-label required">Variants</label>
                        <select class="form-control" id="edit_car_variant" name="car_variant">
                            <option value="">Select Variants</option>
                        </select>
                        <small class="text-danger" id="validateVariant required">Please Select Variant</small>
                    </div>
                    <div class="col-md">
                        <label for="car_color" class="form-label required">Color</label>
                        <select class="form-control" id="edit_car_color" name="car_color">
                            <option value="">Select Color</option>
                            <option value="any_color">Any Color</option>
                        </select>
                        <small class="text-danger" id="validateColor">Please Select Color</small>
                    </div>
                    <div class="col-md d-none" id="editQuantityColumnField">
                        <label for="edit_quantity" class="form-label">Quantity</label>
                        <input type="number" class="form-control" id="edit_quantity" name="quantity" placeholder="" readonly/>
                    </div>
                </div>
            </div>
            <div class="row mb-4">
                <div class="col-md">
                    <label for="transaction" class="form-label required">Transactions</label>
                    <select class="form-control" id="edit_transaction" name="transaction">
                        <option value="">Select Transactions</option>
                        <option value="cash">Cash</option>
                        <option value="po">PO</option>
                        <option value="financing">Financing</option>
                    </select>
                    <small class="text-danger" id="validateTransaction">Please Select Transaction</small>
                </div>
                <div class="col-md">
                    <label for="source" class="form-label required">Source</label>
                    <select class="form-control" id="edit_source" name="source" >
                        <option value="">Select Source</option>
                        <option value="Social-Media">Social-Media</option>
                        <option value="Referral">Referral</option>
                        <option value="Mall Duty">Mall Duty</option>
                        <option value="Show Room">Show Room</option>
                        <option value="Saturation">Saturation</option>
                    </select>
                    <small class="text-danger" id="validateSource">Please Select Source</small>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-md">
                    <label for="edit_category" class="form-label required">Category</label>
                    <select class="form-control" id="edit_category" name="category">
                        <option value="">Select Category</option>
                        <option class="" value="Hot" style="color: #ff0000; font-weight: bold;">Hot</option>
                        <option class="text-warning" value="Warm" style="font-weight: bold;">Warm</option>
                        <option class="text-info" value="Cold" style="font-weight: bold;">Cold</option>
                    </select>
                    <small class="text-danger" id="validateCategory">Please Select Category</small>
                </div>
            </div>
            <div class="row mb-4">
                <div class="col-md">
                    <label for="edit_payment_status" class="form-label required">Reservation Status</label>
                    <select class="form-control" id="edit_payment_status" name="payment_status">
                        <option value="none" selected>None</option>
                        <option value="paid">Paid</option>
                    </select>
                    <small class="text-danger" id="validatePaymentStatus">Please Select Payment Status</small>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-md">
                    <label for="additional_info" class="form-label">Remarks</label>
                    <textarea class="form-control" placeholder="Message" id="edit_remarks" name="additional_info" rows="3"></textarea>
                </div>
            </div>
            <div class="row">
                <div class="col-md d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-success" id="editReservationModalButton">Edit Details</button>
                    <button type="button" class="btn btn-label-danger d-none" id="cancelReservationModalButton">Cancel</button>
                    <button type="submit" class="btn btn-primary d-none" id="saveEditReservationModalButton">Save Changes</button>
                </div>
            </div>
          </form>
        </div>
      </div>
    </div>
</div>

<!-- Select CS Number Modal -->
<div class="modal fade" id="selectCSNumber" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <form id="saveCSNumber">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Select CS Number</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                  </button>
              </div>
              <div class="modal-body">
                  <div class="mb-2">
                      <div class="">Customer: <b id="customerName"></b></div>
                      <div class="">Unit: <b id="unit"></b></div>
                      <div class="">Variant: <b id="variant"></b></div>
                      <div class="">Color: <b id="color"></b></div>
                  </div>
                  <div>
                     <input type="hidden" id="transaction_id" name="transaction_id">
                      <select class="form-select" id="csNumberSelect" name="cs_number" aria-label="Default select example">
                          <option selected>CS Number</option>
                      </select>
                  </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-label-danger" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-dark ">Save changes</button>
              </div>
        </form>


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

{{-- button group --}}
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
                            <button type="button" class="btn btn-label-dark active" data-route="{{ route("vehicle.reservation.pending.list") }}">Pending</button>
                            <button type="button" class="btn btn-label-dark" data-route="{{ route("vehicle.reservation.list") }}">Reservation</button>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="vehicleReservationTable" class="table table-bordered table-hover" style="width:100%">
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

    // button group
    $(document).ready(function() {
        $('.btn-group .btn.active').click();
    });


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
            { data: 'team', name: 'team', title: 'Group' },
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

        columns: [
            { data: 'client_name', name: 'client_name', title: 'Customer Name' },
            { data: 'unit', name: 'unit', title: 'Unit' },
            { data: 'year_model', name: 'year_model', title: 'Year Model', visible: false },
            { data: 'variant', name: 'variant', title: 'Variant' },
            { data: 'color', name: 'color', title: 'Color' },
            {
                data: 'cs_number',
                name: 'cs_number',
                title: 'CS Number',
                orderable: false,
                searchable: false,
                visible: false,
                render: function(data, type, row) {
                    if (type === 'display') {
                        return `
                            <div class="d-flex">
                                <button type="button" class="badge btn me-2 btn-label-dark btn-csNumber" data-bs-toggle="modal" data-bs-target="#selectCSNumber"  data-vehicle-id="${row.vehicle_id}" data-transaction-id="${row.id}" data-unit="${row.unit}" data-variant="${row.variant}" data-color="${row.color}" data-client-name="${row.client_name}">
                                        ${data}
                                </button>
                            </div>
                        `;
                    }
                    return data; // Default display for other types like export, search, etc.
                }
            },
            { data: 'transaction', name: 'transaction', title: 'Transaction' },
            { data: 'trans_type', name: 'trans_type', title: 'Type' },
            { data: 'trans_bank', name: 'trans_bank', title: 'Trans Bank' },
            { data: 'agent', name: 'agent', title: 'Agent' },
            { data: 'team', name: 'team', title: 'Group' },
            // { data: 'date_assigned', name: 'date_assigned', title: 'Date Assigned' },
            {
                data: 'application_id',
                name: 'application_id',
                title: 'Action',
                orderable: false,
                searchable: false,
                visible: false,
                render: function(data, type, row) {
                        return `<div class="d-flex">
                                    <button type="button" class="btn btn-icon me-2 btn-success edit-btn" data-bs-toggle="modal" data-bs-target="#editReservationFormModal"  data-id="${data}">
                                        <span class="tf-icons bx bxs-show bx-22px"></span>
                                    </button>
                                    <button type="button" class="btn btn-icon me-2 btn-primary processing-pending-btn" data-id="${data}">
                                        <span class="tf-icons bx bxs-check-circle bx-22px"></span>
                                    </button>
                                    <button type="button" class="btn btn-icon me-2 btn-danger cancel-btn" data-id="${data}">
                                        <span class="tf-icons bx bxs-x-circle bx-22px"></span>
                                    </button>
                                </div>`;
                    }
            },
            {
                data: 'application_id',
                name: 'application_id',
                title: 'Action',
                orderable: false,
                searchable: false,
                visible: false,
                render: function(data, type, row) {
                        return `<div class="d-flex">
                                    @if(auth()->user()->can('process_pending_reservation'))
                                    <button type="button" class="btn btn-icon me-2 btn-primary processing-pending-btn" data-id="${data}">
                                        <span class="tf-icons bx bxs-check-circle bx-22px"></span>
                                    </button>
                                    @endif
                                    @if(auth()->user()->can('cancel_pending_reservation'))
                                      <button type="button" class="btn btn-icon me-2 btn-danger cancel-pending-btn" data-id="${data}">
                                        <span class="tf-icons bx bxs-x-circle bx-22px"></span>
                                    </button>
                                    @endif
                                </div>`;
                    }
            },
            {
                data: 'id',
                name: 'id',
                title: 'Action',
                orderable: false,
                searchable: false,
                visible: false,
                render: function(data, type, row) {
                        return `<div class="d-flex">
                                    <button type="button" class="btn btn-icon me-2 btn-primary processing-reserved-btn" data-id="${data}">
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

    // Example usage when a CS number is selected
    $('#saveCSNumber').on('submit',  function(e) {
        e.preventDefault();
        const formData = $(this).serialize();
        $.ajax({
            url: '{{ route("vehicle.reservation.addCSNumber") }}',
            type: 'POST',
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    Swal.fire(
                        'Success!',
                        response.message,
                        'success'
                    );
                    reservedCount();
                    vehicleReservationTable.ajax.reload()
                    statusTable.ajax.reload()
                    availableUnitsTable.ajax.reload()
                    $('#selectCSNumber').modal('hide'); // Close the modal upon successful save
                } else {
                    Swal.fire(
                        'Error!',
                        response.message,
                        'error'
                    );
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
    });

    $(document).on('click', '.btn-csNumber', function() {
        const vehicleId = $(this).data('vehicle-id');
        const transaction_id = $(this).data('transaction-id');
        const selectElement = $(this);

        const unit = selectElement.data('unit');
        const variant = selectElement.data('variant');
        const color = selectElement.data('color');
        const client_name = selectElement.data('client-name');

        $('#transaction_id').val(transaction_id);
        $('#customerName').text(client_name);
        $('#unit').text(unit);
        $('#variant').text(variant);
        $('#color').text(color);

        $.ajax({
            url: `/get-cs-number/${vehicleId}`,
            type: 'GET',
            data: {
                unit: unit,
                variant: variant,
                color: color
            },
            dataType: 'json',
            success: function(data) {
                let numberSelect = $('#csNumberSelect');
                numberSelect.empty();
                numberSelect.append('<option value="">Select CS Number...</option>');
                if (data.length > 0) {
                    data.forEach(function(item) {
                        numberSelect.append(`<option value="${item.CS_number}">${item.CS_number}</option>`);
                    });
                } else {
                    numberSelect.append('<option value="">Insufficient inventory for this unit</option>');
                }
            },
            error: function(error) {
                console.error('Error loading CS Number:', error);
            }
        });


    });


    // button group active tabs
    $('.btn-group .btn').on('click', function(e) {
        e.preventDefault();
        $('#date-range-picker').val('');

        // Toggle column visibility based on the active tab
        const isReservationTab = $(this).text().trim() === 'Reservation';
        vehicleReservationTable.column(2).visible(isReservationTab); // year_model
        @if(auth()->user()->can('add_cs_number') && auth()->user()->can('get_cs_number'))
        vehicleReservationTable.column(5).visible(isReservationTab); // cs_number
        @endif

        @if(auth()->user()->can('process_reserved_reservation'))
        vehicleReservationTable.column(13).visible(isReservationTab); // application_id
        @endif

        const isPendingTab = $(this).text().trim() === 'Pending';
        @if(auth()->user()->can('process_pending_reservation') || auth()->user()->can('cancel_pending_reservation'))
        vehicleReservationTable.column(12).visible(isPendingTab); // id
        @endif

        var route = $(this).data('route');
        vehicleReservationTable.ajax.url(route).load();
    });

    // datatables button tabs
    $(document).ready(function() {
        $('.btn-group .btn').on('click', function() {
            // Remove 'active' class from all buttons in the group
            $('.btn-group .btn').removeClass('active');
            // Add 'active' class to the clicked button
            $(this).addClass('active');
            $('#date-range-picker').val(''); // Clear the date range input
            vehicleReservationTable.ajax.reload(null, false); // Reload the table without resetting the paging
            var route = $(this).data('route'); // Get the route from the clicked button
            vehicleReservationTable.ajax.url(route).load();
        });
    });

      //Process Data
    $(document).on('click', '.processing-pending-btn', function() {
        const appID = $(this).data('id');

        Swal.fire({
            title: 'Are you sure?',
            text: "Do you want to reserved this transaction?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, reserved it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ route("vehicle.reservation.processing_pending") }}',
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
                            vehicleReservationTable.ajax.reload();
                            statusTable.ajax.reload();
                            availableUnitsTable.ajax.reload();
                            reservedCount();
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

    $(document).on('click', '.processing-reserved-btn', function() {
        const appID = $(this).data('id');

        Swal.fire({
            title: 'Are you sure?',
            text: "Do you want to proceed this to pending for release?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, proceed it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ route("vehicle.reservation.processing_reserved") }}',
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
                            vehicleReservationTable.ajax.reload();
                            statusTable.ajax.reload();
                            availableUnitsTable.ajax.reload();
                            reservedCount();
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

    $(document).on('click', '.cancel-pending-btn', function() {
        const appID = $(this).data('id');
        console.log(appID);

        Swal.fire({
            title: 'Are you sure?',
            text: "Do you want to cancel this application?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, cancel it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ route("vehicle.reservation.cancel.pending") }}', // Ensure this route is defined in your routes
                    type: 'POST',
                    data: {
                        id: appID,
                        _token: '{{ csrf_token() }}' // Include CSRF token
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire(
                                'Deleted!',
                                response.message,
                                'success'
                            );
                            vehicleReservationTable.ajax.reload(); // Reload the DataTable
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

     // Edit Reservation Modal Fields disabled state -> Editable State
     $(document).ready(function () {
        // Function to reset the modal to its initial uneditable state
        function resetModalToInitialState() {
            // Disable all input fields except the Edit button
            $("#editReservationFormData :input").not("#editReservationModalButton").prop("disabled", true);

            // Show the Edit button
            $("#editReservationModalButton").removeClass("d-none");

            // Hide the Save and Cancel buttons
            $("#saveEditReservationModalButton").addClass("d-none");
            $("#cancelReservationModalButton").addClass("d-none");
        }

        // Initially, reset the modal to its initial state when the page is ready
        resetModalToInitialState();

        // When the Edit button is clicked
        $("#editReservationModalButton").on("click", function () {
            // Enable all input fields except hidden fields
            $("#editReservationFormData :input").not("[type='hidden']").prop("disabled", false);

            // Hide the Edit button
            $(this).addClass("d-none");

            // Show the Save Changes and Cancel buttons
            $("#saveEditReservationModalButton").removeClass("d-none");
            $("#cancelReservationModalButton").removeClass("d-none");
        });

        // When the Cancel button is clicked
        $("#cancelReservationModalButton").on("click", function () {
            // Close the modal properly
            $("#editReservationFormModal").modal("hide");

            // Reset the modal to its initial uneditable state when reopened
            resetModalToInitialState();
        });

        // Reset the modal when it's closed (using Bootstrap modal `hidden.bs.modal` event)
        $("#editReservationFormModal").on("hidden.bs.modal", function () {
            resetModalToInitialState();
        });
    });

</script>


@endsection
