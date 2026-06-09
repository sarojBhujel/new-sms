@extends('layouts.master')
@section('css')
    @toastr_css
@section('title')
    Faculties
@stop
@endsection
@section('page-header')
<!-- breadcrumb -->
@section('PageTitle')
    Faculties
@stop
<!-- breadcrumb -->
@endsection
@section('content')
<div class="row">
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

                <button type="button" class="button x-small" data-toggle="modal" data-target="#addFacultyModal">
                    Add Faculty
                </button>
                <br><br>

                <div class="table-responsive">
                    <table id="facultiesTable" class="table p-0 table-hover table-sm table-bordered no-datatable" style="text-align: center">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Faculty Name</th>
                                <th>Faculty Code</th>
                                <th>Grade</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="viewFacultyModal" tabindex="-1" role="dialog" aria-labelledby="viewFacultyLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">View Faculty</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p><strong>Name:</strong> <span id="view_faculty_name"></span></p>
                    <p><strong>Code:</strong> <span id="view_faculty_code"></span></p>
                    <p><strong>Grade:</strong> <span id="view_faculty_grade"></span></p>
                    <p><strong>Status:</strong> <span id="view_faculty_status"></span></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editFacultyModal" tabindex="-1" role="dialog" aria-labelledby="editFacultyLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Faculty</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="facultyEditForm">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" id="edit_faculty_id" name="id">
                        <div class="form-group">
                            <label for="edit_faculty_name">Faculty Name:</label>
                            <input id="edit_faculty_name" type="text" class="form-control" name="faculty_name" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_faculty_code">Faculty Code:</label>
                            <input id="edit_faculty_code" type="text" class="form-control" name="faculty_code" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_grade_id">Grade:</label>
                            <select id="edit_grade_id" class="form-control" name="grade_id" required>
                                @foreach($grades as $grade)
                                    <option value="{{ $grade->id }}">{{ $grade->Name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="edit_status">Status:</label>
                            <select id="edit_status" class="form-control" name="status">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-success">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteFacultyModal" tabindex="-1" role="dialog" aria-labelledby="deleteFacultyLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete Faculty</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete the faculty <strong id="delete_faculty_name"></strong>?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteFaculty">Delete</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addFacultyModal" tabindex="-1" role="dialog" aria-labelledby="addFacultyLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Faculty</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('Faculties.store') }}" method="POST" id="facultyAddForm">
                        @csrf
                        <div class="form-group">
                            <label for="faculty_name">Faculty Name:</label>
                            <input id="faculty_name" type="text" name="faculty_name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="faculty_code">Faculty Code:</label>
                            <input id="faculty_code" type="text" name="faculty_code" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="grade_id">Grade:</label>
                            <select id="grade_id" class="form-control" name="grade_id" required>
                                @foreach($grades as $grade)
                                    <option value="{{ $grade->id }}">{{ $grade->Name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="status">Status:</label>
                            <select id="status" class="form-control" name="status">
                                <option value="1" selected>Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-success">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
@toastr_js
@toastr_render
@endsection
@push('js')
<script>
    $(function () {
        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });

        var facultiesTable = $('#facultiesTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '/Faculties',
                type: 'GET'
            },
            columns: [
                {
                    data: 'id',
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    },
                    orderable: false,
                    searchable: false
                },
                { data: 'faculty_name' },
                { data: 'faculty_code' },
                { data: 'grade.Name', defaultContent: '' },
                {
                    data: 'status',
                    render: function (data) {
                        return data ? 'Active' : 'Inactive';
                    }
                },
                {
                    data: 'id',
                    render: function (data, type, row) {
                        return `<button type="button" class="btn btn-secondary btn-sm btn-faculty-view" data-id="${row.id}">View</button>
                            <button type="button" class="btn btn-info btn-sm btn-faculty-edit" data-id="${row.id}">Edit</button>
                            <button type="button" class="btn btn-danger btn-sm btn-faculty-delete" data-id="${row.id}" data-name="${row.faculty_name}">Delete</button>`;
                    },
                    orderable: false,
                    searchable: false
                }
            ],
            pageLength: 10,
            order: [[1, 'asc']]
        });

        $(document).on('click', '.btn-faculty-view', function () {
            var id = $(this).data('id');
            $.getJSON('/Faculties/' + id, function (faculty) {
                $('#view_faculty_name').text(faculty.faculty_name);
                $('#view_faculty_code').text(faculty.faculty_code);
                $('#view_faculty_grade').text(faculty.grade ? faculty.grade.Name : 'N/A');
                $('#view_faculty_status').text(faculty.status ? 'Active' : 'Inactive');
                $('#viewFacultyModal').modal('show');
            });
        });

        $(document).on('click', '.btn-faculty-edit', function () {
            var id = $(this).data('id');
            $.getJSON('/Faculties/edit/' + id, function (faculty) {
                $('#edit_faculty_id').val(faculty.id);
                $('#edit_faculty_name').val(faculty.faculty_name);
                $('#edit_faculty_code').val(faculty.faculty_code);
                $('#edit_grade_id').val(faculty.grade_id);
                $('#edit_status').val(faculty.status ? 1 : 0);
                $('#editFacultyModal').modal('show');
            });
        });

        $('#facultyEditForm').on('submit', function (e) {
            e.preventDefault();
            var id = $('#edit_faculty_id').val();
            var payload = {
                faculty_name: $('#edit_faculty_name').val(),
                faculty_code: $('#edit_faculty_code').val(),
                grade_id: $('#edit_grade_id').val(),
                status: $('#edit_status').val(),
                _method: 'PATCH'
            };
            $.ajax({
                url: '/Faculties/' + id,
                type: 'POST',
                data: payload,
                success: function () {
                    $('#editFacultyModal').modal('hide');
                    facultiesTable.ajax.reload(null, false);
                    toastr.success('Faculty updated successfully.');
                },
                error: function (xhr) {
                    toastr.error(xhr.responseJSON?.error || 'Unable to update faculty.');
                }
            });
        });

        $(document).on('click', '.btn-faculty-delete', function () {
            $('#delete_faculty_name').text($(this).data('name'));
            $('#confirmDeleteFaculty').data('id', $(this).data('id'));
            $('#deleteFacultyModal').modal('show');
        });

        $('#confirmDeleteFaculty').on('click', function () {
            var id = $(this).data('id');
            $.ajax({
                url: '/Faculties/' + id,
                type: 'DELETE',
                success: function () {
                    $('#deleteFacultyModal').modal('hide');
                    facultiesTable.ajax.reload(null, false);
                    toastr.success('Faculty deleted successfully.');
                },
                error: function (xhr) {
                    toastr.error(xhr.responseJSON?.error || 'Unable to delete faculty.');
                }
            });
        });
    });
</script>
@endpush
