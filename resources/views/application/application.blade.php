@extends('components.app')

@section('content')

{{-- Page Title --}}
<div class="card bg-dark shadow-none mb-4">
    <div class="card-body">
        <div class="d-flex align-items-center">
            <i class='bx bx-list-plus text-white' style="font-size: 24px;">&nbsp;</i>
            <h4 class="text-white mb-0">Application</h4>
        </div>
    </div>
</div>

<!-- Modal for Adding Banks -->
<div class="modal fade" id="selectBankModal" tabindex="-1" role="dialog" aria-labelledby="addBankModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header d-flex align-items-center gap-2">
                <div class="d-flex align-items-center gap-2">
                    <i class='bx bxs-bank' ></i>
                    <h5 class="modal-title" id="largeModalLabel">Select Banks</h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="selectBankForm">
                    <input type="hidden" name="application_id" id="application_id">
                    <div id="bankFieldsContainer">
                        <div class="row mb-2">
                            <div class="col-md">
                                <button type="button" class="btn btn-label-dark" id="addBankFieldButton">
                                    {{-- <span class="tf-icons bx bxs-plus-circle bx-22px"></span> --}}
                                    Add More Field
                                </button>
                            </div>
                        </div>
                        <!-- Bank Field Template -->
                        <div class="row mb-2 bank-field">
                            <div class="col-md">
                                <select class="form-control" name="bank_id[]" id=bank_ids>
                                </select>
                            </div>

                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-label-danger" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-dark" form="selectBankForm">Add these Banks</button>
            </div>
        </div>
    </div>
</div>


{{-- Edit Application Modal --}}
<div class="modal fade" id="editApplicationFormModal" tabindex="-1" aria-labelledby="largeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="largeModalLabel">Application Details</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="editApplicationFormData">
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
                        <input type="number" class="form-control" id="edit_quantity" name="quantity" placeholder="" />
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
                        <option value="Referal">Referal</option>
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
                    <button type="button" class="btn btn-success" id="editApplicationModalButton">Edit Details</button>
                    <button type="button" class="btn btn-label-danger d-none" id="cancelApplicationModalButton">Cancel</button>
                    <button type="submit" class="btn btn-primary d-none" id="saveEditApplicationModalButton">Save Changes</button>
                </div>
            </div>
          </form>
        </div>
      </div>
    </div>
</div>

