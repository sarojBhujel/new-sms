@extends('layouts.master')
@section('css')
    @toastr_css
@section('title')
    {{trans('Students_trans.Student_Edit')}}
@stop
@endsection
@section('page-header')
<!-- breadcrumb -->
@section('PageTitle')
    {{trans('Students_trans.Student_Edit')}}
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

                    <form action="{{route('Students.update','test')}}" method="post" autocomplete="off">
                        @method('PUT')
                        @csrf
                        <input type="hidden" name="id" value="{{ $Students->id }}">
                        <input type="hidden" name="fiscal_year_id" value="{{ optional($activeFiscalYear)->id }}">
                    <h6 style="font-family: 'Cairo', sans-serif;color: blue">{{trans('Students_trans.personal_information')}}</h6><br>
                        <div class="row">

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Student Name : <span class="text-danger">*</span></label>
                                    <input value="{{$Students->name}}" class="form-control" name="name" type="text" >
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            @php $hasLoginCredentials = !empty($Students->password); @endphp
                            <div class="col-md-12">
                                <div class="form-group custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="create_login_credentials" name="create_login_credentials" value="1" {{ $hasLoginCredentials ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="create_login_credentials">Create Login Credentials</label>
                                </div>
                            </div>

                            <div id="credentials-section" style="width:100%;{{ $hasLoginCredentials ? '' : 'display:none;' }}">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{trans('Students_trans.email')}} : </label>
                                        <input type="email" value="{{ old('email', $Students->email) }}" name="email" class="form-control" >
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{trans('Students_trans.password')}} :</label>
                                        <input type="password" name="password" class="form-control" placeholder="Leave blank to keep the current password">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="gender">{{trans('Students_trans.gender')}} : <span class="text-danger">*</span></label>
                                    <select class="custom-select mr-sm-2" name="gender_id">
                                        <option selected disabled>{{trans('Parent_trans.Choose')}}...</option>
                                        @foreach($Genders as $Gender)
                                            <option value="{{$Gender->id}}" {{$Gender->id == $Students->gender_id ? 'selected' : ""}}>{{ $Gender->Name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="nal_id">{{trans('Students_trans.Nationality')}} : <span class="text-danger">*</span></label>
                                    <select class="custom-select mr-sm-2" name="nationalitie_id">
                                        <option selected disabled>{{trans('Parent_trans.Choose')}}...</option>
                                        @foreach($nationals as $nal)
                                            <option value="{{ $nal->id }}" {{$nal->id == $Students->nationalitie_id ? 'selected' : ""}}>{{ $nal->Name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="bg_id">{{trans('Students_trans.blood_type')}} : </label>
                                    <select class="custom-select mr-sm-2" name="blood_id">
                                        <option selected disabled>{{trans('Parent_trans.Choose')}}...</option>
                                        @foreach($bloods as $bg)
                                            <option value="{{ $bg->id }}" {{$bg->id == $Students->blood_id ? 'selected' : ""}}>{{ $bg->Name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="phone">Phone Number :</label>
                                    <input class="form-control" name="phone" type="text" value="{{ old('phone', $Students->phone) }}">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>{{trans('Students_trans.Date_of_Birth')}}  :</label>
                                    <input class="form-control" type="text" value="{{$Students->Date_Birth}}" id="datepicker-action" name="Date_Birth" data-date-format="yyyy-mm-dd">
                                </div>
                            </div>

                        </div>

                    <h6 style="font-family: 'Cairo', sans-serif;color: blue">{{trans('Students_trans.Student_information')}}</h6><br>
                    <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="Grade_id">{{trans('Students_trans.Grade')}} : <span class="text-danger">*</span></label>
                                    <select class="custom-select mr-sm-2" name="Grade_id">
                                        <option selected disabled>{{trans('Parent_trans.Choose')}}...</option>
                                        @foreach($Grades as $Grade)
                                            <option value="{{ $Grade->id }}" {{$Grade->id == $Students->Grade_id ? 'selected' : ""}}>{{ $Grade->Name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="faculty_id">Faculty :</label>
                                    <select class="custom-select mr-sm-2" name="faculty_id" id="faculty_id">
                                        <option selected disabled>{{trans('Parent_trans.Choose')}}...</option>
                                        @if(optional($studentFiscalDetail)->faculty)
                                            <option value="{{ optional($studentFiscalDetail)->faculty->id }}" selected>{{ optional($studentFiscalDetail)->faculty->faculty_name }}</option>
                                        @endif
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="Classroom_id">{{trans('Students_trans.classrooms')}} : <span class="text-danger">*</span></label>
                                    <select class="custom-select mr-sm-2" name="Classroom_id">
                                        <option value="{{$Students->Classroom_id}}">{{$Students->classroom->Name_Class}}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="section_id">{{trans('Students_trans.section')}} : </label>
                                    <select class="custom-select mr-sm-2" name="section_id">
                                        <option value="" disabled {{ empty($Students->section_id) ? 'selected' : '' }}>{{ trans('Parent_trans.Choose') }}...</option>
                                        @if($Students->section_id)
                                            <option value="{{$Students->section_id}}" selected>{{ optional($Students->section)->Name_Section ?? 'N/A' }}</option>
                                        @endif
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="parent_id">{{trans('Students_trans.parent')}} : <span class="text-danger">*</span></label>
                                    <select class="custom-select mr-sm-2" name="parent_id">
                                        <option selected disabled>{{trans('Parent_trans.Choose')}}...</option>
                                       @foreach($parents as $parent)
                                            <option value="{{ $parent->id }}" {{ $parent->id == $Students->parent_id ? 'selected' : ""}}>{{ $parent->Name_Father }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="admission_no">{{trans('Students_trans.admission_no')}} :</label>
                                <input class="form-control" name="admission_no" type="text" value="{{ old('admission_no', optional($studentFiscalDetail)->admission_no) }}">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="admission_date">{{trans('Students_trans.admission_date')}} :</label>
                                <input class="form-control" name="admission_date" type="text" value="{{ old('admission_date', optional(optional($studentFiscalDetail)->admission_date)->format('Y-m-d')) }}" id="datepicker-admission-date" data-date-format="yyyy-mm-dd">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="roll_no">{{trans('Students_trans.roll_no')}} :</label>
                                <input class="form-control" name="roll_no" type="text" value="{{ old('roll_no', optional($studentFiscalDetail)->roll_no) }}">
                            </div>
                        </div>

                        <div class="col-md-1">
                            <div class="form-group">
                                <label>{{trans('Students_trans.academic_year')}} :</label>
                                <input type="text" class="form-control" value="{{ optional($activeFiscalYear)->name }}" readonly>
                            </div>
                        </div>
                        </div><br>
                    <button class="btn btn-success btn-sm nextBtn btn-lg pull-right" type="submit">{{trans('Students_trans.submit')}}</button>
                </form>

            </div>
        </div>
    </div>
</div>
<!-- row closed -->
@endsection
@section('js')
    @toastr_js
    @toastr_render
    <script>
        function toggleCredentialsSection() {
            var isChecked = $('#create_login_credentials').is(':checked');
            $('#credentials-section').toggle(isChecked);
        }

        $(document).ready(function () {
            toggleCredentialsSection();
            $('#create_login_credentials').on('change', toggleCredentialsSection);

            $('select[name="Grade_id"]').on('change', function () {
                var Grade_id = $(this).val();
                if (Grade_id) {
                    $.ajax({
                        url: "{{ URL::to('Get_classrooms') }}/" + Grade_id,
                        type: "GET",
                        dataType: "json",
                        success: function (data) {
                            $('select[name="Classroom_id"]').empty();
                            $.each(data, function (key, value) {
                                $('select[name="Classroom_id"]').append('<option selected disabled >{{trans('Parent_trans.Choose')}}...</option>');
                                $('select[name="Classroom_id"]').append('<option value="' + key + '">' + value + '</option>');
                            });

                            // load faculties for selected grade
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
                                }
                            });

                        },
                    });
                }

                else {
                    console.log('AJAX load did not work');
                }
            });
        });
    </script>


    <script>
        $(document).ready(function () {
            $('select[name="Classroom_id"]').on('change', function () {
                var Classroom_id = $(this).val();
                if (Classroom_id) {
                    $.ajax({
                        url: "{{ URL::to('Get_Sections') }}/" + Classroom_id,
                        type: "GET",
                        dataType: "json",
                        success: function (data) {
                            $('select[name="section_id"]').empty();
                            $.each(data, function (key, value) {
                                $('select[name="section_id"]').append('<option value="' + key + '">' + value + '</option>');
                            });

                        },
                    });
                }

                else {
                    console.log('AJAX load did not work');
                }
            });
        });
    </script>
@endsection
