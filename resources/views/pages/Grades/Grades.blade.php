@extends('layouts.master')
@section('css')
    @toastr_css
@section('title')
    {{ trans('Grades_trans.title_page') }}
@stop
@endsection
@section('page-header')
<!-- breadcrumb -->
@section('PageTitle')
    {{ trans('main_trans.Grades') }}
@stop
<!-- breadcrumb -->
@endsection
@section('content')
<!-- row -->
<div class="row">


    @if ($errors->any())
        <div class="error">{{ $errors->first('Name') }}</div>
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

                <button type="button" class="button x-small" data-toggle="modal" data-target="#exampleModal">
                    {{ trans('Grades_trans.add_Grade') }}
                </button>
                <br><br>

                <div class="table-responsive">
                    <table id="gradesTable" class="table p-0 table-hover table-sm table-bordered no-datatable"
                        style="text-align: center">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ trans('Grades_trans.Name') }}</th>
                                <th>{{ trans('Grades_trans.Notes') }}</th>
                                <th>{{ trans('Grades_trans.Processes') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- edit_modal_Grade -->
    <div class="modal fade" id="editGradeModal" tabindex="-1" role="dialog" aria-labelledby="editGradeLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 style="font-family: 'Cairo', sans-serif;" class="modal-title" id="editGradeLabel">
                        {{ trans('Grades_trans.edit_Grade') }}
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="gradeEditForm">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" id="edit_grade_id" name="id">
                        <div class="row">
                            <div class="col">
                                <label for="edit_Name" class="mr-sm-2">{{ trans('Grades_trans.Name') }} :</label>
                                <input id="edit_Name" type="text" class="form-control" name="Name" required>
                            </div>
                        </div>
                        <div class="form-group mt-3">
                            <label for="edit_Notes">{{ trans('Grades_trans.Notes') }} :</label>
                            <textarea class="form-control" id="edit_Notes" name="Notes" rows="3"></textarea>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('Grades_trans.Close') }}</button>
                            <button type="submit" class="btn btn-success">{{ trans('Grades_trans.submit') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- delete_modal_Grade -->
    <div class="modal fade" id="deleteGradeModal" tabindex="-1" role="dialog" aria-labelledby="deleteGradeLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 style="font-family: 'Cairo', sans-serif;" class="modal-title" id="deleteGradeLabel">
                        {{ trans('Grades_trans.delete_Grade') }}
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>{{ trans('Grades_trans.Warning_Grade') }}</p>
                    <p><strong id="delete_grade_name"></strong></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('Grades_trans.Close') }}</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteGrade">{{ trans('Grades_trans.Delete') }}</button>
                </div>
            </div>
        </div>
    </div>

    <!-- add_modal_Grade -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 style="font-family: 'Cairo', sans-serif;" class="modal-title" id="exampleModalLabel">
                        Add Grade
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- add_form -->
                    <form action="{{ route('Grades.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col">
                                <label for="Name" class="mr-sm-2">Grade Name
                                    :</label>
                                <input id="Name" type="text" name="Name" class="form-control">
                            </div>
                        </div>
                            <div class="form-group">
                                <label for="exampleFormControlTextarea1">{{ trans('Grades_trans.Notes') }}
                                    :</label>
                                <textarea class="form-control" name="Notes" id="exampleFormControlTextarea1" rows="3"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="has_faculty">Has Faculty:</label>
                                <input type="checkbox" name="has_faculty" value="1">
                            </div>
                        <br><br>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                        data-dismiss="modal">{{ trans('Grades_trans.Close') }}</button>
                    <button type="submit" class="btn btn-success">{{ trans('Grades_trans.submit') }}</button>
                </div>
                </form>

            </div>
        </div>
    </div>

                                <div class="form-group mt-3">
                                    <label for="edit_Notes">{{ trans('Grades_trans.Notes') }} :</label>
                                    <textarea class="form-control" id="edit_Notes" name="Notes" rows="3"></textarea>
                                </div>
                                <div class="form-group mt-3">
                                    <label for="edit_has_faculty">Has Faculty:</label>
                                    <input type="checkbox" id="edit_has_faculty" name="has_faculty" value="1">
                                </div>
@section('js')
@toastr_js
@toastr_render
@endsection
@push('js')
    <script>
        $(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var editModal = $('#editGradeModal');
            var deleteModal = $('#deleteGradeModal');
            var currentDeleteId = null;
            var gradesTable;

            // Initialize DataTables with server-side processing
            gradesTable = $('#gradesTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '/Grades',
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
                    { data: 'Notes' },
                    {
                        data: 'id',
                        render: function(data, type, row) {
                            return `<button type="button" class="btn btn-info btn-sm btn-grade-edit" data-id="${row.id}" title="Edit">
                                <i class="fa fa-edit"></i>
                            </button>
                            <button type="button" class="btn btn-danger btn-sm btn-grade-delete" data-id="${row.id}" data-name="${row.Name}" title="Delete">
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
            $(document).on('click', '.btn-grade-edit', function () {
                var id = $(this).data('id');
                $.getJSON('/grades/edit/' + id, function (grade) {
                    $('#edit_grade_id').val(grade.id);
                    $('#edit_Name').val(grade.Name);
                    $('#edit_Notes').val(grade.Notes);
                        $('#edit_has_faculty').prop('checked', grade.has_faculty ? true : false);
                    editModal.modal('show');
                }).fail(function () {
                    toastr.error('Unable to load grade data.');
                });
            });

            // Edit form submission
            $('#gradeEditForm').on('submit', function (e) {
                e.preventDefault();
                var id = $('#edit_grade_id').val();
                var payload = {
                    Name: $('#edit_Name').val(),
                    Notes: $('#edit_Notes').val()
                };

                payload.has_faculty = $('#edit_has_faculty').is(':checked') ? 1 : 0;

                $.ajax({
                    url: '/grades/' + id,
                    method: 'PATCH',
                    data: payload,
                    success: function (response) {
                        editModal.modal('hide');
                        toastr.success(response.message || 'Grade updated successfully.');
                        gradesTable.ajax.reload();
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
            $(document).on('click', '.btn-grade-delete', function () {
                currentDeleteId = $(this).data('id');
                $('#delete_grade_name').text($(this).data('name'));
                deleteModal.modal('show');
            });

            // Delete confirmation
            $('#confirmDeleteGrade').on('click', function () {
                if (!currentDeleteId) {
                    return;
                }

                $.ajax({
                    url: '/grades/' + currentDeleteId,
                    method: 'DELETE',
                    success: function (response) {
                        deleteModal.modal('hide');
                        currentDeleteId = null;
                        toastr.success(response.message || 'Grade deleted successfully.');
                        gradesTable.ajax.reload();
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
@endpush