{{-- Datatables Tabs --}}
<div class="row mb-2">
    <div class="col">
        <div class="card custom-card">
            <div class="card-body">
                <div class="row mb-2">
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
                            <button type="button" class="btn btn-label-dark active" data-route="{{ route('application.pending') }}">Pending</button>
                            <button type="button" class="btn btn-label-dark" data-route="{{ route('application.approved') }}">Approved</button>
                            <button type="button" class="btn btn-label-dark" data-route="{{ route('application.cancel') }}">Denied/Canceled</button>
                            <button type="button" class="btn btn-label-dark" data-route="{{ route('application.cash') }}">Cash</button>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="applicationTable" class="table table-bordered table-hover" style="width:100%">
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
                    applicationTable.ajax.reload(null, false);
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
                applicationTable.ajax.reload(null, false); // Reload the tables
            });

            // Add event listener to close the calendar
            closeButton.addEventListener("click", function() {
                instance.close(); // Close the flatpickr calendar
            });
        }
    });

    // DataTable initialization
    const applicationTable = $('#applicationTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("application.pending") }}',
            data: function(d) {
                d.date_range = $('#date-range-picker').val();
            },
        },
        pageLength: 10,
        paging: true,
        responsive: false,
        dom: '<"top"lf>rt<"bottom"ip>',
        language: {
            search: "",
            searchPlaceholder: "Search..."
        },
        columns: [
            { data: 'id', name: 'id', title: 'ID' , visible: false},
            { data: 'team', name: 'team', title: 'Team' },
            { data: 'type', name: 'type', title: 'Type' },
            { data: 'agent', name: 'agent', title: 'Agent' },
            { data: 'client_name', name: 'client_name', title: 'Customer' },
            { data: 'contact_number', name: 'contact_number', title: 'Contact No.' },
            { data: 'unit', name: 'unit', title: 'Unit' },
            { data: 'variant', name: 'variant', title: 'Variant' },
            { data: 'color', name: 'color', title: 'Color' },
            { data: 'transaction', name: 'transaction', title: 'Transaction' },
            { data: 'source', name: 'source', title: 'Source' },
            { data: 'date', name: 'date', title: 'Date' },
            {
                data: 'id',
                title: 'Bank',
                orderable: false,
                searchable: false,
                render: function(data) {
                    return `
                            <div class="d-flex">
                                <button type="button" class="btn btn-icon me-2 btn-warning bank-btn" data-bs-toggle="modal" data-bs-target="#selectBankModal" data-id="${data}">
                                    <span class="tf-icons bx bxs-bank bx-22px"></span>
                                </button>
                                <button type="button" class="btn btn-icon me-2 btn-info bank-btn" data-bs-toggle="modal" data-bs-target="#bankApprovalDateModal" data-id="">
                                    <span class="tf-icons bx bxs-bank bx-22px"></span>
                                </button>
                            </div>
                            `;
                }
            },
            {
                data: 'id',
                title: 'Action',
                orderable: false,
                searchable: false,
                render: function(data) {
                    return `
                        <div class="d-flex">
                            <button type="button" class="btn btn-icon me-2 btn-success edit-btn" data-bs-toggle="modal" data-bs-target="#editApplicationFormModal"  data-id="${data}">
                                <span class="tf-icons bx bxs-show bx-22px"></span>
                            </button>
                            <button type="button" class="btn btn-icon me-2 btn-primary processing-btn" data-id="${data}">
                                <span class="tf-icons bx bxs-check-circle bx-22px"></span>
                            </button>
                            <button type="button" class="btn btn-icon me-2 btn-danger cancel-btn" data-id="${data}">
                                <span class="tf-icons bx bxs-x-circle bx-22px"></span>
                            </button>
                        </div>
                    `;
                }
            }
        ],
        order: [[0, 'desc']],  // Sort by date created by default
        columnDefs: [
            {
                type: 'created_at',
                targets: [0, 1] // Apply date sorting to date_received and date_on_hold columns
            }
        ],
    });

    // Change DataTable route based on button click
    $('.btn-group .btn').on('click', function(e) {
        e.preventDefault();
          // Clear the date range picker
        $('#date-range-picker').val(''); // Clear the date range input
        applicationTable.ajax.reload(null, false); // Reload the table without resetting the paging
        var route = $(this).data('route'); // Get the route from the clicked button
        applicationTable.ajax.url(route).load();
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

     // Inquiry Form Validation
     $(document).ready(function () {



        $.ajax({
            url: '{{ route('leads.getUnit') }}',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                let unitSelect = $('#edit_car_unit');
                unitSelect.empty();
                unitSelect.append('<option value="">Select Unit...</option>');
                data.forEach(function(item) {
                    unitSelect.append(`<option value="${item.unit}">${item.unit}</option>`);
                });
            },
            error: function(error) {
                console.error('Error loading unit:', error);
            }
        });


        // Load variants and colors based on selected unit
        $('#edit_car_unit').on('change', function() {
            const selectedUnit = $(this).val();
            if (selectedUnit) {
                $.ajax({
                    url: '{{ route("leads.getVariants") }}',
                    type: 'GET',
                    data: { unit: selectedUnit },
                    dataType: 'json',
                    success: function(data) {
                        let variantSelect = $('#edit_car_variant');
                        variantSelect.empty();
                        variantSelect.append('<option value="">Select Variants...</option>');
                        // Check if data.variants is an array or a single value
                        if (Array.isArray(data.variants)) {
                            data.variants.forEach(function(variant) {
                                variantSelect.append(`<option value="${variant}">${variant}</option>`);
                            });
                        } else {
                            variantSelect.append(`<option value="${data.variants}">${data.variants}</option>`);
                        }
                    },
                    error: function(error) {
                        console.error('Error loading variants and colors:', error);
                    }
                });
            } else {
                // Clear the selects if no unit is selected
                $('#car_variant').empty().append('<option value="">Select Variants...</option>');
            }
        });


        $('#edit_car_variant').on('change', function() {
            const selectedVariant = $(this).val();
            if (selectedVariant) {
                $.ajax({
                    url: '{{ route("leads.getColor") }}',
                    type: 'GET',
                    data: { variant: selectedVariant },
                    dataType: 'json',
                    success: function(data) {

                        let colorSelect = $('#edit_car_color');
                        colorSelect.empty();
                        colorSelect.append('<option value="">Select Color...</option>');
                        // Check if data.colors is an array or a single value
                        if (Array.isArray(data.colors)) {
                            data.colors.forEach(function(color) {
                                colorSelect.append(`<option value="${color}">${color}</option>`);
                            });
                        } else {
                            colorSelect.append(`<option value="${data.colors}">${data.colors}</option>`);
                        }

                        if (!Array.isArray(data.colors) || !data.colors.includes('Any Color')) {
                            colorSelect.append('<option value="Any Color">Any Color</option>');
                        }

                    },
                    error: function(error) {
                        console.error('Error loading variants and colors:', error);
                    }
                });
            } else {
                // Clear the selects if no unit is selected
                $('#car_color').empty().append('<option value="">Select Color...</option>');
            }
        });

        // Hide warning messages initially
        $("small").hide();

        // Helper function to capitalize first letter of each word
        function capitalizeWords(str) {
            return str.replace(/\b\w/g, function (txt) {
                return txt.toUpperCase();
            });
        }


        function validateField(field, message) {
            const $field = $(field);
            const $errorMsg = $field.siblings('small');
            if (!$field.val()) {
                $field.addClass('is-invalid border-danger');
                $errorMsg.show();
                return false;
            }
            $field.removeClass('is-invalid border-danger');
            $errorMsg.hide();
            return true;
        }


        // Validate form on submit
        $("#editApplicationFormData").on("submit", function (e) {
            e.preventDefault();
            let isValid = true;

            const edit_inquiryType = $('#edit_inquiry_type').val();


            // Always validate inquiry type
            isValid = validateField('#edit_inquiry_type', 'Select Inquiry Type') && isValid;

            if (edit_inquiryType === 'Individual') {
                isValid = validateField('#edit_first_name', 'Enter Customer First Name') && isValid;
                isValid = validateField('#edit_last_name', 'Enter Customer Last Name') && isValid;
                isValid = validateField('#edit_age', 'Enter Customer Age') && isValid;
                isValid = validateField('#edit_gender', 'Please Select Gender') && isValid;

            } else if (edit_inquiryType === 'Fleet') {
                isValid = validateField('#edit_fleet', 'Enter Fleet Name') && isValid;

            } else if (edit_inquiryType === 'Company') {
                isValid = validateField('#edit_company', 'Enter Company Name') && isValid;

            } else if (edit_inquiryType === 'Government') {
                isValid = validateField('#edit_government', 'Enter Government Agency') && isValid;
            }

            // Validate required fields
            isValid = validateField('#edit_mobile_number', 'Enter Valid Mobile Number') && isValid;
            isValid = validateField('#edit_car_unit', 'Please Select Unit') && isValid;
            isValid = validateField('#edit_car_variant', 'Please Select Variant') && isValid;
            isValid = validateField('#edit_car_color', 'Please Select Color') && isValid;
            isValid = validateField('#edit_transaction', 'Please Select Transaction') && isValid;
            isValid = validateField('#edit_source', 'Please Select Source') && isValid;
            isValid = validateField('#edit_address', 'Enter Address') && isValid;

            // Special validation for mobile number
            const mobileNumber = $('#edit_mobile_number').val();
            if (mobileNumber && !mobileNumber.match(/^09\d{9}$/)) {
                $('#edit_mobile_number').addClass('is-invalid border-danger');
                $('#validateMobileNumber').text('Invalid Mobile Number').show();
                isValid = false;
            } else {
                $('#edit_mobile_number').removeClass('is-invalid border-danger');
                $('#validateMobileNumber').hide();
            }

            console.log(isValid);
            console.log(edit_inquiry_type);

            // Restore original values on invalid fields
            if (!isValid) {
                $('#edit_id').val(originalValues.id);

                $('#edit_car_unit').val(originalValues.carUnit);
                $('#edit_car_variant').val(originalValues.carVariant);
                $('#edit_car_color').val(originalValues.carColor);
                $('#edit_transaction').val(originalValues.transaction);
                $('#edit_source').val(originalValues.source);
                $('#edit_address').val(originalValues.address);
                $('#edit_quantity').val(originalValues.quantity);
                $('#edit_mobile_number').val(originalValues.mobileNumber);
                $('#edit_category').val(originalValues.category);

                if (edit_inquiryType === 'Individual') {
                    $('#edit_first_name').val(originalValues.firstName);
                    $('#edit_last_name').val(originalValues.lastName);
                    $('#edit_age').val(originalValues.age);
                    $('#edit_gender').val(originalValues.gender);

                } else if (edit_inquiryType === 'Fleet' || edit_inquiry_type === 'Company') {

                    $('#edit_fleet').val(edit_inquiryType === 'Company' ? '' : originalValues.fleet);
                    $('#edit_company').val(edit_inquiryType === 'Fleet' ? '' : originalValues.company);

                } else if (edit_inquiryType === 'Government') {
                    $('#edit_government').val(originalValues.government);
                }

                return; // Stop execution if validation fails
            }

            // Proceed with AJAX request if the form is valid
            const formData = $(this).serialize();
            const inquiryId = originalValues.id; // Assuming you set data-id on the form

            $.ajax({
                url: `/application/update/${inquiryId}`, // Adjust URL as needed
                type: 'POST',
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: response.message,
                        });
                        // Reload the DataTable or update the UI as needed
                        applicationTable.ajax.reload();
                        $('#editApplicationFormModal').modal('hide'); // Hide the modal
                    }
                },
                error: function (xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: xhr.responseJSON?.message || 'Something went wrong!'
                    });
                }
            });
        });


        $('#editApplicationFormData input, #editApplicationFormData select').on('input change', function() {
            validateField(this);
        });

        // Real-time Capitalization
        $("input[type='text']").on("input", function () {
            $(this).val(capitalizeWords($(this).val()));
        });
    });

    let originalValues = {};0

    // Add this inside your <script> tag in the Blade file
    $(document).on('click', '.edit-btn', function() {
        const applicationId = $(this).data('id');
        $.ajax({
            url: `{{ url('application/edit') }}/${applicationId}`,
            type: 'GET',
            success: function(response) {
                const data = response.application;
                const statuses = response.statuses;
                const banks = response.banks;

                // Populate the form fields with the inquiry data
                $('#edit_id').val(data.id);
                $('#edit_first_name').val(data.customer.customer_first_name);
                $('#edit_last_name').val(data.customer.customer_last_name);
                $('#edit_gender').val(data.customer.gender);
                $('#edit_age').val(data.customer.age);
                $('#edit_mobile_number').val(data.customer.contact_number);
                $('#edit_address').val(data.customer.address);
                $('#edit_car_unit').val(data.vehicle.unit).trigger('change');
                $('#edit_inquiry_type').val(data.inquiry.inquiry_type.inquiry_type);
                $('#edit_category').val(data.inquiry.category).trigger('change');
                $('#edit_quantity').val(data.inquiry.quantity);
                $('#edit_payment_status').val(data.trans.reservation_status).trigger('change');


                const edit_inquiry_type =  $('#edit_inquiry_type').val();
                console.log(edit_inquiry_type);

                if (edit_inquiry_type === 'Individual') {
                        // No special validation changes for individual, just hide others
                        $('#edit_first_name, #edit_first_name').closest('.row').show(); // Show first and last name by default
                        $('#editCompanyColumnField, #editGovernmentColumnField, #editQuantityColumnField').addClass('d-none');
                        $('#editQuantityColumnField').addClass('d-none');
                        $('#editCompanyColumnField').addClass('d-none');
                        $('#edit_gender, #edit_age').closest('.row').show();
                        $('#editFleetColumnField').addClass('d-none');

                        $('#edit_first_name').val(data.customer.customer_first_name);
                        $('#edit_last_name').val(data.customer.customer_last_name);
                        $('#edit_gender').val(data.customer.gender);
                        $('#edit_age').val(data.customer.age);

                    } else if (edit_inquiry_type === 'Fleet' || edit_inquiry_type === 'Company') {
                        // Hide first and last name, show quantity
                        $('#edit_first_name, #edit_first_name').closest('.row').hide();
                        $('#editQuantityColumnField').removeClass('d-none');
                        $('#editCompanyColumnField').toggleClass('d-none', edit_inquiry_type !== 'Company');
                        $('#editFleetColumnField').toggleClass('d-none', edit_inquiry_type !== 'Fleet');
                        $('#edit_gender, #edit_age').closest('.row').hide(); // Hide gender and age for fleet and company
                        $('#editGovernmentColumnField').addClass('d-none');

                        $('#edit_fleet').val(edit_inquiry_type === 'Company' ? '' : data.customer.company_name);
                        $('#edit_company').val(edit_inquiry_type === 'Fleet' ? '' : data.customer.company_name);

                    } else if (edit_inquiry_type === 'Government') {
                        // Hide first name, last name, and company, show government field
                        $('#editFleetColumnField').addClass('d-none');
                        $('#edit_first_name, #edit_first_name').closest('.row').hide();
                        $('#editQuantityColumnField').removeClass('d-none');
                        $('#editCompanyColumnField').addClass('d-none');
                        $('#editGovernmentColumnField').removeClass('d-none');
                        $('#edit_gender, #edit_age').closest('.row').hide(); // Hide gender and age for fleet and company
                        $('#edit_government').val(data.customer.department_name);

                    }


                $.ajax({
                    url: '{{ route("leads.getVariants") }}',
                    type: 'GET',
                    data: { unit: data.vehicle.unit },
                    dataType: 'json',
                    success: function(variantsData) {
                        $('#edit_car_variant').val(data.vehicle.variant).trigger('change'); // Trigger change to update colors
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Could not fetch variants.'
                        });
                    }
                });

                $('#edit_car_variant').on('change', function() {
                    const selectedVariant = $(this).val(); // Get the selected variant
                    // Automatically select the color based on the variant
                    $.ajax({
                        url: '{{ route("leads.getColor") }}',
                        type: 'GET',
                        data: { variant: data.vehicle.variant },
                        dataType: 'json',
                        success: function(colorsData) {
                            $('#edit_car_color').val(data.vehicle.color); // Automatically select the color
                        },
                        error: function(xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Could not fetch colors.'
                            });
                        }
                    });
                })

                $('#edit_transaction').val(data.transaction);
                $('#edit_source').val(data.customer.source);
                $('#edit_remarks').val(data.remarks);


                // Store original values
                originalValues = {
                    id: data.id,
                    firstName: data.customer.customer_first_name,
                    lastName: data.customer.customer_last_name,
                    gender: data.customer.gender,
                    age: data.customer.age,
                    mobileNumber: data.customer.contact_number,
                    address: data.customer.address,
                    carUnit: data.vehicle.unit,
                    carVariant: data.vehicle.variant,
                    carColor: data.vehicle.color,
                    transaction: data.transaction,
                    source: data.customer.source,
                    remarks: data.remarks,
                    fleet: data.customer.company_name,
                    company: data.customer.company_name,
                    government: data.customer.department_name,
                    quantity: data.inquiry.quantity,
                    category: data.inquiry.category,
                };

                $('#editApplicationFormModal').modal('show');

            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Could not fetch Application data.'
                });
            }
        });
    });

     //Process Data
    $(document).on('click', '.processing-btn', function() {
        const appID = $(this).data('id');

        Swal.fire({
            title: 'Are you sure?',
            text: "Do you want to update its status?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, update it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ route("application.processing") }}',
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
                            applicationTable.ajax.reload();
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

    $(document).on('click', '.cancel-btn', function() {
        const appID = $(this).data('id');

        Swal.fire({
            title: 'Are you sure?',
            text: "Do you want to cancel this application?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, cancel it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ route("application.status.cancel") }}',
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
                            applicationTable.ajax.reload();
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

    // Edit Modal Fields disabled state -> Editable State
    $(document).ready(function () {
        // Function to reset the modal to its initial uneditable state
        function resetModalToInitialState() {
            // Disable all input fields except the Edit button
            $("#editApplicationFormData :input").not("#editApplicationModalButton").prop("disabled", true);

            // Show the Edit button
            $("#editApplicationModalButton").removeClass("d-none");

            // Hide the Save and Cancel buttons
            $("#saveEditApplicationModalButton").addClass("d-none");
            $("#cancelApplicationModalButton").addClass("d-none");
        }

        // Initially, reset the modal to its initial state when the page is ready
        resetModalToInitialState();

        // When the Edit button is clicked
        $("#editApplicationModalButton").on("click", function () {
            // Enable all input fields except hidden fields
            $("#editApplicationFormData :input").not("[type='hidden']").prop("disabled", false);

            // Hide the Edit button
            $(this).addClass("d-none");

            // Show the Save Changes and Cancel buttons
            $("#saveEditApplicationModalButton").removeClass("d-none");
            $("#cancelApplicationModalButton").removeClass("d-none");
        });

        // When the Cancel button is clicked
        $("#cancelApplicationModalButton").on("click", function () {
            // Close the modal properly
            $("#editApplicationFormModal").modal("hide");

            // Reset the modal to its initial uneditable state when reopened
            resetModalToInitialState();
        });

        // Reset the modal when it's closed (using Bootstrap modal `hidden.bs.modal` event)
        $("#editApplicationFormModal").on("hidden.bs.modal", function () {
            resetModalToInitialState();
        });
    });

    function populateBankSelects(data, targetSelect = null) {
        if (targetSelect) {
            // Populate only the target select
            const selectedValue = targetSelect.val(); // Save the current selected value
            targetSelect.empty(); // Clear options
            targetSelect.append('<option value="">Select Banks...</option>'); // Add default option
            data.forEach(function (item) {
                targetSelect.append(`<option value="${item.id}">${item.bank_name}</option>`);
            });
            targetSelect.val(selectedValue); // Restore the selected value
        } else {
            // Populate all selects
            const bankSelects = $("select[name='bank_id[]']");
            bankSelects.each(function () {
                const bankSelect = $(this);
                const selectedValue = bankSelect.val(); // Save the current selected value
                bankSelect.empty();
                bankSelect.append('<option value="">Select Banks...</option>'); // Add default option
                data.forEach(function (item) {
                    bankSelect.append(`<option value="${item.id}">${item.bank_name}</option>`);
                });
                bankSelect.val(selectedValue); // Restore the selected value
            });
        }
    }

    // Fetch banks from the server
    function fetchBanks(targetSelect = null) {
        $.ajax({
            url: '{{ route('application.getBanks') }}',
            type: 'GET',
            dataType: 'json',
            success: function (data) {
                populateBankSelects(data, targetSelect); // Populate only the target select if provided
            },
            error: function (error) {
                console.error('Error loading banks:', error);
            },
        });
    }

    $(document).on('click', '.bank-btn', function () {
        $('#application_id').val($(this).data('id'));
        fetchBanks(); // Initial fetch of banks
    });

    $("#addBankFieldButton").on("click", function (e) {
        e.preventDefault();

        // Create a new bank field
        const newBankField = `
            <div class="row mb-2 bank-field">
                <div class="col-md d-flex align-items-center gap-2">
                    <select class="form-control" name="bank_id[]">
                        <option value="">Select Banks...</option>
                    </select>
                    <button type="button" class="btn btn-icon me-2 btn-label-danger removeBankFieldButton">
                        <span class="tf-icons bx bxs-trash bx-22px"></span>
                    </button>
                </div>
            </div>
        `;

        // Append the new field to the container
        const newField = $(newBankField);
        $("#bankFieldsContainer").append(newField);

        // Fetch and populate only the newly added select field
        const newSelect = newField.find("select[name='bank_id[]']");
        fetchBanks(newSelect);
    });

    // Remove bank field
    $(document).on("click", ".removeBankFieldButton", function (e) {
        e.preventDefault();

        // Check if this field is not the very first field
        const bankFields = $(".bank-field");
        if (bankFields.length > 1) {
            // Remove the parent row of the clicked remove button
            $(this).closest(".bank-field").remove();
        } else {
            alert("The first field cannot be removed.");
        }
    });


    $('#selectBankForm').on('submit', function(e) {
        e.preventDefault();

        // Collect selected bank IDs
        const bankIds = [];
        $("select[name='bank_id[]']").each(function() {
            const selectedValue = $(this).val();
            if (selectedValue) {
                bankIds.push(selectedValue);
            }
        });

        // Add bankIds to the form data
        const formData = $(this).serialize()
        // + '&bank_id=' + JSON.stringify(bankIds);

        // Submit the form data to the store method
        $.ajax({
            url: '{{ route('application.store.banks') }}',
            type: 'POST',
            data: formData,
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
                    $('#selectBankModal').modal('hide'); // Hide the modal
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

</script>


@endsection
