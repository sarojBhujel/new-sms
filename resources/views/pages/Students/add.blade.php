@extends('layouts.master')
@section('css')
    @toastr_css
    <link href="https://nepalidatepicker.sajanmaharjan.com.np/v5/nepali.datepicker/css/nepali.datepicker.v5.0.6.min.css" rel="stylesheet" type="text/css"/>
@section('title')
    {{ trans('main_trans.add_student') }}
@stop
@endsection
@section('page-header')
<!-- breadcrumb -->
@section('PageTitle')
    {{ trans('main_trans.add_student') }}
@stop
<!-- breadcrumb -->
@endsection
@section('content')
<!-- row -->
<div class="row">
    <div class="col-md-12 mb-30">
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

                <form method="post" action="{{ route('Students.store') }}" autocomplete="off" enctype="multipart/form-data">
                    @csrf
                    <h6 style="font-family: 'Cairo', sans-serif;color: blue">
                        Personal 
                        Information</h6><br>
                    <div class="row">

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Name : <span
                                        class="text-danger">*</span></label>
                                <input class="form-control" name="name" type="text">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="create_login_credentials" name="create_login_credentials" value="1">
                                <label class="custom-control-label" for="create_login_credentials">Create Login Credentials</label>
                            </div>
                        </div>

                        <div id="credentials-section" style="display:none; width:100%;">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Email : </label>
                                    <input type="email" name="email" class="form-control">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Password :</label>
                                    <input type="password" name="password" class="form-control">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="gender">Gender : <span
                                        class="text-danger">*</span></label>
                                <select class="custom-select mr-sm-2" name="gender_id">
                                    <option selected disabled>{{ trans('Parent_trans.Choose') }}...</option>
                                    @foreach ($Genders as $Gender)
                                        <option value="{{ $Gender->id }}">{{ $Gender->Name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="nal_id">Nationality: <span
                                        class="text-danger">*</span></label>
                                <select class="custom-select mr-sm-2" name="nationalitie_id">
                                    <option selected disabled>{{ trans('Parent_trans.Choose') }}...</option>
                                    @foreach ($nationals as $nal)
                                        <option value="{{ $nal->id }}" {{ $nal->id==155 ? 'selected':'' }}>{{ $nal->Name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="bg_id">Blood Type : </label>
                                <select class="custom-select mr-sm-2" name="blood_id">
                                    <option selected disabled>{{ trans('Parent_trans.Choose') }}...</option>
                                    @foreach ($bloods as $bg)
                                        <option value="{{ $bg->id }}">{{ $bg->Name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>{{ trans('Students_trans.Date_of_Birth') }} :</label>
                                <input class="form-control" type="text" id="datepicker-action" name="Date_Birth"
                                    data-date-format="yyyy-mm-dd">
                            </div>
                        </div>

                    </div>

                    <h6 style="font-family: 'Cairo', sans-serif;color: blue">
                        {{ trans('Students_trans.Student_information') }}</h6><br>
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="Grade_id">Grade : <span
                                        class="text-danger">*</span></label>
                                <select class="custom-select mr-sm-2" name="Grade_id">
                                    <option selected disabled>{{ trans('Parent_trans.Choose') }}...</option>
                                    @foreach ($my_classes as $c)
                                        <option value="{{ $c->id }}">{{ $c->Name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="faculty_id">Faculty :</label>
                                <select class="custom-select mr-sm-2" name="faculty_id" id="faculty_id">
                                    <option selected disabled>{{ trans('Parent_trans.Choose') }}...</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="Classroom_id">Class : <span
                                        class="text-danger">*</span></label>
                                <select class="custom-select mr-sm-2" name="Classroom_id">

                                </select>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="section_id">Section : </label>
                                <select class="custom-select mr-sm-2" name="section_id">

                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="parent_id">Parent : <span
                                        class="text-danger">*</span></label>
                                <div class="input-group mb-2">
                                    <select class="custom-select mr-sm-2" name="parent_id" id="parent_id">
                                        <option selected disabled>{{ trans('Parent_trans.Choose') }}...</option>
                                        @foreach ($parents as $parent)
                                            <option value="{{ $parent->id }}">{{ $parent->Name_Father }}</option>
                                        @endforeach
                                    </select>
                                    <div class="input-group-append">
                                        <button class="btn btn-success btn-sm" type="button" id="addParentBtn" data-toggle="modal" data-target="#quickCreateParentModal">
                                            <i class="fa fa-plus"></i> Add Parent
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="admission_no">Admission No :</label>
                                <input class="form-control" name="admission_no" type="text" value="{{ old('admission_no') }}">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="admission_date">Admission Date:</label>
                                <input class="form-control" name="admission_date" type="text" id="datepicker-admission-date" value="{{ old('admission_date') }}" data-date-format="yyyy-mm-dd">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="roll_no">Roll No :</label>
                                <input class="form-control" name="roll_no" type="text" value="{{ old('roll_no') }}">
                            </div>
                        </div>


                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Attachments : <span
                                        class="text-danger">*</span></label>
                                <input type="file" accept="image/*" name="photos[]" multiple>
                            </div>
                        </div>

                    </div><br>
                    <button class="btn btn-success btn-sm nextBtn btn-lg pull-right"
                        type="submit">Submit</button>
                </form>

                <!-- Quick Create Parent Modal -->
                <div class="modal fade" id="quickCreateParentModal" tabindex="-1" role="dialog" aria-labelledby="quickCreateParentModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <form id="quickCreateParentForm">
                                @csrf
                                <div class="modal-header">
                                    <h5 class="modal-title" id="quickCreateParentModalLabel">Quick Create Parent</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <!-- Error Messages -->
                                    <div id="parentFormErrors" class="alert alert-danger" role="alert" style="display: none;">
                                        <ul id="errorList" style="margin-bottom: 0;"></ul>
                                    </div>

                                    <!-- Form Fields -->
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="parentNameFather">Father Name : <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="parentNameFather" name="Name_Father" required>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="parentNameMother">Mother Name : <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="parentNameMother" name="Name_Mother" required>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="parentNationalId">Citizenship No :</label>
                                            <input type="text" class="form-control" id="parentNationalId" name="National_ID_Father" placeholder="Optional">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="parentPhoneFather">Father Phone :</label>
                                            <input type="tel" class="form-control" id="parentPhoneFather" name="Phone_Father" placeholder="Optional">
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="parentPhoneMother">Mother Phone :</label>
                                            <input type="tel" class="form-control" id="parentPhoneMother" name="Phone_Mother" placeholder="Optional">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="parentJobFather">Father Job :</label>
                                            <input type="text" class="form-control" id="parentJobFather" name="Job_Father" placeholder="Optional">
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="parentJobMother">Mother Job :</label>
                                            <input type="text" class="form-control" id="parentJobMother" name="Job_Mother" placeholder="Optional">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="parentAddressFather">Father Address :</label>
                                            <input type="text" class="form-control" id="parentAddressFather" name="Address_Father" placeholder="Optional">
                                        </div>
                                    </div>

                                    <div class="form-group form-check mt-2">
                                        <input type="checkbox" class="form-check-input" id="createUserToggle" name="create_user" value="1">
                                        <label class="form-check-label" for="createUserToggle">Create User Account?</label>
                                    </div>

                                    <div id="accountFields" style="display: none;">
                                        <div class="form-group">
                                            <label for="parentEmail">Email :</label>
                                            <input type="email" class="form-control" id="parentEmail" name="email" placeholder="Email address">
                                        </div>
                                        <div class="form-group">
                                            <label for="parentPassword">Password :</label>
                                            <input type="password" class="form-control" id="parentPassword" name="password" placeholder="Min 6 characters">
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-primary">Create Parent</button>
                                </div>
                            </form>
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
<script src="https://nepalidatepicker.sajanmaharjan.com.np/v5/nepali.datepicker/js/nepali.datepicker.v5.0.6.min.js" type="text/javascript"></script>
<script>
    function toggleCredentialsSection() {
        var isChecked = $('#create_login_credentials').is(':checked');
        $('#credentials-section').toggle(isChecked);
    }

    $(document).ready(function () {
        $('#datepicker-action').NepaliDatePicker();
        $('#datepicker-admission-date').NepaliDatePicker();
        toggleCredentialsSection();
        $('#create_login_credentials').on('change', toggleCredentialsSection);
    });
</script>
<script>
    $(document).ready(function () {
        $('select[name="Grade_id"]').on('change', function () {
            var Grade_id = $(this).val();
            if (Grade_id) {
                $.ajax({
                    url: '/grades/' + Grade_id + '/faculties',
                    type: 'GET',
                    dataType: 'json',
                    success: function (data) {
                        var $faculty = $('#faculty_id');
                        $faculty.empty();
                        $faculty.append('<option selected disabled>{{ trans('Parent_trans.Choose') }}...</option>');
                        if (data.has_faculty) {
                            $.each(data.faculties, function (key, value) {
                                $faculty.append('<option value="' + value.id + '">' + value.faculty_name + '</option>');
                            });
                            $faculty.prop('disabled', false);
                        } else {
                            $faculty.prop('disabled', true);
                        }
                    },
                });
            } else {
                console.log('AJAX load did not work');
            }
        });
    });
</script>

<!-- Quick Create Parent Modal JavaScript -->
<script>
    $(document).ready(function () {
        // Toggle account fields
        $('#createUserToggle').on('change', function () {
            if ($(this).is(':checked')) {
                $('#accountFields').show();
            } else {
                $('#accountFields').hide();
            }
        });

        // Handle quick create parent form submission
        $('#quickCreateParentForm').on('submit', function (e) {
            e.preventDefault();

            // Clear previous errors
            $('#parentFormErrors').hide();
            $('#errorList').empty();

            const createUser = $('#createUserToggle').is(':checked') ? 1 : 0;

            const formData = {
                _token: $('input[name="_token"]').val(),
                Name_Father: $('#parentNameFather').val(),
                Name_Mother: $('#parentNameMother').val(),
                National_ID_Father: $('#parentNationalId').val(),
                Phone_Father: $('#parentPhoneFather').val(),
                Phone_Mother: $('#parentPhoneMother').val(),
                Job_Father: $('#parentJobFather').val(),
                Job_Mother: $('#parentJobMother').val(),
                Address_Father: $('#parentAddressFather').val(),
                create_user: createUser,
                email: createUser ? $('#parentEmail').val() : null,
                password: createUser ? $('#parentPassword').val() : null
            };

            $.ajax({
                url: '{{ route("parents.quick-create") }}',
                type: 'POST',
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    if (response.success) {
                        // Close the modal
                        $('#quickCreateParentModal').modal('hide');

                        // Add new parent to dropdown and select it
                        const parentDropdown = $('#parent_id');
                        const newOption = $('<option></option>')
                            .attr('value', response.parent.id)
                            .text(response.parent.name);

                        parentDropdown.append(newOption);
                        parentDropdown.val(response.parent.id).trigger('change');

                        // Show success message
                        toastr.success('Parent created successfully!');

                        // Clear form
                        $('#quickCreateParentForm')[0].reset();
                        $('#accountFields').hide();
                    }
                },
                error: function (response) {
                    if (response.status === 422) {
                        // Validation errors
                        const errors = response.responseJSON.errors;
                        const errorList = $('#errorList');

                        $.each(errors, function (key, messages) {
                            $.each(messages, function (index, message) {
                                errorList.append('<li>' + message + '</li>');
                            });
                        });

                        $('#parentFormErrors').show();
                    } else {
                        // Server error
                        toastr.error('Failed to create parent. Please try again.');
                    }
                }
            });
        });

        // Reset form when modal is closed
        $('#quickCreateParentModal').on('hidden.bs.modal', function () {
            $('#quickCreateParentForm')[0].reset();
            $('#parentFormErrors').hide();
            $('#errorList').empty();
            $('#accountFields').hide();
        });
    });
</script>
@endsection
