@extends('layouts.master')
@section('css')
    @toastr_css
@section('title')
    Specializations
@stop
@endsection
@section('page-header')
    <!-- breadcrumb -->
@section('PageTitle')
    Specializations
@stop
<!-- breadcrumb -->
@endsection
<!-- breadcrumb -->
@section('content')
<!-- row -->
<div class="row">

    @if ($errors->any())
        <div class="error">{{ $errors->first('specialization_name') }}</div>
    @endif

    <div class="col-xl-12 mb-30">
        <div class="card card-statistics h-100">
            <div class="card-body">

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <button type="button" class="button x-small" data-toggle="modal" data-target="#addSpecializationModal">
                    Add Specialization
                </button>
                <br><br>

                <div class="table-responsive">
                    <table id="specializationsTable" class="table p-0 table-hover table-sm table-bordered no-datatable"
                        style="text-align: center">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                {{-- <th>Code</th>
                                <th>Description</th>
                                <th>Status</th> --}}
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- edit_modal_Specialization -->
    <div class="modal fade" id="editSpecializationModal" tabindex="-1" role="dialog" aria-labelledby="editSpecializationLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 style="font-family: 'Cairo', sans-serif;" class="modal-title" id="editSpecializationLabel">
                        Edit Specialization
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="specializationEditForm">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" id="edit_specialization_id" name="id">
                        <div class="row">
                            <div class="col">
                                <label for="edit_specialization_name" class="mr-sm-2">Specialization Name :</label>
                                <input id="edit_specialization_name" type="text" class="form-control" name="Name" required>
                            </div>
                        </div>
                                        {{-- <div class="row mt-3">
                                            <div class="col">
                                                <label for="edit_specialization_code" class="mr-sm-2">Specialization Code :</label>
                                                <input id="edit_specialization_code" type="text" class="form-control" name="specialization_code">
                                            </div>
                                        </div>
                                        <div class="form-group mt-3">
                                            <label for="edit_description">Description :</label>
                                            <textarea class="form-control" id="edit_description" name="description" rows="3"></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="edit_status">Status:</label>
                                            <input type="checkbox" id="edit_status" name="status" value="1" checked>
                                            <span>Active</span>
                                        </div> --}}
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-success">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- delete_modal_Specialization -->
    <div class="modal fade" id="deleteSpecializationModal" tabindex="-1" role="dialog" aria-labelledby="deleteSpecializationLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 style="font-family: 'Cairo', sans-serif;" class="modal-title" id="deleteSpecializationLabel">
                        Delete Specialization
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this specialization?</p>
                    <p><strong id="delete_specialization_name"></strong></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteSpecialization">Delete</button>
                </div>
            </div>
        </div>
    </div>

    <!-- add_modal_Specialization -->
    <div class="modal fade" id="addSpecializationModal" tabindex="-1" role="dialog" aria-labelledby="addSpecializationLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 style="font-family: 'Cairo', sans-serif;" class="modal-title" id="addSpecializationLabel">
                        Add Specialization
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- add_form -->
                    <form action="{{ route('specializations.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col">
                                <label for="specialization_name" class="mr-sm-2">Specialization Name :</label>
                                <input id="specialization_name" type="text" name="Name" class="form-control" required>
                            </div>
                        </div>
                        {{-- <div class="row mt-3">
                            <div class="col">
                                <label for="specialization_code" class="mr-sm-2">Specialization Code :</label>
                                <input id="specialization_code" type="text" name="specialization_code" class="form-control">
                            </div>
                        </div>
                        <div class="form-group mt-3">
                            <label for="description">Description :</label>
                            <textarea class="form-control" name="description" id="description" rows="3"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="status">Status:</label>
                            <input type="checkbox" name="status" value="1" checked>
                            <span>Active</span>
                        </div> --}}
                        <br><br>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                        data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Submit</button>
                </div>
                </form>

            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
