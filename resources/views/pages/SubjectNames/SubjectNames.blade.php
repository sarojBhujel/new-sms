@extends('layouts.master')
@section('css')
    @toastr_css
@section('title')
    Subject Names
@stop
@endsection
@section('page-header')
    <!-- breadcrumb -->
@section('PageTitle')
    Subject Names
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

                <button type="button" class="button x-small" data-toggle="modal" data-target="#addSubjectNameModal">
                    Add Subject Name
                </button>
                <br><br>

                <div class="table-responsive">
                    <table id="subjectNamesTable" class="table p-0 table-hover table-sm table-bordered no-datatable" style="text-align: center">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Subject Name</th>
                                <th>Code</th>
                                <th>Total Classes Assigned</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addSubjectNameModal" tabindex="-1" role="dialog" aria-labelledby="addSubjectNameLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addSubjectNameLabel">Add Subject Name</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('subject-names.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Subject items</label>
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm">
                                    <thead>
                                        <tr>
                                            <th style="width: 45%;">Name</th>
                                            <th style="width: 45%;">Code</th>
                                            <th style="width: 10%;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="subjectRows">
                                        @php
                                            $oldNames = old('name', ['']);
                                            $oldCodes = old('code', ['']);
                                        @endphp
                                        @foreach ($oldNames as $index => $oldName)
                                            <tr>
                                                <td><input type="text" name="name[]" class="form-control" value="{{ old('name.'.$index, $oldName) }}" required></td>
                                                <td><input type="text" name="code[]" class="form-control" value="{{ old('code.'.$index, $oldCodes[$index] ?? '') }}" required></td>
                                                <td class="text-center">
                                                    <button type="button" class="btn btn-danger btn-sm btn-remove-row" {{ $index === 0 ? 'disabled' : '' }}>×</button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <button type="button" id="addSubjectRow" class="btn btn-secondary btn-sm">Add Row</button>
                        </div>

                        <div class="form-group">
                            <label>Classroom mapping</label>
                            <div class="row">
                                @php
                                    $oldClassrooms = old('classroom_ids', []);
                                    $oldTeachers = old('teacher_id', []);
                                @endphp
                                @foreach ($classrooms as $classroom)
                                    <div class="col-12 classroom-item mb-2">
                                        <div class="form-row align-items-center">
                                            <div class="col-auto">
                                                <div class="form-check">
                                                    <input class="form-check-input classroom-checkbox" type="checkbox"
                                                        id="create_classroom_{{ $classroom->id }}"
                                                        value="{{ $classroom->id }}"
                                                        name="classroom_ids[]"
                                                        {{ in_array($classroom->id, $oldClassrooms) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="create_classroom_{{ $classroom->id }}">
                                                        {{ $classroom->Name_Class }}
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <input type="hidden" name="grade_ids[{{ $classroom->id }}]" value="{{ $classroom->Grade_id }}">
                                                <select class="form-control teacher-select {{ in_array($classroom->id, $oldClassrooms) ? '' : 'd-none' }}"
                                                    id="create_teachers_{{ $classroom->id }}"
                                                    name="teacher_id[{{ $classroom->id }}]">
                                                    <option value="">Choose Teacher ...</option>
                                                    @foreach ($teachers as $teacher)
                                                        <option value="{{ $teacher->id }}"
                                                            {{ isset($oldTeachers[$classroom->id]) && $oldTeachers[$classroom->id] == $teacher->id ? 'selected' : '' }}>{{ $teacher->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editSubjectNameModal" tabindex="-1" role="dialog" aria-labelledby="editSubjectNameLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editSubjectNameLabel">Edit Subject Name</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="subjectNameEditForm">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" id="edit_subject_name_id" name="id">
                    <div class="modal-body">
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="edit_name">Subject Name</label>
                                    <input id="edit_name" type="text" name="name" class="form-control" required>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="edit_code">Code</label>
                                    <input id="edit_code" type="text" name="code" class="form-control" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Classroom mapping</label>
                            <div class="row">
                                @foreach ($classrooms as $classroom)
                                    <div class="col-12 classroom-item mb-2">
                                        <div class="form-row align-items-center">
                                            <div class="col-auto">
                                                <div class="form-check">
                                                    <input class="form-check-input classroom-checkbox" type="checkbox"
                                                        id="edit_classroom_{{ $classroom->id }}"
                                                        value="{{ $classroom->id }}"
                                                        name="classroom_ids[]">
                                                    <label class="form-check-label" for="edit_classroom_{{ $classroom->id }}">
                                                        {{ $classroom->Name_Class }}
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <input type="hidden" name="grade_ids[{{ $classroom->id }}]" value="{{ $classroom->Grade_id }}">
                                                <select class="form-control teacher-select d-none"
                                                    id="edit_teachers_{{ $classroom->id }}"
                                                    name="teacher_id[{{ $classroom->id }}]">
                                                    <option value="">Choose Teacher ...</option>
                                                    @foreach ($teachers as $teacher)
                                                        <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="viewSubjectNameModal" tabindex="-1" role="dialog" aria-labelledby="viewSubjectNameLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewSubjectNameLabel">Subject Name Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p><strong>Subject Name:</strong> <span id="view_subject_name"></span></p>
                    <p><strong>Code:</strong> <span id="view_subject_code"></span></p>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm">
                            <thead>
                                <tr>
                                    <th>Classroom</th>
                                    <th>Teacher</th>
                                </tr>
                            </thead>
                            <tbody id="view_subject_mappings"></tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteSubjectNameModal" tabindex="-1" role="dialog" aria-labelledby="deleteSubjectNameLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteSubjectNameLabel">Delete Subject Name</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this subject name?</p>
                    <p><strong id="delete_subject_name"></strong></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteSubjectName">Delete</button>
                </div>
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

            var currentDeleteId = null;
            var subjectNamesTable = $('#subjectNamesTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '/subject-names',
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
                    { data: 'name' },
                    { data: 'code' },
                    { data: 'subjects_count' },
                    {
                        data: 'id',
                        render: function (data, type, row) {
                            return `
                                <button type="button" class="btn btn-info btn-sm btn-subjectname-view" data-id="${row.id}" title="View">
                                    <i class="fa fa-eye"></i>
                                </button>
                                <button type="button" class="btn btn-primary btn-sm btn-subjectname-edit" data-id="${row.id}" title="Edit">
                                    <i class="fa fa-edit"></i>
                                </button>
                                <button type="button" class="btn btn-danger btn-sm btn-subjectname-delete" data-id="${row.id}" data-name="${row.name}" title="Delete">
                                    <i class="fa fa-trash"></i>
                                </button>
                            `;
                        },
                        orderable: false,
                        searchable: false
                    }
                ],
                pageLength: 10,
                order: [[1, 'asc']],
            });

            $('#addSubjectRow').on('click', function () {
                var row = `<tr>
                    <td><input type="text" name="name[]" class="form-control" required></td>
                    <td><input type="text" name="code[]" class="form-control" required></td>
                    <td class="text-center"><button type="button" class="btn btn-danger btn-sm btn-remove-row">×</button></td>
                </tr>`;
                $('#subjectRows').append(row);
            });

            $(document).on('click', '.btn-remove-row', function () {
                $(this).closest('tr').remove();
            });

            $(document).on('change', '.classroom-checkbox', function () {
                var classroomItem = $(this).closest('.classroom-item');
                var select = classroomItem.find('.teacher-select');
                if ($(this).is(':checked')) {
                    select.removeClass('d-none');
                } else {
                    select.addClass('d-none').val('');
                }
            });

            $(document).on('click', '.btn-subjectname-view', function () {
                var id = $(this).data('id');
                $.getJSON('/subject-names/' + id, function (response) {
                    $('#view_subject_name').text(response.name);
                    $('#view_subject_code').text(response.code);
                    var tbody = $('#view_subject_mappings').empty();
                    if (response.mappings.length === 0) {
                        tbody.append('<tr><td colspan="2">No class mappings assigned.</td></tr>');
                    } else {
                        response.mappings.forEach(function (mapping) {
                            tbody.append(`<tr><td>${mapping.classroom}</td><td>${mapping.teacher}</td></tr>`);
                        });
                    }
                    $('#viewSubjectNameModal').modal('show');
                }).fail(function () {
                    toastr.error('Unable to load subject name details.');
                });
            });

            $(document).on('click', '.btn-subjectname-edit', function () {
                var id = $(this).data('id');
                $.getJSON('/subject-names/' + id + '/edit', function (response) {
                    $('#edit_subject_name_id').val(response.id);
                    $('#edit_name').val(response.name);
                    $('#edit_code').val(response.code);

                    $('.classroom-item').each(function () {
                        var checkbox = $(this).find('.classroom-checkbox');
                        var select = $(this).find('.teacher-select');
                        var classroomId = checkbox.val();

                        if (response.classroom_ids.includes(parseInt(classroomId, 10))) {
                            checkbox.prop('checked', true);
                            select.removeClass('d-none');
                            select.val(response.mapped_teachers[classroomId] || '');
                        } else {
                            checkbox.prop('checked', false);
                            select.addClass('d-none').val('');
                        }
                    });

                    $('#editSubjectNameModal').modal('show');
                }).fail(function () {
                    toastr.error('Unable to load subject name data.');
                });
            });

            $('#subjectNameEditForm').on('submit', function (e) {
                e.preventDefault();
                var id = $('#edit_subject_name_id').val();
                var payload = {
                    name: $('#edit_name').val(),
                    code: $('#edit_code').val(),
                    classroom_ids: [],
                    teacher_id: {},
                    grade_ids: {}
                };

                $('#editSubjectNameModal .classroom-item').each(function () {
                    var checkbox = $(this).find('.classroom-checkbox');
                    var select = $(this).find('.teacher-select');
                    var gradeInput = $(this).find('input[name^="grade_ids["]');

                    if (checkbox.is(':checked')) {
                        var classroomId = checkbox.val();
                        payload.classroom_ids.push(classroomId);
                        payload.teacher_id[classroomId] = select.val();
                        payload.grade_ids[classroomId] = gradeInput.val();
                    }
                });

                $.ajax({
                    url: '/subject-names/' + id,
                    method: 'PATCH',
                    data: payload,
                    success: function (response) {
                        $('#editSubjectNameModal').modal('hide');
                        toastr.success(response.message || 'Subject name updated successfully.');
                        subjectNamesTable.ajax.reload();
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

            $(document).on('click', '.btn-subjectname-delete', function () {
                currentDeleteId = $(this).data('id');
                $('#delete_subject_name').text($(this).data('name'));
                $('#deleteSubjectNameModal').modal('show');
            });

            $('#confirmDeleteSubjectName').on('click', function () {
                if (!currentDeleteId) {
                    return;
                }

                $.ajax({
                    url: '/subject-names/' + currentDeleteId,
                    method: 'DELETE',
                    success: function (response) {
                        $('#deleteSubjectNameModal').modal('hide');
                        currentDeleteId = null;
                        toastr.success(response.message || 'Subject name deleted successfully.');
                        subjectNamesTable.ajax.reload();
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
