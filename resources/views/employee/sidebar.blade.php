  <!-- ========== Left Sidebar Start ========== -->
  @php 
       $emp_id = get_emp_id(auth()->user()->id);
  @endphp
  <div class="left side-menu">
    <div class="slimscroll-menu" id="remove-scroll">
      <!--- Sidemenu -->
      <div id="sidebar-menu">

        <!-- Left Menu Start -->
        <ul class="metismenu" id="side-menu">
          <li class="menu-title">Main</li>
          <li class="">
            <a href="{{route('dashboard')}}" class="waves-effect {{ request()->is("dashboard") || request()->is("admin/*") ? "mm active" : "" }}">
              <i class="ti-home"></i><span class="badge badge-primary badge-pill float-right">2</span> <span> Dashboard </span>
            </a>
          </li>
          <li class="">
            <a href="/mark-attendance" class="waves-effect {{ request()->is("mark-attendance") || request()->is("latetime/*") ? "mm active" : "" }}">
              <i class="fa fa-check"></i><span> Mark Break </span>
            </a>
          </li>
          <li class="">
            <a href="/empattendence" class="waves-effect {{ request()->is("empattendence") || request()->is("attendance/*") ? "mm active" : "" }}">
              <i class="ti-calendar"></i> <span> Attendance Logs </span>
            </a>
          </li>
          <li class="">
            <a href="/employeecheck" class="waves-effect {{ request()->is("employeecheck") || request()->is("check/*") ? "mm active" : "" }}">
              <i class="dripicons-to-do"></i> <span> Attendance Sheet </span>
            </a>
          </li>
          <li class="">
            <a href="/employee/report/{{encrypt(get_emp_id(auth()->user()->id))}}/{{Date('m')}}/{{Date('Y')}}/" class="waves-effect {{ request()->is("attendence-report") || request()->is("sheet-report/*") ? "mm active" : "" }}">
              <i class="dripicons-to-do"></i> <span> Attendence Report </span>
            </a>
          </li>
          <li class="">
            <a href="/emplatetime" class="waves-effect {{ request()->is("emplatetime") || request()->is("latetime/*") ? "mm active" : "" }}">
              <i class="dripicons-warning"></i><span> Late Time </span>
            </a>
          </li>
          <li class="">
            <a href="/leave/request" class="waves-effect {{ request()->is("leave/request") || request()->is("leave/*") ? "mm active" : "" }}">
                <i class="dripicons-backspace"></i> <span> Leave </span>
            </a>
          </li>
        </ul>
        </li>
        </ul>
      </div>
    </div>
    <!-- Sidebar -left -->

  </div>
  <!-- Left Sidebar End -->