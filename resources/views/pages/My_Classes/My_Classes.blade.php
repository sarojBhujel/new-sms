@extends('layouts.master')
@section('css')
    @toastr_css
@section('title')
    {{ trans('My_Classes_trans.title_page') }}
@stop
@endsection
@section('page-header')
<!-- breadcrumb -->
@section('PageTitle')
    {{ trans('My_Classes_trans.title_page') }}
@stop
<!-- breadcrumb -->
@endsection
@section('content')
<!-- row -->
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

                <button type="button" class="button x-small" data-toggle="modal" data-target="#exampleModal">
                    {{ trans('My_Classes_trans.add_class') }}
                </button>

                <button type="button" class="button x-small" id="btn_delete_all">
                    {{ trans('My_Classes_trans.delete_checkbox') }}
                </button>

                <br><br>

                <form id="classFilterForm" action="#" method="POST">
                    @csrf
                    <select class="selectpicker" data-style="btn-info" name="Grade_id" required
                        onchange="$('#classFilterForm').submit()">
                        <option value="" selected disabled>{{ trans('My_Classes_trans.Search_By_Grade') }}
                        </option>
                        @foreach ($grades as $grade)
                            <option value="{{ $grade->id }}">{{ $grade->Name }}</option>
                        @endforeach
                    </select>
                </form>

                <div class="table-responsive">
                    <table id="myClassesTable" class="table table-hover table-sm table-bordered p-0 no-datatable"
                        data-page-length="50" style="text-align: center">
                        <thead>
                            <tr>
                                <th><input name="select_all" id="example-select-all" type="checkbox"
                                        onclick="CheckAll('box1', this)" /></th>
                                <th>#</th>
                                <th>Class Name</th>
                                <th>Grade</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>

                <div class="modal fade" id="editClassModal" tabindex="-1" role="dialog"
                    aria-labelledby="editClassLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 style="font-family: 'Cairo', sans-serif;" class="modal-title" id="editClassLabel">
                                    {{ trans('My_Classes_trans.edit_class') }}
                                </h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form id="classEditForm">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" id="edit_class_id" name="id">
                                    <div class="row">
                                        <div class="col">
                                            <label for="edit_Name_Class" class="mr-sm-2">Class Name :</label>
                                            <input id="edit_Name_Class" type="text" class="form-control" name="Name"
                                                required>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="form-group">
                                        <label for="edit_Grade_id">{{ trans('My_Classes_trans.Name_Grade') }} :</label>
                                        <select class="form-control form-control-lg" id="edit_Grade_id" name="Grade_id">
                                            @foreach ($grades as $grade)
                                                <option value="{{ $grade->id }}">{{ $grade->Name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <br><br>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-dismiss="modal">{{ trans('Grades_trans.Close') }}</button>
                                        <button type="submit" class="btn btn-success">{{ trans('Grades_trans.submit') }}</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="deleteClassModal" tabindex="-1" role="dialog"
                    aria-labelledby="deleteClassLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 style="font-family: 'Cairo', sans-serif;" class="modal-title" id="deleteClassLabel">
                                    {{ trans('My_Classes_trans.delete_class') }}
                                </h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <p>{{ trans('My_Classes_trans.Warning_Grade') }}</p>
                                <p><strong id="delete_class_name"></strong></p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary"
                                    data-dismiss="modal">{{ trans('My_Classes_trans.Close') }}</button>
                                <button type="button" class="btn btn-danger" id="confirmDeleteClass">{{ trans('My_Classes_trans.Delete') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- add_modal_class -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 style="font-family: 'Cairo', sans-serif;" class="modal-title" id="exampleModalLabel">
                        {{ trans('My_Classes_trans.add_class') }}
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <form class=" row mb-30" action="{{ route('Classrooms.store') }}" method="POST">
                        @csrf
                        <div class="card-body">
                            <div class="repeater">
                                <div data-repeater-list="List_Classes">
                                    <div data-repeater-item>
                                        <div class="row">

                                            <div class="col">
                                                <label for="Name"
                                                    class="mr-sm-2">Class Name
                                                    :</label>
                                                <input class="form-control" type="text" name="Name" />
                                            </div>


                                            <div class="col">
                                                <label for="Name"
                                                    class="mr-sm-2">{{ trans('My_Classes_trans.Name_Grade') }}
                                                    :</label>

                                                <div class="box">
                                                    <select class="fancyselect" name="Grade_id">
                                                        @foreach ($grades as $grade)
                                                            <option value="{{ $grade->id }}">{{ $grade->Name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                            </div>

                                            <div class="col">
                                                <label for="Name"
                                                    class="mr-sm-2">{{ trans('My_Classes_trans.Processes') }}
                                                    :</label>
                                                <input class="btn btn-danger btn-block" data-repeater-delete
                                                    type="button"
                                                    value="{{ trans('My_Classes_trans.delete_row') }}" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-20">
                                    <div class="col-12">
                                        <input class="button" data-repeater-create type="button"
                                            value="{{ trans('My_Classes_trans.add_row') }}" />
                                    </div>

                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-dismiss="modal">{{ trans('Grades_trans.Close') }}</button>
                                    <button type="submit"
                                        class="btn btn-success">{{ trans('Grades_trans.submit') }}</button>
                                </div>


                            </div>
                        </div>
                    </form>
                </div>


            </div>

        </div>

    </div>
</div>

<!-- delete all  modal-->
<div class="modal fade" id="delete_all" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 style="font-family: 'Cairo', sans-serif;" class="modal-title" id="exampleModalLabel">
                    {{ trans('My_Classes_trans.delete_class') }}
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form action="{{ route('delete_all') }}" method="POST">
                {{ csrf_field() }}
                <div class="modal-body">
                    {{ trans('My_Classes_trans.Warning_Grade') }}
                    <input class="text" type="hidden" id="delete_all_id" name="delete_all_id" value=''>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                        data-dismiss="modal">{{ trans('My_Classes_trans.Close') }}</button>
                    <button type="submit" class="btn btn-danger">{{ trans('My_Classes_trans.Delete') }}</button>
                </div>
            </form>
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

@push('js')
    <script>
        $(function () {
            ajaxSetupCsrf();

            var editModal = $('#editClassModal');
            var deleteModal = $('#deleteClassModal');
            var currentDeleteId = null;

            var myClassesTable = $('#myClassesTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '/Classrooms',
                    type: 'GET',
                    data: function (d) {
                        d.grade_id = $('select[name="Grade_id"]').val();
                    }
                },
                columns: [
                    {
                        data: 'id',
                        render: function (data) {
                            return '<input type="checkbox" value="' + data + '" class="box1">';
                        },
                        orderable: false,
                        searchable: false,
                    },
                    {
                        data: 'id',
                        render: function (data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        },
                        orderable: false,
                        searchable: false,
                    },
                    { data: 'Name_Class' },
                    {
                        data: 'grade.Name',
                        defaultContent: ''
                    },
                    {
                        data: 'id',
                        render: function (data, type, row) {
                            return '<button type="button" class="btn btn-info btn-sm btn-class-edit" data-id="' + row.id + '" title="Edit"><i class="fa fa-edit"></i></button>' +
                                ' <button type="button" class="btn btn-danger btn-sm btn-class-delete" data-id="' + row.id + '" data-name="' + row.Name_Class + '" title="Delete"><i class="fa fa-trash"></i></button>';
                        },
                        orderable: false,
                        searchable: false,
                    }
                ],
                pageLength: 10,
                lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
                order: [[2, 'asc']],
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

            $('#classFilterForm').on('submit', function (e) {
                e.preventDefault();
                myClassesTable.ajax.reload();
            });

            $(document).on('click', '.btn-class-edit', function () {
                var id = $(this).data('id');
                $.getJSON('/Classrooms/' + id + '/edit', function (classroom) {
                    $('#edit_class_id').val(classroom.id);
                    $('#edit_Name_Class').val(classroom.Name_Class);
                    $('#edit_Grade_id').val(classroom.Grade_id);
                    editModal.modal('show');
                }).fail(function (xhr) {
                    showAjaxError(xhr, 'Unable to load class data.');
                });
            });

            $('#classEditForm').on('submit', function (e) {
                e.preventDefault();
                var id = $('#edit_class_id').val();
                var payload = {
                    Name: $('#edit_Name_Class').val(),
                    Grade_id: $('#edit_Grade_id').val()
                };

                $.ajax({
                    url: '/Classrooms/' + id,
                    method: 'PATCH',
                    data: payload,
                    success: function (response) {
                        editModal.modal('hide');
                        toastr.success(response.message || 'Class updated successfully.');
                        myClassesTable.ajax.reload();
                    },
                    error: function (xhr) {
                        showAjaxError(xhr, 'Update failed.');
                    }
                });
            });

            $(document).on('click', '.btn-class-delete', function () {
                currentDeleteId = $(this).data('id');
                $('#delete_class_name').text($(this).data('name'));
                deleteModal.modal('show');
            });

            $('#confirmDeleteClass').on('click', function () {
                if (!currentDeleteId) {
                    return;
                }

                $.ajax({
                    url: '/Classrooms/' + currentDeleteId,
                    method: 'DELETE',
                    success: function (response) {
                        deleteModal.modal('hide');
                        currentDeleteId = null;
                        toastr.success(response.message || 'Class deleted successfully.');
                        myClassesTable.ajax.reload();
                    },
                    error: function (xhr) {
                        showAjaxError(xhr, 'Delete failed.');
                    }
                });
            });

            $('#btn_delete_all').click(function () {
                var selected = [];
                $('#myClassesTable tbody input[type=checkbox]:checked').each(function () {
                    selected.push(this.value);
                });
                if (selected.length > 0) {
                    $('#delete_all').modal('show');
                    $('input[id="delete_all_id"]').val(selected);
                }
            });
        });
    </script>
@endpush

@endsection
