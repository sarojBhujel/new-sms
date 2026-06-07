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
                                    <table id="studentsTable" class="table table-hover table-sm table-bordered p-0"
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
                                        <tbody></tbody>
                                    </table>
                                </div>

                                <div class="modal fade" id="deleteStudentModal" tabindex="-1" aria-labelledby="deleteStudentLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 style="font-family: 'Cairo', sans-serif;" class="modal-title" id="deleteStudentLabel">
                                                    {{ trans('Students_trans.Deleted_Student') }}
                                                </h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <p>{{ trans('Students_trans.Deleted_Student_tilte') }}</p>
                                                <p><strong id="delete_student_name"></strong></p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('Students_trans.Close') }}</button>
                                                <button type="button" class="btn btn-danger" id="confirmDeleteStudent">{{ trans('Students_trans.submit') }}</button>
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

            var currentDeleteId = null;
            var studentsUrl = '{{ url('Students') }}';
            var feesInvoiceUrl = '{{ url('Fees_Invoices') }}';
            var receiptUrl = '{{ url('receipt_students') }}';
            var processingFeeUrl = '{{ url('ProcessingFee') }}';
            var paymentUrl = '{{ url('Payment_students') }}';

            var studentsTable = $('#studentsTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('Students.index') }}',
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
                    { data: 'email' },
                    { data: 'gender' },
                    { data: 'grade' },
                    { data: 'classroom' },
                    { data: 'section' },
                    {
                        data: 'id',
                        render: function (data, type, row) {
                            return `<div class="dropdown show">
                                <a class="btn btn-success btn-sm dropdown-toggle" href="#" role="button" id="dropdownMenuLink${row.id}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Actions
                                </a>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink${row.id}" style="min-width: 220px;">
                                    <a class="dropdown-item" href="${studentsUrl}/${row.id}"><i style="color: #ffc107" class="far fa-eye"></i>&nbsp; View Student</a>
                                    <a class="dropdown-item" href="${studentsUrl}/${row.id}/edit"><i style="color:green" class="fa fa-edit"></i>&nbsp; Edit Student</a>
                                    <a class="dropdown-item" href="${feesInvoiceUrl}/${row.id}"><i style="color: #0000cc" class="fa fa-edit"></i>&nbsp; Add Fee Invoice</a>
                                    <a class="dropdown-item" href="${receiptUrl}/${row.id}"><i style="color: #9dc8e2" class="fas fa-money-bill-alt"></i>&nbsp; Receipt</a>
                                    <a class="dropdown-item" href="${processingFeeUrl}/${row.id}"><i style="color: #9dc8e2" class="fas fa-money-bill-alt"></i>&nbsp; Processing Fee</a>
                                    <a class="dropdown-item" href="${paymentUrl}/${row.id}"><i style="color:goldenrod" class="fas fa-donate"></i>&nbsp; Payment Voucher</a>
                                    <a class="dropdown-item btn-delete-student" href="#" data-id="${row.id}" data-name="${row.name}"><i style="color: red" class="fa fa-trash"></i>&nbsp; Delete Student</a>
                                </div>
                            </div>`;
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

            $(document).on('click', '.btn-delete-student', function (e) {
                e.preventDefault();
                currentDeleteId = $(this).data('id');
                $('#delete_student_name').text($(this).data('name'));
                $('#deleteStudentModal').modal('show');
            });

            $('#confirmDeleteStudent').on('click', function () {
                if (!currentDeleteId) {
                    return;
                }

                $.ajax({
                    url: `${studentsUrl}/${currentDeleteId}`,
                    type: 'DELETE',
                    success: function (response) {
                        $('#deleteStudentModal').modal('hide');
                        currentDeleteId = null;
                        toastr.success(response.message || 'Student deleted successfully.');
                        studentsTable.ajax.reload(null, false);
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
