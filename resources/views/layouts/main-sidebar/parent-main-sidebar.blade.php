<div class="scrollbar side-menu-bg" style="overflow: scroll">
    <ul class="nav navbar-nav side-menu" id="sidebarnav">
        <!-- menu item Dashboard-->
        <li>
            <a href="{{ route('dashboard.parents') }}">
                <div class="pull-left"><i class="ti-home"></i><span
                        class="right-nav-text">{{trans('main_trans.Dashboard')}}</span>
                </div>
                <div class="clearfix"></div>
            </a>
        </li>
        <!-- menu title -->
        <li class="mt-10 mb-10 text-muted pl-4 font-medium menu-title">{{trans('main_trans.Programname')}} </li>


        <!-- Children -->
        <li>
            <a href="{{route('sons.index')}}"><i class="fas fa-book-open"></i><span
                    class="right-nav-text">Children</span></a>
        </li>

        <!-- Attendance Report -->
        <li>
            <a href="{{route('sons.attendances')}}"><i class="fas fa-book-open"></i><span
                    class="right-nav-text">Attendance Report</span></a>
        </li>

        <!-- Financial Report -->
        <li>
            <a href="{{route('sons.fees')}}"><i class="fas fa-book-open"></i><span
                    class="right-nav-text">Financial Report</span></a>
        </li>


        <!-- Profile -->
        <li>
            <a href="{{route('profile.show.parent')}}"><i class="fas fa-id-card-alt"></i><span
                    class="right-nav-text">Profile</span></a>
        </li>

    </ul>
</div>
