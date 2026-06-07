@extends('layouts.master')
@section('css')
    @toastr_css
@section('title')
List of Subjects
@stop
@endsection
@section('page-header')
    <!-- breadcrumb -->
@section('PageTitle')
List of Subjects
@stop
<!-- breadcrumb -->
@endsection
@section('content')
    <!-- row -->
    <div class="row">
        <div class="col-md-12 mb-30">
            <div class="card card-statistics h-100">
                <div class="card-body">
                    <div class="col-xl-12 mb-30">
                        <div class="card card-statistics h-100">
                            <div class="card-body">
                                <a href="{{route('subjects.create')}}" class="btn btn-success btn-sm" role="button"
                                   aria-pressed="true">Add New Subject</a><br><br>
                                <div class="table-responsive">
                                    <table id="subjectsTable" class="table table-hover table-sm table-bordered p-0 no-datatable"
                                           data-page-length="50"
                                           style="text-align: center">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Subject Name</th>
                                            <th>Grade</th>
                                            <th>Classroom</th>
                                            <th>Teacher Name</th>
                                            <th>Actions</th>
                                        </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>

                                <div class="modal fade" id="editSubjectModal" tabindex="-1" role="dialog" aria-labelledby="editSubjectLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 style="font-family: 'Cairo', sans-serif;" class="modal-title" id="editSubjectLabel">Edit Subject</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form id="subjectEditForm">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" id="edit_subject_id" name="id">
                                                    <div class="form-row">
                                                        <div class="col">
                                                            <label for="edit_Name">Subject Name</label>
                                                            <input id="edit_Name" type="text" name="Name" class="form-control" required>
                                                        </div>
                                                    </div>
                                                    <br>
                                                    <div class="form-row">
                                                        <div class="form-group col">
                                                            <label for="edit_Grade_id">Grade</label>
                                                            <select class="custom-select my-1 mr-sm-2" id="edit_Grade_id" name="Grade_id">
                                                                <option selected disabled>Choose Grade ...</option>
                                                                @foreach ($grades as $grade)
                                                                    <option value="{{ $grade->id }}">{{ $grade->Name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        <div class="form-group col">
                                                            <label for="edit_Class_id">Classroom</label>
                                                            <select name="Class_id" id="edit_Class_id" class="custom-select">
                                                                <option selected disabled>Choose Classroom ...</option>
                                                            </select>
                                                        </div>

                                                        <div class="form-group col">
                                                            <label for="edit_teacher_id">Teacher Name</label>
                                                            <select class="custom-select my-1 mr-sm-2" id="edit_teacher_id" name="teacher_id">
                                                                <option selected disabled>Choose Teacher ...</option>
                                                                @foreach ($teachers as $teacher)
                                                                    <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('My_Classes_trans.Close') }}</button>
                                                        <button type="submit" class="btn btn-success">{{ trans('My_Classes_trans.submit') }}</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal fade" id="deleteSubjectModal" tabindex="-1" role="dialog" aria-labelledby="deleteSubjectLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 style="font-family: 'Cairo', sans-serif;" class="modal-title" id="deleteSubjectLabel">Delete Subject</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <p>{{ trans('My_Classes_trans.Warning_Grade') }}</p>
                                                <p><strong id="delete_subject_name"></strong></p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('My_Classes_trans.Close') }}</button>
                                                <button type="button" class="btn btn-danger" id="confirmDeleteSubject">{{ trans('My_Classes_trans.Delete') }}</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- row closed -->
@endsection
@section('js')
    @toastr_js
    @toastr_render
@endsection

@push('js')
    <script>
        $(function () {
            ajaxSetupCsrf();

            var editModal = $('#editSubjectModal');
            var deleteModal = $('#deleteSubjectModal');
            var currentDeleteId = null;

            var subjectsTable = $('#subjectsTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '/subjects',
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
                    { data: 'grade.Name', defaultContent: '' },
                    { data: 'classroom.Name_Class', defaultContent: '' },
                    { data: 'teacher.name', defaultContent: '' },
                    {
                        data: 'id',
                        render: function (data, type, row) {
                            return '<button type="button" class="btn btn-info btn-sm btn-subject-edit" data-id="' + row.id + '" title="Edit"><i class="fa fa-edit"></i></button>' +
                                ' <button type="button" class="btn btn-danger btn-sm btn-subject-delete" data-id="' + row.id + '" data-name="' + row.name + '" title="Delete"><i class="fa fa-trash"></i></button>';
                        },
                        orderable: false,
                        searchable: false
                    }
                ],
                pageLength: 10,
                lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
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

            function loadSubjectClassrooms(gradeId, selectedClassroomId) {
                $('#edit_Class_id').empty().append('<option selected disabled>Choose Classroom ...</option>');
                if (!gradeId) {
                    return;
                }

                $.getJSON('/classes/' + gradeId, function (data) {
                    $.each(data, function (key, value) {
                        var option = $('<option>').val(key).text(value);
                        if (selectedClassroomId && key == selectedClassroomId) {
                            option.prop('selected', true);
                        }
                        $('#edit_Class_id').append(option);
                    });
                }).fail(function (xhr) {
                    showAjaxError(xhr, 'Unable to load classrooms.');
                });
            }

            $(document).on('click', '.btn-subject-edit', function () {
                var id = $(this).data('id');
                $.getJSON('/subjects/' + id + '/edit', function (subject) {
                    $('#edit_subject_id').val(subject.id);
                    $('#edit_Name').val(subject.name);
                    $('#edit_Grade_id').val(subject.grade_id);
                    $('#edit_teacher_id').val(subject.teacher_id);
                    loadSubjectClassrooms(subject.grade_id, subject.classroom_id);
                    editModal.modal('show');
                }).fail(function (xhr) {
                    showAjaxError(xhr, 'Unable to load subject data.');
                });
            });

            $('#edit_Grade_id').on('change', function () {
                loadSubjectClassrooms($(this).val());
            });

            $('#subjectEditForm').on('submit', function (e) {
                e.preventDefault();
                var id = $('#edit_subject_id').val();
                var payload = {
                    Name: $('#edit_Name').val(),
                    Grade_id: $('#edit_Grade_id').val(),
                    Class_id: $('#edit_Class_id').val(),
                    teacher_id: $('#edit_teacher_id').val()
                };

                $.ajax({
                    url: '/subjects/' + id,
                    method: 'PATCH',
                    data: payload,
                    success: function (response) {
                        editModal.modal('hide');
                        toastr.success(response.message || 'Subject updated successfully.');
                        subjectsTable.ajax.reload();
                    },
                    error: function (xhr) {
                        showAjaxError(xhr, 'Update failed.');
                    }
                });
            });

            $(document).on('click', '.btn-subject-delete', function () {
                currentDeleteId = $(this).data('id');
                $('#delete_subject_name').text($(this).data('name'));
                deleteModal.modal('show');
            });

            $('#confirmDeleteSubject').on('click', function () {
                if (!currentDeleteId) {
                    return;
                }

                $.ajax({
                    url: '/subjects/' + currentDeleteId,
                    method: 'DELETE',
                    success: function (response) {
                        deleteModal.modal('hide');
                        currentDeleteId = null;
                        toastr.success(response.message || 'Subject deleted successfully.');
                        subjectsTable.ajax.reload();
                    },
                    error: function (xhr) {
                        showAjaxError(xhr, 'Delete failed.');
                    }
                });
            });
        });
    </script>
@endpush