@toastr_js
@toastr_render

    <script>
        $(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var editModal = $('#editSpecializationModal');
            var deleteModal = $('#deleteSpecializationModal');
            var currentDeleteId = null;
            var specializationsTable;

            // Initialize DataTables with AJAX
            specializationsTable = $('#specializationsTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '/specializations',
                    type: 'GET'
                },
                columns: [
                    { 
                        data: 'id', 
                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        },
                        orderable: false,
                        searchable: false
                    },
                    { data: 'Name' },
                    // { data: 'specialization_code' },
                    // { data: 'description' },
                    // {
                    //     data: 'status',
                    //     render: function(data) {
                    //         return data ? '<span class="badge badge-success">Active</span>' : '<span class="badge badge-danger">Inactive</span>';
                    //     }
                    // },
                    {
                        data: 'id',
                        render: function(data, type, row) {
                            return `<button type="button" class="btn btn-info btn-sm btn-specialization-edit" data-id="${row.id}" title="Edit">
                                <i class="fa fa-edit"></i>
                            </button>
                            <button type="button" class="btn btn-danger btn-sm btn-specialization-delete" data-id="${row.id}" data-name="${row.specialization_name}" title="Delete">
                                <i class="fa fa-trash"></i>
                            </button>`;
                        },
                        orderable: false,
                        searchable: false
                    }
                ],
                pageLength: 10,
                order: [[1, 'asc']],
                language: {
                    processing: 'Processing...',
                    lengthMenu: 'Show _MENU_ entries',
                    zeroRecords: 'No matching records found',
                    info: 'Showing _START_ to _END_ of _TOTAL_ entries',
                    infoEmpty: 'Showing 0 to 0 of 0 entries',
                    infoFiltered: '(filtered from _MAX_ total entries)',
                    paginate: {
                        first: 'First',
                        last: 'Last',
                        next: 'Next',
                        previous: 'Previous'
                    }
                }
            });

            // Edit button click
            $(document).on('click', '.btn-specialization-edit', function () {
                var id = $(this).data('id');
                $.getJSON('/specializations/' + id + '/edit', function (specialization) {
                    $('#edit_specialization_id').val(specialization.id);
                    $('#edit_specialization_name').val(specialization.Name);
                    // $('#edit_specialization_code').val(specialization.specialization_code);
                    // $('#edit_description').val(specialization.description);
                    // $('#edit_status').prop('checked', specialization.status == 1);
                    editModal.modal('show');
                }).fail(function () {
                    toastr.error('Unable to load specialization data.');
                });
            });

            // Edit form submission
            $('#specializationEditForm').on('submit', function (e) {
                e.preventDefault();
                var id = $('#edit_specialization_id').val();
                var payload = {
                    Name: $('#edit_specialization_name').val(),
                    // specialization_code: $('#edit_specialization_code').val(),
                    // description: $('#edit_description').val(),
                    // status: $('#edit_status').is(':checked') ? 1 : 0
                };

                $.ajax({
                    url: '/specializations/' + id,
                    method: 'PATCH',
                    data: payload,
                    success: function (response) {
                        editModal.modal('hide');
                        toastr.success(response.message || 'Specialization updated successfully.');
                        specializationsTable.ajax.reload();
                    },
                    error: function (xhr) {
                        var msg = 'Update failed.';
                        if (xhr.responseJSON && xhr.responseJSON.error) {
                            msg = xhr.responseJSON.error;
                        }
                        toastr.error(msg);
                    }
                });
            });

            // Delete button click
            $(document).on('click', '.btn-specialization-delete', function () {
                currentDeleteId = $(this).data('id');
                $('#delete_specialization_name').text($(this).data('name'));
                deleteModal.modal('show');
            });

            // Delete confirmation
            $('#confirmDeleteSpecialization').on('click', function () {
                if (!currentDeleteId) {
                    return;
                }

                $.ajax({
                    url: '/specializations/' + currentDeleteId,
                    method: 'DELETE',
                    success: function (response) {
                        deleteModal.modal('hide');
                        currentDeleteId = null;
                        toastr.success(response.message || 'Specialization deleted successfully.');
                        specializationsTable.ajax.reload();
                    },
                    error: function (xhr) {
                        var msg = 'Delete failed.';
                        if (xhr.responseJSON && xhr.responseJSON.error) {
                            msg = xhr.responseJSON.error;
                        }
                        toastr.error(msg);
                    }
                });
            });
        });
    </script>
@endsection
