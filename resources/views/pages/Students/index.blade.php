@extends('layouts.master')
@section('css')
    @toastr_css
@section('title')
    {{trans('main_trans.list_students')}}
@stop
@endsection
@section('page-header')
    <!-- breadcrumb -->
@section('PageTitle')
    {{trans('main_trans.list_students')}}
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
                                <a href="{{route('Students.create')}}" class="btn btn-success btn-sm" role="button"
                                   aria-pressed="true">{{trans('main_trans.add_student')}}</a><br><br>
                                <div class="table-responsive">
                                    <table id="datatable" class="table  table-hover table-sm table-bordered p-0"
                                           data-page-length="50"
                                           style="text-align: center">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>{{trans('Students_trans.name')}}</th>
                                            <th>{{trans('Students_trans.email')}}</th>
                                            <th>{{trans('Students_trans.gender')}}</th>
                                            <th>{{trans('Students_trans.Grade')}}</th>
                                            <th>{{trans('Students_trans.classrooms')}}</th>
                                            <th>{{trans('Students_trans.section')}}</th>
                                            <th>{{trans('Students_trans.Processes')}}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($students as $student)
                                            <tr>
                                            <td>{{ $loop->index+1 }}</td>
                                            <td>{{$student->name}}</td>
                                            <td>{{$student->email}}</td>
                                            <td>{{$student->gender->Name}}</td>
                                            <td>{{$student->grade->Name}}</td>
                                            <td>{{$student->classroom->Name_Class}}</td>
                                            <td>{{$student->section->Name_Section}}</td>
                                                <td>
                                                    <div class="dropdown show">
                                                        <a class="btn btn-success btn-sm dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            Actions
                                                        </a>
                                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                                            <a class="dropdown-item" href="{{route('Students.show',$student->id)}}"><i style="color: #ffc107" class="far fa-eye "></i>&nbsp;  View Student</a>
                                                            <a class="dropdown-item" href="{{route('Students.edit',$student->id)}}"><i style="color:green" class="fa fa-edit"></i>&nbsp;  Edit Student</a>
                                                            <a class="dropdown-item" href="{{route('Fees_Invoices.show',$student->id)}}"><i style="color: #0000cc" class="fa fa-edit"></i>&nbsp;Add Fee Invoice&nbsp;</a>
                                                            <a class="dropdown-item" href="{{route('receipt_students.show',$student->id)}}"><i style="color: #9dc8e2" class="fas fa-money-bill-alt"></i>&nbsp; &nbsp;Receipt</a>
                                                            <a class="dropdown-item" href="{{route('ProcessingFee.show',$student->id)}}"><i style="color: #9dc8e2" class="fas fa-money-bill-alt"></i>&nbsp; &nbsp;Processing Fee</a>
                                                            <a class="dropdown-item" href="{{route('Payment_students.show',$student->id)}}"><i style="color:goldenrod" class="fas fa-donate"></i>&nbsp; &nbsp;Payment Voucher</a>
                                                            <a class="dropdown-item" data-target="#Delete_Student{{ $student->id }}" data-toggle="modal" href="##Delete_Student{{ $student->id }}"><i style="color: red" class="fa fa-trash"></i>&nbsp;  Delete Student</a>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @include('pages.Students.Delete')
                                        @endforeach
                                    </table>
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
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var editModal = $('#editGradeModal');
            var deleteModal = $('#deleteGradeModal');
            var currentDeleteId = null;

            $('.btn-grade-edit').on('click', function () {
                var id = $(this).data('id');

                $.getJSON('/grades/edit/' + id, function (grade) {
                    $('#edit_grade_id').val(grade.id);
                    $('#edit_Name').val(grade.Name);
                    $('#edit_Notes').val(grade.Notes);
                    editModal.modal('show');
                }).fail(function () {
                    toastr.error('Unable to load grade data.');
                });
            });

            $('#gradeEditForm').on('submit', function (e) {
                e.preventDefault();
                var id = $('#edit_grade_id').val();
                var payload = {
                    Name: $('#edit_Name').val(),
                    Notes: $('#edit_Notes').val()
                };

                $.ajax({
                    url: '/grades/' + id,
                    method: 'PATCH',
                    data: payload,
                    success: function (response) {
                        editModal.modal('hide');
                        var row = $('button.btn-grade-edit[data-id="' + id + '"]').closest('tr');
                        row.find('td').eq(1).text(response.grade.Name);
                        row.find('td').eq(2).text(response.grade.Notes);
                        toastr.success(response.message || 'Grade updated successfully.');
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

            $('.btn-grade-delete').on('click', function () {
                currentDeleteId = $(this).data('id');
                $('#delete_grade_name').text($(this).data('name'));
                deleteModal.modal('show');
            });

            $('#confirmDeleteGrade').on('click', function () {
                if (!currentDeleteId) {
                    return;
                }

                $.ajax({
                    url: '/grades/' + currentDeleteId,
                    method: 'DELETE',
                    success: function (response) {
                        deleteModal.modal('hide');
                        $('button.btn-grade-delete[data-id="' + currentDeleteId + '"]').closest('tr').remove();
                        currentDeleteId = null;
                        toastr.success(response.message || 'Grade deleted successfully.');
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
