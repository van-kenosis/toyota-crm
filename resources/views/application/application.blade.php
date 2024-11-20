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

{{-- Edit Application Modal --}}
<div class="modal fade" id="editApplicationFormModal" tabindex="-1" aria-labelledby="largeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="largeModalLabel">Edit</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="editApplicationFormData">
              <div class="mb-4">
                  <div class="row mb-2">
                      <div class="col-md">
                          <input type="hidden" class="form-control" id="edit_id" name="id" />
                          <label for="first_name" class="form-label required">First Name</label>
                          <input type="text" class="form-control" id="edit_first_name" name="first_name" placeholder="" />
                          <small class="text-danger" id="validateFirstname">Enter Customer First Name</small>
                      </div>
                      <div class="col-md">
                          <label for="last_name" class="form-label required">Last Name</label>
                          <input type="text" class="form-control" id="edit_last_name" name="last_name" placeholder="" />
                          <small class="text-danger" id="validateLastname">Enter Customer Last Name</small>
                      </div>
                  </div>
                  <div class="row mb-2">
                      <div class="col-md">
                          <label for="gender" class="form-label required">Gender</label>
                          <select class="form-control" id="edit_gender" name="gender">
                              <option value="">Select Gender</option>
                              <option value="Female">Female</option>
                              <option value="Male">Male</option>
                          </select>
                          <small class="text-danger" id="validateGender">Please Select Gender</small>
                      </div>
                      <div class="col-md">
                          <label for="age" class="form-label required">Age</label>
                          <input type="number" class="form-control" id="edit_age" name="age" placeholder="" />
                          <small class="text-danger" id="validateLastname">Enter Customer Age</small>
                      </div>
                      <div class="col-md">
                          <label for="mobile_number" class="form-label required">Mobile Number</label>
                          <input type="text" class="form-control" id="edit_mobile_number" name="mobile_number" placeholder="09" />
                          <small class="text-danger" id="validateMobileNumber">Enter Valid Mobile Number</small>
                      </div>
                  </div>
                  <div class="row">
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
                          </select>
                          <small class="text-danger" id="validateColor">Please Select Color</small>
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
                  <div class="mb-4">
                    <div class="row mb-2">
                        <div class="col-md">
                            <label for="edit_bank_id" class="form-label required">Bank</label>
                            <select class="form-control" id="edit_bank_id" name="bank_id">
                                <option value="">Select a Bank</option>
                            </select>
                            <small class="text-danger" id="validateBank">Please Select a Bank</small>
                        </div>
                        <div class="col-md">
                            <label for="edit_status_id" class="form-label required">Status</label>
                            <select class="form-control" id="edit_status_id" name="status_id">
                                <option value="">Select a Status</option>
                            </select>
                            <small class="text-danger" id="validateStatus">Please Select a Status</small>
                        </div>
                    </div>
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
                      <button type="button" class="btn btn-label-danger" id="cancelEditApplicationFormButton">Cancel</button>
                      <button type="submit" class="btn btn-primary">Save Changes</button>
                  </div>
              </div>
          </form>
        </div>
      </div>
    </div>
</div>

