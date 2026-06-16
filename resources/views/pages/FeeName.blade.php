@extends('layouts.master')
@section('css')
    @toastr_css
@section('title')
    Fee Setup
@stop
@endsection
@section('page-header')
<!-- breadcrumb -->
@section('PageTitle')
    Fee Setup
@stop
<!-- breadcrumb -->
@endsection
@section('content')
<!-- row -->
<div class="row">
    <div class="col-xl-12 mb-30">
        <div class="card card-statistics h-100">
            <div class="card-body">
                <button type="button" class="button x-small" id="openFeeModal">
                    Add Fee Setup
                </button>
                <br><br>

                <div class="table-responsive">
                    <table id="feeNamesTable" class="table p-0 table-hover table-sm table-bordered no-datatable"
                           style="text-align: center">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Fee Name</th>
                                <th>Amount</th>
                                <th>Type</th>
                                <th>Processes</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="feeNameModal" tabindex="-1" aria-labelledby="feeNameModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="feeNameModalLabel">Add Fee Setup</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger d-none" id="feeFormErrors"></div>
                <form id="feeNameForm">
                    @csrf
                    <input type="hidden" id="fee_name_id" name="id">

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="fee_name">Fee Name</label>
                                <input type="text" id="fee_name" name="name" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="fee_amount">Amount</label>
                                <input type="number" step="0.01" id="fee_amount" name="amount" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="fee_type">Type</label>
                                <select id="fee_type" name="type" class="form-control">
                                    <option value="monthly">Monthly</option>
                                    <option value="yearly">Yearly</option>
                                    <option value="custom">Custom</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row" id="yearlyMonthGroup" style="display: none;">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="fee_months_yearly">Month</label>
                                <select id="fee_months_yearly" name="months[]" class="form-control">
                                    <option value="">Select month</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row" id="customMonthGroup" style="display: none;">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="fee_months_custom">Months</label>
                                <select id="fee_months_custom" name="months[]" class="form-control" multiple size="4">
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="fee_description">Description</label>
                                <textarea id="fee_description" name="description" class="form-control" rows="3"></textarea>
                            </div>
                        </div>
                    </div>

                    <hr>
                    <h5>Class Fees</h5>
                    <div id="classFeeRows"></div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success" id="saveFeeName">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="viewFeeModal" tabindex="-1" aria-labelledby="viewFeeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewFeeModalLabel">Fee Setup Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="feeDetails"></div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteFeeModal" tabindex="-1" aria-labelledby="deleteFeeModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteFeeModalLabel">Delete Fee Setup</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this fee setup?</p>
                <p><strong id="delete_fee_name"></strong></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteFee">Delete</button>
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
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var currentDeleteId = null;
        var feeNamesUrl = '{{ url('fee-names') }}';
        var classesUrl = '{{ route('fee-names.classes') }}';
        var monthsUrl = '{{ route('nepali-months.index') }}';
        var feeNamesTable = $('#feeNamesTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: feeNamesUrl,
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
                { data: 'amount' },
                { data: 'type' },
                {
                    data: 'id',
                    render: function (data, type, row) {
                        return `<div class="dropdown show">
                            <a class="btn btn-success btn-sm dropdown-toggle" href="#" role="button" id="dropdownMenuLink${row.id}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Actions
                            </a>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink${row.id}" style="min-width: 220px;">
                                <a class="dropdown-item btn-view-fee" href="#" data-id="${row.id}"><i class="far fa-eye" style="color: #ffc107"></i>&nbsp; View</a>
                                <a class="dropdown-item btn-edit-fee" href="#" data-id="${row.id}"><i class="fa fa-edit" style="color: green"></i>&nbsp; Edit</a>
                                <a class="dropdown-item btn-delete-fee" href="#" data-id="${row.id}" data-name="${row.name}"><i class="fa fa-trash" style="color: red"></i>&nbsp; Delete</a>
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

        $('#openFeeModal').on('click', function () {
            resetFeeForm();
            $('#feeNameModalLabel').text('Add Fee Setup');
            loadMonths();
            loadClassRows();
            toggleMonthGroups('monthly');
            $('#feeNameModal').modal('show');
        });

        $(document).on('click', '.btn-view-fee', function (e) {
            e.preventDefault();
            var id = $(this).data('id');
            $.getJSON(`${feeNamesUrl}/${id}`, function (data) {
                var rows = data.fees.map(function (fee) {
                    return `<tr>
                        <td>${fee.grade}</td>
                        <td>${fee.classroom}</td>
                        <td>${fee.amount}</td>
                        <td>${fee.remarks || '-'}</td>
                    </tr>`;
                }).join('');

                $('#feeDetails').html(`<div class="mb-3">
                        <p><strong>Fee Name:</strong> ${data.name}</p>
                        <p><strong>Amount:</strong> ${data.amount}</p>
                        <p><strong>Type:</strong> ${data.type}</p>
                        <p><strong>Months:</strong> ${data.months.join(', ') || 'N/A'}</p>
                        <p><strong>Description:</strong> ${data.description || 'N/A'}</p>
                    </div>
                    <div>
                        <h6>Class fee mappings</h6>
                        <table class="table table-sm table-bordered">
                            <thead>
                                <tr>
                                    <th>Grade</th>
                                    <th>Class</th>
                                    <th>Amount</th>
                                    <th>Remarks</th>
                                </tr>
                            </thead>
                            <tbody>${rows}</tbody>
                        </table>
                    </div>`);

                $('#viewFeeModal').modal('show');
            }).fail(function () {
                toastr.error('Unable to load fee setup details.');
            });
        });

        $(document).on('click', '.btn-edit-fee', function (e) {
            e.preventDefault();
            resetFeeForm();
            var id = $(this).data('id');
            $.getJSON(`${feeNamesUrl}/${id}/edit`, function (data) {
                    $('#fee_name_id').val(data.id);
                    $('#fee_name').val(data.name);
                    $('#fee_amount').val(data.amount);
                    $('#fee_type').val(data.type);
                    $('#fee_description').val(data.description);
                    resetMonthSelections();
                    loadMonths(data.months);
                    toggleMonthGroups(data.type, data.months);
                    loadClassRows(data.fees);
                $('#feeNameModalLabel').text('Edit Fee Setup');
                $('#feeNameModal').modal('show');
            }).fail(function () {
                toastr.error('Unable to load fee setup.');
            });
        });

        $('#fee_type').on('change', function () {
            toggleMonthGroups($(this).val());
        });

        $('#fee_amount').on('input', function () {
            var amount = $(this).val();
            if (amount === null || amount === '') {
                return;
            }

            $('#classFeeRows').find('input.classFee').each(function () {
                if ($(this).val() === null || $(this).val() === '') {
                    $(this).val(amount);
                }
            });
        });

        $('#feeNameForm').on('submit', function (e) {
            e.preventDefault();
            $('#feeFormErrors').addClass('d-none').empty();
            var feeId = $('#fee_name_id').val();
            var method = feeId ? 'PATCH' : 'POST';
            var url = feeId ? `${feeNamesUrl}/${feeId}` : feeNamesUrl;
            var payload = $(this).serialize();

            $.ajax({
                url: url,
                method: method,
                data: payload,
                success: function () {
                    $('#feeNameModal').modal('hide');
                    toastr.success('Fee setup saved successfully.');
                    feeNamesTable.ajax.reload(null, false);
                },
                error: function (xhr) {
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        showFormErrors(xhr.responseJSON.errors);
                        return;
                    }

                    toastr.error('Unable to save fee setup.');
                }
            });
        });

        $(document).on('click', '.btn-delete-fee', function (e) {
            e.preventDefault();
            currentDeleteId = $(this).data('id');
            $('#delete_fee_name').text($(this).data('name'));
            $('#deleteFeeModal').modal('show');
        });

        $('#confirmDeleteFee').on('click', function () {
            if (!currentDeleteId) {
                return;
            }

            $.ajax({
                url: `${feeNamesUrl}/${currentDeleteId}`,
                type: 'DELETE',
                success: function () {
                    $('#deleteFeeModal').modal('hide');
                    currentDeleteId = null;
                    toastr.success('Fee setup deleted successfully.');
                    feeNamesTable.ajax.reload(null, false);
                },
                error: function () {
                    toastr.error('Unable to delete fee setup.');
                }
            });
        });

        function toggleMonthGroups(type, selectedMonths) {
            $('#yearlyMonthGroup, #customMonthGroup').hide();
            $('#fee_months_yearly').val('');
            $('#fee_months_custom').val([]);

            if (type === 'yearly') {
                $('#yearlyMonthGroup').show();
                if (selectedMonths && selectedMonths.length) {
                    $('#fee_months_yearly').val(selectedMonths[0]);
                }
            }

            if (type === 'custom') {
                $('#customMonthGroup').show();
                if (selectedMonths && selectedMonths.length) {
                    $('#fee_months_custom').val(selectedMonths);
                }
            }
        }

        function resetMonthSelections() {
            $('#fee_months_yearly').val('');
            $('#fee_months_custom').val([]);
        }

        function resetFeeForm() {
            $('#feeNameForm')[0].reset();
            $('#fee_name_id').val('');
            $('#feeFormErrors').addClass('d-none').empty();
            $('#feeNameForm .is-invalid').removeClass('is-invalid');
            $('#classFeeRows').empty();
        }

        function showFormErrors(errors) {
            var container = $('#feeFormErrors').removeClass('d-none');
            var html = '<ul>';
            $.each(errors, function (key, messages) {
                html += `<li>${messages.join('<br>')}</li>`;

                var field = $(`#feeNameForm [name="${key}"]`);
                if (field.length) {
                    field.addClass('is-invalid');
                }
            });
            html += '</ul>';
            container.html(html);
        }

        function loadMonths(selectedMonths) {
            selectedMonths = selectedMonths || [];
            $('#fee_months_yearly').html('<option value="">Select month</option>');
            $('#fee_months_custom').empty();
            $.getJSON(monthsUrl, function (months) {
                months.forEach(function (m) {
                    var value = m.name_en;
                    var text = m.name_ne || m.name_en;
                    var option = `<option value="${value}">${text}</option>`;
                    $('#fee_months_yearly').append(option);
                    $('#fee_months_custom').append(option);
                });

                if (selectedMonths && selectedMonths.length) {
                    // set selected for yearly (first) and custom
                    if (selectedMonths.length === 1) {
                        $('#fee_months_yearly').val(selectedMonths[0]);
                    }
                    $('#fee_months_custom').val(selectedMonths);
                }
            }).fail(function () {
                $('#fee_months_yearly').html('<option value="">Unable to load months</option>');
                $('#fee_months_custom').html('');
            });
        }

        function loadClassRows(feeMap) {
            $('#classFeeRows').html('<div class="text-center py-3">Loading classes...</div>');
            $.getJSON(classesUrl, function (classes) {
                var html = '';
                classes.forEach(function (item) {
                    var mapping = feeMap && feeMap[item.id] ? feeMap[item.id] : { amount: '', remarks: '' };
                    html += `<div class="card mb-2 p-3">
                        <div class="form-row align-items-end">
                            <div class="col-md-4">
                                <label class="font-weight-bold">${item.grade_name} / ${item.name}</label>
                                <input type="hidden" name="classroom_id[]" value="${item.id}">
                            </div>
                            <div class="col-md-3">
                                <label>Amount</label>
                                <input type="number" step="0.01" name="class_amount[]" class="form-control classFee" value="${mapping.amount || ''}">
                            </div>
                            <div class="col-md-5">
                                <label>Remarks</label>
                                <input type="text" name="class_remarks[]" class="form-control" value="${mapping.remarks || ''}">
                            </div>
                        </div>
                    </div>`;
                });
                $('#classFeeRows').html(html);
            }).fail(function () {
                $('#classFeeRows').html('<div class="text-danger">Unable to load classes.</div>');
            });
        }
    });
</script>
@endpush
