@extends('components.app')

@section('content')

{{-- Title Header --}}
<div class="card bg-dark shadow-none mb-4">
    <div class="card-body">
        <div class="d-flex align-items-center">
            <i class='bx bxs-user-account text-white' style="font-size: 24px;">&nbsp;</i>
            <h4 class="text-white mb-0">User Management</h4>
        </div>
    </div>
</div>

{{-- Edit User Modal --}}
<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editUserForm">
                    @csrf
                    <input type="hidden" id="edit_user_id" name="id">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="edit_first_name" class="form-label required">First Name</label>
                            <input type="text" class="form-control" id="edit_first_name" name="first_name">
                            <small class="text-danger" id="edit_validate_first_name">Please enter first name</small>
                        </div>
                        <div class="col-md-6">
                            <label for="edit_last_name" class="form-label required">Last Name</label>
                            <input type="text" class="form-control" id="edit_last_name" name="last_name">
                            <small class="text-danger" id="edit_validate_last_name">Please enter last name</small>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="edit_email" class="form-label required">Email</label>
                            <input type="email" class="form-control" id="edit_email" name="email">
                            <small class="text-danger" id="edit_validate_email">Please enter valid email</small>
                        </div>
                        <div class="col-md-6">
                            <label for="edit_usertype" class="form-label required">User Type</label>
                            <select class="form-control" id="edit_usertype" name="usertype_id">
                                <option value="">Select User Type</option>
                            </select>
                            <small class="text-danger" id="edit_validate_usertype">Please select user type</small>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="edit_team" class="form-label required">Team</label>
                            <select class="form-control" id="edit_team" name="team_id" disabled>
                                <option value="">Select Team</option>
                            </select>
                            <small class="text-danger" id="edit_validate_team">Please select team</small>
                        </div>
                        <div class="col-md-6">
                            <label for="edit_status" class="form-label required">Status</label>
                            <select class="form-control" id="edit_status" name="status">
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-label-danger" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-dark">Update User</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Add User Form --}}
<div class="row mb-4">
    <div class="col-md">
        <div class="card" id="addUserCard" style="display: none;">
            <div class="card-header">
                <h5 class="text-primary card-title">Add New User</h5>
            </div>
            <div class="card-body">
                <form id="addUserForm">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="first_name" class="form-label required">First Name</label>
                            <input type="text" class="form-control" id="first_name" name="first_name">
                            <small class="text-danger" id="validate_first_name">Please enter first name</small>
                        </div>
                        <div class="col-md-6">
                            <label for="last_name" class="form-label required">Last Name</label>
                            <input type="text" class="form-control" id="last_name" name="last_name">
                            <small class="text-danger" id="validate_last_name">Please enter last name</small>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="email" class="form-label required">Email</label>
                            <input type="email" class="form-control" id="email" name="email">
                            <small class="text-danger" id="validate_email">Please enter valid email</small>
                        </div>
                        <div class="col-md-6">
                            <label for="password" class="form-label required">Password</label>
                            <input type="password" class="form-control" id="password" name="password">
                            <small class="text-danger" id="validate_password">Please enter password</small>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="usertype" class="form-label required">User Type</label>
                            <select class="form-control" id="usertype" name="usertype_id">
                                <option value="">Select User Type</option>
                            </select>
                            <small class="text-danger" id="validate_usertype">Please select user type</small>
                        </div>
                        <div class="col-md-6">
                            <label for="team" class="form-label required">Team</label>
                            <select class="form-control" id="team" name="team_id">
                                <option value="">Select Team</option>
                            </select>
                            <small class="text-danger" id="validate_team">Please select team</small>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-label-danger" id="cancelAddUserBtn">Cancel</button>
                            <button type="submit" class="btn btn-dark">Add User</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Add User Button --}}
<div class="row mb-2">
    <div class="col-md d-flex justify-content-end">
        <button class="btn btn-primary" id="addUserBtn">Add New User</button>
    </div>
</div>

