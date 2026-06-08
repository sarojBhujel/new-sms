@extends('layouts.master')

@section('title')
Fiscal Years
@endsection
@section('css')
<link href="https://nepalidatepicker.sajanmaharjan.com.np/v5/nepali.datepicker/css/nepali.datepicker.v5.0.6.min.css" rel="stylesheet" type="text/css"/>

@endsection

@section('page-header')
<div class="page-title">
    <div class="row">
        <div class="col-sm-6">
            <h4 class="mb-0">Fiscal Years</h4>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12 mb-3">
        <button class="btn btn-success" data-toggle="modal" data-target="#addFiscalYearModal">Add Fiscal Year</button>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="fiscalYearsTable" style="width:100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Start (NP)</th>
                                <th>End (NP)</th>
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
</div>

@include('pages.FiscalYears.modals')
@endsection

@section('js')
<script src="https://nepalidatepicker.sajanmaharjan.com.np/v5/nepali.datepicker/js/nepali.datepicker.v5.0.6.min.js" type="text/javascript"></script>

<script>
    $(document).ready(function () {
        $('#addStartDateNp, #addEndDateNp').NepaliDatePicker({
            container: "#addFiscalYearModal"
        });
        $('#editStartDateNp, #editEndDateNp').NepaliDatePicker({
            container: "#editFiscalYearModal"
        });

        const table = $('#fiscalYearsTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ route('fiscal-years.index') }}',
                type: 'GET'
            },
            columns: [
                { data: 'id', name: 'id' },
                { data: 'name', name: 'name' },
                { data: 'start_date_np', name: 'start_date_np' },
                { data: 'end_date_np', name: 'end_date_np' },
                {
                    data: 'status',
                    render: function (data) {
                        return data ? '<span class="badge badge-success">Active</span>' : '<span class="badge badge-secondary">Inactive</span>';
                    }
                },
                {
                    data: 'id',
                    orderable: false,
                    searchable: false,
                    render: function (data, type, row) {
                        let buttons = `
                            <button class="btn btn-sm btn-info mr-1 viewFiscalYear" data-id="${data}">View</button>
                            <button class="btn btn-sm btn-primary mr-1 editFiscalYear" data-id="${data}">Edit</button>
                            <button class="btn btn-sm btn-danger mr-1 deleteFiscalYear" data-id="${data}">Delete</button>
                        `;

                        if (!row.status) {
                            buttons += `<button class="btn btn-sm btn-warning activateFiscalYear" data-id="${data}">Activate</button>`;
                        }

                        return buttons;
                    }
                }
            ]
        });

        function convertBsToAd(bsValue) {
            if (!bsValue) {
                return '';
            }

            try {
                return NepaliFunctions.BS2AD(bsValue);
            } catch (error) {
                return '';
            }
        }

        function isBsAfter(bsStart, bsEnd) {
            if (!bsStart || !bsEnd) {
                return true;
            }

            return bsEnd > bsStart;
        }

        function syncFiscalYearDates(formPrefix) {
            const bsStart = $(`#${formPrefix}StartDateNp`).val();
            const bsEnd = $(`#${formPrefix}EndDateNp`).val();
            const adStart = convertBsToAd(bsStart);
            const adEnd = convertBsToAd(bsEnd);

            $(`#${formPrefix}StartDate`).val(adStart);
            $(`#${formPrefix}EndDate`).val(adEnd);
        }

        $('#addStartDateNp, #addEndDateNp').on('change', function () {
            syncFiscalYearDates('add');
        });

        $('#editStartDateNp, #editEndDateNp').on('change', function () {
            syncFiscalYearDates('edit');
        });

        $('#addFiscalYearForm').on('submit', function (event) {
            event.preventDefault();
            if (!isBsAfter($('#addStartDateNp').val(), $('#addEndDateNp').val())) {
                toastr.error('End Date (NP) must be after Start Date (NP).');
                return;
            }

            syncFiscalYearDates('add');
            const form = $(this);
            const data = form.serialize();

            $.ajax({
                url: '{{ route('fiscal-years.store') }}',
                method: 'POST',
                data: data,
                success: function (response) {
                    table.ajax.reload(null, false);
                    $('#addFiscalYearModal').modal('hide');
                    form[0].reset();
                    toastr.success(response.message);
                },
                error: function (xhr) {
                    const message = xhr.responseJSON?.error || 'Unable to save fiscal year.';
                    toastr.error(message);
                }
            });
        });

        $('#fiscalYearsTable').on('click', '.viewFiscalYear', function () {
            const id = $(this).data('id');
            $.get(`{{ url('fiscal-years') }}/${id}`, function (data) {
                $('#viewName').text(data.name);
                $('#viewStartDateNp').text(data.start_date_np);
                $('#viewEndDateNp').text(data.end_date_np);
                $('#viewStartDate').text(data.start_date);
                $('#viewEndDate').text(data.end_date);
                $('#viewStatus').html(data.status ? '<span class="badge badge-success">Active</span>' : '<span class="badge badge-secondary">Inactive</span>');
                $('#viewFiscalYearModal').modal('show');
            });
        });

        $('#fiscalYearsTable').on('click', '.editFiscalYear', function () {
            const id = $(this).data('id');
            $.get(`{{ url('fiscal-years') }}/${id}/edit`, function (data) {
                $('#editFiscalYearId').val(data.id);
                $('#editName').val(data.name);
                $('#editStartDateNp').val(data.start_date_np);
                $('#editEndDateNp').val(data.end_date_np);
                $('#editStartDate').val(data.start_date);
                $('#editEndDate').val(data.end_date);
                $('#editStatus').prop('checked', data.status);
                $('#editFiscalYearModal').modal('show');
            });
        });

        $('#editFiscalYearForm').on('submit', function (event) {
            event.preventDefault();
            if (!isBsAfter($('#editStartDateNp').val(), $('#editEndDateNp').val())) {
                toastr.error('End Date (NP) must be after Start Date (NP).');
                return;
            }

            syncFiscalYearDates('edit');
            const id = $('#editFiscalYearId').val();
            const data = $(this).serialize();

            $.ajax({
                url: `{{ url('fiscal-years') }}/${id}`,
                method: 'PUT',
                data: data,
                success: function (response) {
                    table.ajax.reload(null, false);
                    $('#editFiscalYearModal').modal('hide');
                    toastr.success(response.message);
                },
                error: function (xhr) {
                    const message = xhr.responseJSON?.error || 'Unable to update fiscal year.';
                    toastr.error(message);
                }
            });
        });

        $('#fiscalYearsTable').on('click', '.deleteFiscalYear', function () {
            const id = $(this).data('id');
            $('#deleteFiscalYearId').val(id);
            $('#deleteFiscalYearModal').modal('show');
        });

        $('#confirmDeleteFiscalYear').on('click', function () {
            const id = $('#deleteFiscalYearId').val();

            $.ajax({
                url: `{{ url('fiscal-years') }}/${id}`,
                method: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    table.ajax.reload(null, false);
                    $('#deleteFiscalYearModal').modal('hide');
                    toastr.success(response.message);
                },
                error: function (xhr) {
                    const message = xhr.responseJSON?.error || 'Unable to delete fiscal year.';
                    toastr.error(message);
                }
            });
        });

        $('#fiscalYearsTable').on('click', '.activateFiscalYear', function () {
            const id = $(this).data('id');
            $('#activateFiscalYearId').val(id);
            $('#activateFiscalYearModal').modal('show');
        });

        $('#confirmActivateFiscalYear').on('click', function () {
            const id = $('#activateFiscalYearId').val();
            $.ajax({
                url: `{{ url('fiscal-years') }}/${id}/activate`,
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    table.ajax.reload(null, false);
                    $('#activateFiscalYearModal').modal('hide');
                    toastr.success(response.message);
                },
                error: function (xhr) {
                    const message = xhr.responseJSON?.error || 'Unable to activate fiscal year.';
                    toastr.error(message);
                }
            });
        });
    });
</script>
@endsection
