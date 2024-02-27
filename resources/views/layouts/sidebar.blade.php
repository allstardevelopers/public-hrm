      <!-- ========== Left Sidebar Start ========== -->
      <div class="left side-menu">
          <div class="slimscroll-menu" id="remove-scroll">
              <!--- Sidemenu -->
              <div id="sidebar-menu">

                  <!-- Left Menu Start -->
                  <ul class="metismenu" id="side-menu">
                      <li class="menu-title">Main </li>
                      <li class="">
                          <a href="{{route('admin')}}" class="waves-effect {{ request()->is("admin") || request()->is("admin/*") ? "mm active" : "" }}">
                              <i class="ti-home"></i><span class="badge badge-danger badge-pill float-right">{{countCheckdIn()}}</span> <span> Dashboard </span>
                          </a>
                      </li>
                      <li>
                          <a href="javascript:void(0);" class="waves-effect"><i class="ti-user"></i><span> Employees <span class="float-right menu-arrow"><i class="mdi mdi-chevron-right"></i></span> </span></a>
                          <ul class="submenu">
                              <li>
                                  <a href="/employees" class="waves-effect {{ request()->is("employees") || request()->is("/employees/*") ? "mm active" : "" }}"><i class="dripicons-view-apps"></i><span>Employees List</span></a>
                              </li>
                              <li>
                                  <a href="/users" class="waves-effect {{ request()->is("users") || request()->is("//users/*") ? "mm active" : "" }}"><i class="ti-user"></i><span>Teams</span></a>
                              </li>
                              <li>
                                  <a href="/inactive/" class="waves-effect {{ request()->is("employees.inactive") || request()->is("/employees/*") ? "mm active" : "" }}"><i class="dripicons-view-apps"></i><span>Inactive Employee</span></a>
                              </li>
                          </ul>
                      </li>

                      <li class="menu-title">Management</li>

                      <li class="">
                          <a href="/schedule" class="waves-effect {{ request()->is("schedule") || request()->is("schedule/*") ? "mm active" : "" }}">
                              <i class="ti-time"></i> <span> Schedule </span>
                          </a>
                      </li>
                      <li class="">
                          <a href="/attendance/today" class="waves-effect {{ request()->is("attendance") || request()->is("attendance/*") ? "mm active" : "" }}">
                              <i class="ti-calendar"></i> <span> Attendance Logs </span>
                          </a>
                      </li>
                      <li class="">
                          <a href="/check" class="waves-effect {{ request()->is("check") || request()->is("check/*") ? "mm active" : "" }}">
                              <i class="dripicons-to-do"></i> <span> Attendance Sheet </span>
                          </a>
                      </li>
                      {{-- <li class="">
                                <a href="/sheet-report" class="waves-effect {{ request()->is("sheet-report") || request()->is("sheet-report/*") ? "mm active" : "" }}">
                      <i class="dripicons-to-do"></i> <span> Sheet Report </span>
                      </a>
                      </li> --}}


                      <li class="">
                          <a href="/latetime" class="waves-effect {{ request()->is("latetime") || request()->is("latetime/*") ? "mm active" : "" }}">
                              <i class="dripicons-warning"></i><span> Late Time </span>
                          </a>
                      </li>
                      <li class="">
                          <a href="/clockrequest" class="waves-effect {{ request()->is("clockrequest") || request()->is("latetime/*") ? "mm active" : "" }}">
                              <i class="dripicons-archive"></i><span> <span class="badge badge-danger badge-pill float-right">{{count_clockout_requests(date('Y-m-d'))}}</span> Requests</span>
                          </a>
                      </li>
                      <li class="">
                          <a href="/leave" class="waves-effect {{ request()->is("leave") || request()->is("leave/*") ? "mm active" : "" }}">
                              <i class="dripicons-backspace"></i> <span> <span class="badge badge-danger badge-pill float-right">{{count_pendingLeave_requests()}}</span> Leave </span>
                          </a>
                      </li>
                      <li class="">
                          <a href="/upcoming-events" class="waves-effect {{ request()->is("upcoming-events") || request()->is("upcoming-events/*") ? "mm active" : "" }}">
                              <i class="dripicons-backspace"></i> <span> Events </span>
                          </a>
                      </li>
                      {{-- <li class="">
                                <a href="/clocks" class="waves-effect {{ request()->is("clocks") || request()->is("clocks/*") ? "mm active" : "" }}">
                      <i class="dripicons-alarm"></i> <span> Clock Out </span>
                      </a>
                      </li> --}}
                      {{-- <li class="menu-title">Tools</li>
                            <li class="">
                                <a href="{{ route("finger_device.index") }}" class="waves-effect {{ request()->is("finger_device") || request()->is("finger_device/*") ? "mm active" : "" }}">
                      <i class="fas fa-fingerprint"></i> <span> Biometric Device </span>
                      </a>
                      </li> --}}
                  </ul>

              </div>
              <!-- Sidebar -->
              <div class="clearfix"></div>

          </div>
          <!-- Sidebar -left -->

      </div>
      <!-- Left Sidebar End -->