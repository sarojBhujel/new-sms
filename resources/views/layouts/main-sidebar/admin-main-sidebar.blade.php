<div class="scrollbar side-menu-bg" style="overflow: scroll">
    <ul class="nav navbar-nav side-menu" id="sidebarnav">
        <!-- menu item Dashboard-->
        <li>
            <a href="{{ url('/dashboard') }}">
                <div class="pull-left"><i class="ti-home"></i><span class="right-nav-text">{{trans('main_trans.Dashboard')}}</span>
                </div>
                <div class="clearfix"></div>
            </a>
        </li>
        <!-- menu title -->
        <li class="mt-10 mb-10 text-muted pl-4 font-medium menu-title">{{trans('main_trans.Programname')}} </li>

        <!-- Grades-->
        <li>
            <a href="javascript:void(0);" data-toggle="collapse" data-target="#Grades-menu">
                <div class="pull-left"><i class="fas fa-school"></i><span
                        class="right-nav-text">{{trans('main_trans.Grades')}}</span></div>
                <div class="pull-right"><i class="ti-plus"></i></div>
                <div class="clearfix"></div>
            </a>
            <ul id="Grades-menu" class="collapse" data-parent="#sidebarnav">
                <li><a href="{{route('Grades.index')}}">{{trans('main_trans.Grades_list')}}</a></li>
                <li><a href="{{route('Faculties.index')}}">Faculties List</a></li>

            </ul>
        </li>
        <!-- classes-->
        <li>
            <a href="javascript:void(0);" data-toggle="collapse" data-target="#classes-menu">
                <div class="pull-left"><i class="fa fa-building"></i><span
                        class="right-nav-text">{{trans('main_trans.classes')}}</span></div>
                <div class="pull-right"><i class="ti-plus"></i></div>
                <div class="clearfix"></div>
            </a>
            <ul id="classes-menu" class="collapse" data-parent="#sidebarnav">
                <li><a href="{{route('Classrooms.index')}}">{{trans('main_trans.List_classes')}}</a></li>
            </ul>
        </li>


        <!-- sections-->
        <li>
            <a href="javascript:void(0);" data-toggle="collapse" data-target="#sections-menu">
                <div class="pull-left"><i class="fas fa-chalkboard"></i></i><span
                        class="right-nav-text">{{trans('main_trans.sections')}}</span></div>
                <div class="pull-right"><i class="ti-plus"></i></div>
                <div class="clearfix"></div>
            </a>
            <ul id="sections-menu" class="collapse" data-parent="#sidebarnav">
                <li><a href="{{route('Sections.index')}}">{{trans('main_trans.List_sections')}}</a></li>
            </ul>
        </li>


        <!-- students-->
        <li>
            <a href="javascript:void(0);" data-toggle="collapse" data-target="#students-menu"><i class="fas fa-user-graduate"></i>{{trans('main_trans.students')}}<div class="pull-right"><i class="ti-plus"></i></div><div class="clearfix"></div></a>
            <ul id="students-menu" class="collapse">
                <li>
                    <a href="javascript:void(0);" data-toggle="collapse" data-target="#Student_information">{{trans('main_trans.information_student')}}<div class="pull-right"><i class="ti-plus"></i></div><div class="clearfix"></div></a>
                    <ul id="Student_information" class="collapse">
                        <li> <a href="{{route('Students.create')}}">Add Student</a></li>
                        <li> <a href="{{route('Students.index')}}">Student List</a></li>
                    </ul>
                </li>

                <li>
                    <a href="javascript:void(0);" data-toggle="collapse" data-target="#Students_upgrade">{{trans('main_trans.Students_Promotions')}}<div class="pull-right"><i class="ti-plus"></i></div><div class="clearfix"></div></a>
                    <ul id="Students_upgrade" class="collapse">
                        <li> <a href="{{route('Promotion.index')}}">Add Promotion</a></li>
                        <li> <a href="{{route('Promotion.create')}}">list Promotions</a> </li>
                    </ul>
                </li>

                <li>
                    <a href="javascript:void(0);" data-toggle="collapse" data-target="#Graduate students">{{trans('main_trans.Graduate_students')}}<div class="pull-right"><i class="ti-plus"></i></div><div class="clearfix"></div></a>
                    <ul id="Graduate students" class="collapse">
                        <li> <a href="{{route('Graduated.create')}}">Graduate</a> </li>
                        <li> <a href="{{route('Graduated.index')}}">list</a> </li>
                    </ul>
                </li>
            </ul>
        </li>



        <!-- Teachers-->
        <li>
            <a href="javascript:void(0);" data-toggle="collapse" data-target="#Teachers-menu">
                <div class="pull-left"><i class="fas fa-chalkboard-teacher"></i></i><span
                        class="right-nav-text">{{trans('main_trans.Teachers')}}</span></div>
                <div class="pull-right"><i class="ti-plus"></i></div>
                <div class="clearfix"></div>
            </a>
            <ul id="Teachers-menu" class="collapse" data-parent="#sidebarnav">
                <li> <a href="{{route('Teachers.index')}}">{{trans('main_trans.List_Teachers')}}</a> </li>
            </ul>
        </li>


        <!-- Parents-->
        <li>
            <a href="javascript:void(0);" data-toggle="collapse" data-target="#Parents-menu">
                <div class="pull-left"><i class="fas fa-user-tie"></i><span
                        class="right-nav-text">{{trans('main_trans.Parents')}}</span></div>
                <div class="pull-right"><i class="ti-plus"></i></div>
                <div class="clearfix"></div>
            </a>
            <ul id="Parents-menu" class="collapse" data-parent="#sidebarnav">
                <li> <a href="{{url('add_parent')}}">{{trans('main_trans.List_Parents')}}</a> </li>
                <li> <a href="{{ route('parents.import.form') }}">Import Parents</a> </li>
            </ul>
        </li>

        <!-- Accounts-->
        <li>
            <a href="javascript:void(0);" data-toggle="collapse" data-target="#Accounts-menu">
                <div class="pull-left"><i class="fas fa-money-bill-wave-alt"></i><span
                        class="right-nav-text">{{trans('main_trans.Accounts')}}</span></div>
                <div class="pull-right"><i class="ti-plus"></i></div>
                <div class="clearfix"></div>
            </a>
            <ul id="Accounts-menu" class="collapse" data-parent="#sidebarnav">
                <li> <a href="{{route('fee-names.index')}}">School Fees</a> </li>
                <li> <a href="{{route('Fees_Invoices.index')}}">Invoices</a> </li>
                <li> <a href="{{route('receipt_students.index')}}">Receipts</a> </li>
                <li> <a href="{{route('ProcessingFee.index')}}">Processing Fees</a> </li>
                <li> <a href="{{route('Payment_students.index')}}">Payment Vouchers</a> </li>
            </ul>
        </li>

        <!-- Attendance-->
        <li>
            <a href="javascript:void(0);" data-toggle="collapse" data-target="#Attendance-icon">
                <div class="pull-left"><i class="fas fa-calendar-alt"></i><span class="right-nav-text">{{trans('main_trans.Attendance')}}</span></div>
                <div class="pull-right"><i class="ti-plus"></i></div>
                <div class="clearfix"></div>
            </a>
            <ul id="Attendance-icon" class="collapse" data-parent="#sidebarnav">
                <li> <a href="{{route('Attendance.index')}}">Student List</a> </li>
            </ul>
        </li>

        <!-- Subjects-->
        <li>
            <a href="javascript:void(0);" data-toggle="collapse" data-target="#Subjects">
                <div class="pull-left"><i class="fas fa-book-open"></i><span class="right-nav-text">Subjects</span></div>
                <div class="pull-right"><i class="ti-plus"></i></div>
                <div class="clearfix"></div>
            </a>
            <ul id="Subjects" class="collapse" data-parent="#sidebarnav">
                <li> <a href="{{route('subjects.index')}}">Subject List</a> </li>
            </ul>
        </li>

        <!-- Quizzes-->
        <li>
            <a href="javascript:void(0);" data-toggle="collapse" data-target="#Exams-icon">
                <div class="pull-left"><i class="fas fa-book-open"></i><span class="right-nav-text">Exams</span></div>
                <div class="pull-right"><i class="ti-plus"></i></div>
                <div class="clearfix"></div>
            </a>
            <ul id="Exams-icon" class="collapse" data-parent="#sidebarnav">
                <li> <a href="{{route('Quizzes.index')}}">Exam List</a> </li>
                <li> <a href="{{route('Questions.index')}}">Question List</a> </li>
            </ul>
        </li>


        <!-- library-->
        <li>
            <a href="javascript:void(0);" data-toggle="collapse" data-target="#library-icon">
                <div class="pull-left"><i class="fas fa-book"></i><span class="right-nav-text">{{trans('main_trans.library')}}</span></div>
                <div class="pull-right"><i class="ti-plus"></i></div>
                <div class="clearfix"></div>
            </a>
            <ul id="library-icon" class="collapse" data-parent="#sidebarnav">
                <li> <a href="{{route('library.index')}}">Book List</a> </li>
            </ul>
        </li>


        {{-- <!-- Online classes-->
        <li>
            <a href="javascript:void(0);" data-toggle="collapse" data-target="#Onlineclasses-icon">
                <div class="pull-left"><i class="fas fa-video"></i><span class="right-nav-text">{{trans('main_trans.Onlineclasses')}}</span></div>
                <div class="pull-right"><i class="ti-plus"></i></div>
                <div class="clearfix"></div>
            </a>
            <ul id="Onlineclasses-icon" class="collapse" data-parent="#sidebarnav">
                <li> <a href="{{route('online_classes.index')}}">Zoom Online Classes</a> </li>
            </ul>
        </li> --}}


        <!-- Settings-->
        <li>
            <a href="{{route('settings.index')}}"><i class="fas fa-cogs"></i><span class="right-nav-text">{{trans('main_trans.Settings')}} </span></a>
        </li>
        <li>
            <a href="{{route('fiscal-years.index')}}"><i class="fas fa-calender"></i><span class="right-nav-text">Fiscal Years</span></a>
        </li>



        <!-- Users-->
        <li>
            <a href="javascript:void(0);" data-toggle="collapse" data-target="#Users-icon">
                <div class="pull-left"><i class="fas fa-users"></i><span class="right-nav-text">{{trans('main_trans.Users')}}</span></div>
                <div class="pull-right"><i class="ti-plus"></i></div>
                <div class="clearfix"></div>
            </a>
            <ul id="Users-icon" class="collapse" data-parent="#sidebarnav">
                <li> <a href="fontawesome-icon.html">font Awesome</a> </li>
                <li> <a href="themify-icons.html">Themify icons</a> </li>
                <li> <a href="weather-icon.html">Weather icons</a> </li>
            </ul>
        </li>

    </ul>
</div>