{{-- Users Table --}}
<div class="row">
    <div class="col">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="usersTable" class="table table-bordered table-hover" style="width:100%">
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('components.specific_page_scripts')
<script>
    $(document).ready(function() {
        // Hide validation messages initially
        $(".text-danger").hide();

        // Initialize DataTable
        const usersTable = $('#usersTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route("user.management.list") }}',
            columns: [
                {
                    data: 'id',
                    name: 'id',
                    title: 'Name',
                    render: function(data, type, row) {
                        return row.first_name + ' ' + row.last_name;
                    }
                },
                { data: 'email', name: 'email', title: 'Email' },
                { data: 'usertype', name: 'usertype', title: 'User Type' },
                { data: 'team', name: 'team', title: 'Team' },
                { data: 'status', name: 'status', title: 'Status' },
                {
                    data: 'id',
                    name: 'actions',
                    title: 'Actions',
                    orderable: false,
                    searchable: false,
                    render: function(data) {
                        return `<div class="d-flex gap-2">
                            <button type="button" class="btn btn-icon btn-success edit-user" data-id="${data}" title="Edit User">
                                <span class="tf-icons bx bx-pencil"></span>
                            </button>
                            <button type="button" class="btn btn-icon btn-warning send-temporary-password" data-id="${data}" title="Send Temporary Password">
                                <span class="tf-icons bx bx-envelope"></span>
                            </button>
                        </div>`;
                    }
                }
            ],
            order: [[0, 'asc']]
        });

        // Show/Hide Add User Form
        $('#addUserBtn').click(function() {
            $('#addUserCard').show();
            $('#addUserForm')[0].reset();
            $('#usertype').val('');
            $('#team').val('');
            $(".text-danger").hide();
        });

        $('#cancelAddUserBtn').click(function() {
            $('#addUserCard').hide();
            $('#addUserForm')[0].reset();
            $(".text-danger").hide();
        });

        // Load User Types and Teams
        function loadUserTypes() {
            $.ajax({
                url: '{{ route("usertypes.list") }}',
                type: 'GET',
                success: function(data) {
                    let options = '<option value="">Select User Type</option>';
                    data.forEach(function(type) {
                        options += `<option value="${type.id}">${type.name}</option>`;
                    });
                    $('#usertype, #edit_usertype').html(options);
                }
            });
        }

        function loadTeams() {
            $.ajax({
                url: '{{ route("teams.list") }}',
                type: 'GET',
                success: function(data) {
                    let options = '<option value="">Select Team</option>';
                    data.forEach(function(team) {
                        options += `<option value="${team.id}">${team.name}</option>`;
                    });
                    $('#team, #edit_team').html(options);
                }
            });
        }

        loadUserTypes();
        loadTeams();

        // Add User Form Submission
        $('#addUserForm').on('submit', function(e) {
            e.preventDefault();

            let formData = {
                first_name: $('#first_name').val(),
                last_name: $('#last_name').val(),
                email: $('#email').val(),
                password: $('#password').val(),
                usertype_id: $('#usertype').val(),
                team_id: $('#team').val()
            };

            $.ajax({
                url: '{{ route("user.management.store") }}',
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
                        }).then(() => {
                            $('#addUserCard').hide();
                            $('#addUserForm')[0].reset();
                            usersTable.ajax.reload();
                        });
                    }
                },
                error: function(xhr) {
                    let errors = xhr.responseJSON?.errors || {};
                    Object.keys(errors).forEach(field => {
                        $(`#validate_${field}`).text(errors[field][0]).show();
                        $(`#${field}`).addClass('border-danger');
                    });
                }
            });
        });

        // Edit User
        $(document).on('click', '.edit-user', function(e) {
            e.preventDefault();
            let userId = $(this).data('id');
            console.log(userId);
            $.ajax({
                url: `user-management/${userId}/edit`,
                type: 'GET',
                success: function(response) {
                    $('#edit_user_id').val(userId);
                    $('#edit_first_name').val(response.first_name);
                    $('#edit_last_name').val(response.last_name);
                    $('#edit_email').val(response.email);
                    $('#edit_usertype').val(response.usertype_id);
                    $('#edit_team').val(response.team_id);
                    $('#edit_status').val(response.status);
                    $('#editUserModal').modal('show');
                }
            });
        });

        $('#editUserForm').on('submit', function(e) {
            e.preventDefault();
            let formData = $(this).serialize();

            $.ajax({
                url: '{{ route("user.management.update") }}',
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
                        }).then(() => {
                            $('#editUserModal').modal('hide');
                            usersTable.ajax.reload();
                        });
                    }
                },
                error: function(xhr) {
                    let errors = xhr.responseJSON?.errors || {};
                    Object.keys(errors).forEach(field => {
                        $(`#validate_${field}`).text(errors[field][0]).show();
                        $(`#${field}`).addClass('border-danger');
                    });
                }
            });
        });

        $(document).on('click', '.send-temporary-password', function(e) {
            e.preventDefault();
            let userId = $(this).data('id');
            $.ajax({
                url: `user-management/${userId}/send-temporary-password`,
                type: 'GET',
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text:  'Your temporary password is: ' + response.password,
                        })
                    }
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: xhr.responseJSON?.message || 'An error occurred',
                    });
                }
            });
        });




    });
</script>
@endsection