{{-- Application Form --}}
<div class="row mb-4">
    <div class="col-md">
        <div class="card" id="applicationFormCard" style="display: none;">
            <div class="card-header">
                <h5 class="text-primary card-title">Application Form</h5>
            </div>
            <div class="card-body">
                <form id="applicationFormData">
                    <div class="mb-4">
                        <div class="row mb-2">
                            <div class="col-md">
                                <label for="first_name" class="form-label required">First Name</label>
                                <input type="text" class="form-control" id="first_name" name="first_name" placeholder="" />
                                <small class="text-danger" id="validateFirstname">Enter Customer First Name</small>
                            </div>
                            <div class="col-md">
                                <label for="last_name" class="form-label required">Last Name</label>
                                <input type="text" class="form-control" id="last_name" name="last_name" placeholder="" />
                                <small class="text-danger" id="validateLastname">Enter Customer Last Name</small>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md">
                                <label for="gender" class="form-label required">Gender</label>
                                <select class="form-control" id="gender" name="gender">
                                    <option value="">Select Gender</option>
                                    <option value="Female">Female</option>
                                    <option value="Male">Male</option>
                                </select>
                                <small class="text-danger" id="validateGender">Please Select Gender</small>
                            </div>
                            <div class="col-md">
                                <label for="age" class="form-label required">Age</label>
                                <input type="number" class="form-control" id="age" name="age" placeholder="" />
                                <small class="text-danger" id="validateAge">Enter Customer Age</small>
                            </div>
                            <div class="col-md">
                                <label for="mobile_number" class="form-label required">Mobile Number</label>
                                <input type="text" class="form-control" id="mobile_number" name="mobile_number" placeholder="09" />
                                <small class="text-danger" id="validateMobileNumber">Enter Valid Mobile Number</small>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md">
                                <label for="address" class="form-label required">Address</label>
                                <input type="text" class="form-control" id="address" name="address" placeholder="" />
                                <small class="text-danger" id="validateAddress">Enter <Address></Address></small>
                            </div>
                        </div>
                    </div>
                    <div class="mb-4">
                        <div class="row mb-2">
                            <div class="col-md">
                                <label for="car_unit" class="form-label required">Unit</label>
                                <select class="form-control" id="car_unit" name="car_unit">
                                    <option value="">Select Unit</option>
                                </select>
                                <small class="text-danger" id="validateUnit">Please Select Unit</small>
                            </div>
                            <div class="col-md">
                                <label for="car_variant" class="form-label required">Variants</label>
                                <select class="form-control" id="car_variant" name="car_variant">
                                    <option value="">Select Variants</option>
                                </select>
                                <small class="text-danger" id="validateVariant">Please Select Variant</small>
                            </div>
                            <div class="col-md">
                                <label for="car_color" class="form-label required">Color</label>
                                <select class="form-control" id="car_color" name="car_color">
                                    <option value="">Select Color</option>
                                </select>
                                <small class="text-danger" id="validateColor">Please Select Color</small>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md">
                            <label for="transaction" class="form-label required">Transactions</label>
                            <select class="form-control" id="transaction" name="transaction">
                                <option value="">Select Transactions</option>
                                <option value="cash">Cash</option>
                                <option value="po">PO</option>
                                <option value="financing">Financing</option>
                            </select>
                            <small class="text-danger" id="validateTransaction">Please Select Transaction</small>
                        </div>
                        <div class="col-md">
                            <label for="source" class="form-label required">Source</label>
                            <select class="form-control" id="source" name="source">
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

                    <div class="mb-4">
                        <div class="row mb-2">
                            <div class="col-md">
                                <label for="bank_id" class="form-label required">Bank</label>
                                <select class="form-control" id="bank_id" name="bank_id">
                                    <option value="">Select a Bank</option>
                                </select>
                                <small class="text-danger" id="validateBank">Please Select a Bank</small>
                            </div>
                           
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md">
                            <label for="additional_info" class="form-label">Remarks</label>
                            <textarea class="form-control" placeholder="Message" id="remarks" name="additional_info" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-label-danger" id="cancelApplicationFormButton">Cancel</button>
                            <button type="submit" class="btn btn-success">Add Application</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="row mb-2">
    <div class="col-md d-flex justify-content-end">
        <button class="btn btn-primary" id="addNewApplicationButton">Add Application</button>
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
                    <table id="applicationTable" class="table table-striped table-hover" style="width:100%">
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
            { data: 'agent', name: 'agent', title: 'Agent' },
            { data: 'customer_name', name: 'customer_name', title: 'Customer Name' },
            { data: 'contact_number', name: 'contact_number', title: 'Contact No.' },
            { data: 'unit', name: 'unit', title: 'Unit' },
            { data: 'variant', name: 'variant', title: 'Variant' },
            { data: 'color', name: 'color', title: 'Color' },
            { data: 'transaction', name: 'transaction', title: 'Transaction' },
            { data: 'gender', name: 'gender', title: 'Gender' },
            { data: 'age', name: 'age', title: 'Age' },
            { data: 'source', name: 'source', title: 'Source' },
            { data: 'address', name: 'address', title: 'Address' },
            { data: 'date', name: 'date', title: 'Date' },
            {
                data: 'remarks',
                title: 'Remarks',
                orderable: false,
                searchable: false,
                render: function(data) {
                    return `
                        <div class="d-flex">
                            <button type="button" class="btn btn-icon me-2 btn-info" data-bs-toggle="modal" data-bs-target="#editThesisEntiresModal">
                                <span class="tf-icons bx bx-show-alt bx-22px"></span>
                            </button>
                        </div>`;
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
                                <span class="tf-icons bx bx-pencil bx-22px"></span>
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

    // Inquiry Form Validation
    $(document).ready(function () {

        $.ajax({
            url: '{{ route('application.getBanks') }}',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                let bankSelect = $('#bank_id, #edit_bank_id');
                bankSelect.empty();
                bankSelect.append('<option value="">Select Banks...</option>');
                data.forEach(function(item) {
                    bankSelect.append(`<option value="${item.id}">${item.bank_name}</option>`);
                });
            },
            error: function(error) {
                console.error('Error loading banks:', error);
            }
        });

        $.ajax({
            url: '{{ route('application.getStatus') }}',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                let statusSelect = $('#edit_status_id');
                statusSelect.empty();
                statusSelect.append('<option value="">Select Status...</option>');
                data.forEach(function(item) {
                    statusSelect.append(`<option value="${item.id}">${item.status}</option>`);
                });
            },
            error: function(error) {
                console.error('Error loading status:', error);
            }
        });

        $.ajax({
            url: '{{ route('leads.getUnit') }}',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                let unitSelect = $('#car_unit, #edit_car_unit');
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
        $('#car_unit, #edit_car_unit').on('change', function() {
            const selectedUnit = $(this).val();
            if (selectedUnit) {
                $.ajax({
                    url: '{{ route("leads.getVariants") }}',
                    type: 'GET',
                    data: { unit: selectedUnit },
                    dataType: 'json',
                    success: function(data) {
                        let variantSelect = $('#car_variant, #edit_car_variant');
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
                console.log('here');
                // Clear the selects if no unit is selected
                $('#car_variant').empty().append('<option value="">Select Variants...</option>');
            }
        });

        $('#car_variant, #edit_car_variant').on('change', function() {
            const selectedVariant = $(this).val();
            if (selectedVariant) {
                $.ajax({
                    url: '{{ route("leads.getColor") }}',
                    type: 'GET',
                    data: { variant: selectedVariant },
                    dataType: 'json',
                    success: function(data) {

                        let colorSelect = $('#car_color, #edit_car_color');
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
                    },
                    error: function(error) {
                        console.error('Error loading variants and colors:', error);
                    }
                });
            } else {
                console.log('here');
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

        // Validation function
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

        // // Validate form on submit
        $("#applicationFormData").on("submit", function (e) {
            e.preventDefault();
            let isValid = true;

             // Validate required fields
            isValid = validateField('#first_name', 'Enter Customer First Name') && isValid;
            isValid = validateField('#last_name', 'Enter Customer Last Name') && isValid;
            isValid = validateField('#age', 'Enter Customer Age') && isValid;
            isValid = validateField('#mobile_number', 'Enter Valid Mobile Number') && isValid;
            isValid = validateField('#car_unit', 'Please Select Unit') && isValid;
            isValid = validateField('#car_variant', 'Please Select Variant') && isValid;
            isValid = validateField('#car_color', 'Please Select Color') && isValid;
            isValid = validateField('#transaction', 'Please Select Transaction') && isValid;
            isValid = validateField('#source', 'Please Select Source') && isValid;
            isValid = validateField('#gender', 'Please Select Gender') && isValid;
            isValid = validateField('#car_variant', 'Please Select a Variant') && isValid;
            isValid = validateField('#address', 'Enter Address') && isValid;
            isValid = validateField('#bank_id', 'Please Select a Bank') && isValid;


            // Special validation for mobile number
            const mobileNumber = $('#mobile_number').val();
            if (mobileNumber && !mobileNumber.match(/^09\d{9}$/)) {
                $('#mobile_number').addClass('is-invalid border-danger');
                $('#validateMobileNumber').show();
                isValid = false;
            }

            if (isValid) {
                const formData = $(this).serialize();
                $.ajax({
                    url: '{{ route("application.store") }}',
                    type: 'POST',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                             Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: response.message,
                            });
                            // Reset form and hide it
                            $("#applicationFormData")[0].reset();
                            $("#applicationFormCard").hide();
                            $("#addNewApplicationButton").show();

                            // Clear all validation states
                            $(".text-danger").hide();
                            $("input, select").removeClass("is-invalid border-danger");
                            applicationTable.ajax.reload();
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: xhr.responseJSON?.message || 'Something went wrong!'
                        });
                    }
                });
            }
        });

        $("#editApplicationFormData").on("submit", function (e) {
            e.preventDefault();
            let isValid = true;

            // Validate required fields
            isValid = validateField('#edit_first_name', 'Enter Customer First Name') && isValid;
            isValid = validateField('#edit_last_name', 'Enter Customer Last Name') && isValid;
            isValid = validateField('#edit_age', 'Enter Customer Age') && isValid;
            isValid = validateField('#edit_mobile_number', 'Enter Valid Mobile Number') && isValid;
            isValid = validateField('#edit_car_unit', 'Please Select Unit') && isValid;
            isValid = validateField('#edit_car_variant', 'Please Select Variant') && isValid;
            isValid = validateField('#edit_car_color', 'Please Select Color') && isValid;
            isValid = validateField('#edit_transaction', 'Please Select Transaction') && isValid;
            isValid = validateField('#edit_source', 'Please Select Source') && isValid;
            isValid = validateField('#edit_gender', 'Please Select Gender') && isValid;
            isValid = validateField('#edit_address', 'Enter Address') && isValid;
            isValid = validateField('#edit_bank_id', 'Please Select a Bank') && isValid;
            isValid = validateField('#edit_status_id', 'Please Select a Status') && isValid;

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

            // Restore original values on invalid fields
            if (!isValid) {
                $('#edit_id').val(originalValues.id);
                $('#edit_first_name').val(originalValues.firstName);
                $('#edit_last_name').val(originalValues.lastName);
                $('#edit_age').val(originalValues.age);
                $('#edit_car_unit').val(originalValues.carUnit);
                $('#edit_car_variant').val(originalValues.carVariant);
                $('#edit_car_color').val(originalValues.carColor);
                $('#edit_transaction').val(originalValues.transaction);
                $('#edit_source').val(originalValues.source);
                $('#edit_gender').val(originalValues.gender);
                $('#edit_address').val(originalValues.address);
                $('#edit_mobile_number').val(originalValues.mobileNumber);
                return; // Stop execution if validation fails
            }

            // Proceed with AJAX request if the form is valid
            const formData = $(this).serialize();
            const applicationId = originalValues.id; // Assuming you set data-id on the form
            console.log(applicationId);

            $.ajax({
                url: `/application/update/${applicationId}`, // Adjust URL as needed
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


        $('#applicationFormData input, #editApplicationFormData select').on('input change', function() {
            validateField(this);
        });

        // Real-time Capitalization
        $("input[type='text']").on("input", function () {
            $(this).val(capitalizeWords($(this).val()));
        });


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

    // display form
    $(document).ready(function () {
            // Show #inquiryFormCard when #addNewInquiryButton is clicked
            $("#addNewApplicationButton").on("click", function () {
                $("#addNewApplicationButton").hide();
                $("#applicationFormCard").show(); // Display the form card
                // Clear validation messages
                $(".text-danger").hide();
                $("input, select").removeClass("is-invalid border-danger");
            });

            $("#cancelApplicationFormButton").on("click", function () {
                $("#addNewApplicationButton").show();
                $("#applicationFormCard").hide(); // Hide the form card
                // Clear all fields in the form
                $("#applicationFormCard input[type=text], textarea").val("");
                $("#applicationFormCard select").val("");
                // Clear validation messages
                $(".text-danger").hide();
                $("input, select").removeClass("is-invalid border-danger");
            });
    });

    // mobile number validation
    $(document).ready(function () {
        $("#mobile_number").on("input", function () {
            let value = $(this).val();

            // Enforce the number to start with "09" and allow only digits
            if (!/^09/.test(value)) {
                value = "09"; // If it doesn't start with "09", reset to "09"
            } else {
                value = value.replace(/[^0-9]/g, ""); // Remove any non-numeric characters
            }

            // Limit to exactly 11 digits
            if (value.length > 11) {
                value = value.slice(0, 11); // Truncate to 11 characters if exceeded
            }

            // Update the input field with the sanitized value
            $(this).val(value);
        });

        // Form submission event to check final validation
        $("#applicationFormData").on("submit", function (event) {
            const mobileNumber = $("#mobile_number").val();
            if (mobileNumber.length !== 11) {
                event.preventDefault();
                $("#validateMobileNumber").show().text("Mobile number must be exactly 11 digits.");
            }
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
                $('#edit_bank_id').val();
                $('#edit_car_unit').val(data.vehicle.unit).trigger('change');


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

                const statusSelect = $('#edit_status_id');
                statusSelect.empty(); // Clear existing options
                statusSelect.append('<option value="">Select Status...</option>'); // Default option
                statuses.forEach(function(status) {
                    statusSelect.append(`<option value="${status.id}" ${status.id === data.status_id ? 'selected' : ''}>${status.status}</option>`);
                });
 
                // Populate the bank dropdown
                if (data.bank && data.bank.id) { // Only run this if bank has value and is not null
                    const editBankSelect = $('#edit_bank_id');
                    editBankSelect.empty(); // Clear existing options
                    editBankSelect.append('<option value="">Select Bank...</option>'); // Default option
                    banks.forEach(function(bank) {
                       
                        editBankSelect.append(`<option value="${bank.id}" ${bank.id === data.bank_id ? 'selected' : ''}>${bank.bank_name}</option>`);
                    });
                }


        
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
                    banks: data.bank ? data.bank.id : '', // Ensure no error if bank_id is null
                    status: data.status ? data.status.id : '' // Ensure no error if status_id is null
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






</script>


@endsection
