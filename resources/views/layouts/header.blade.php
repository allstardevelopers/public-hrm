   <!-- Top Bar Start -->
   <div class="topbar">

       <!-- LOGO -->
       <div class="topbar-left">
           <a href="/" class="logo">
               <span>
                   <img src="{{ URL::asset('assets/images/logo.png') }}" width="100%" alt="favicon">
               </span>
               <i>
                <img src="{{ URL::asset('assets/images/ast-favicon.ico') }}" width="100%" alt="favicon">
               </i>
           </a>
       </div>

       <nav class="navbar-custom">
           <ul class="navbar-right d-flex list-inline float-right mb-0">
               {{-- <li class="dropdown notification-list d-none d-md-block">
            <form role="search" class="app-search">
                <div class="form-group mb-0">
                    <input type="text" class="form-control" placeholder="Search..">
                    <button type="submit"><i class="fa fa-search"></i></button>
                </div>
            </form>
        </li> --}}

               <!-- language-->
               {{-- <li class="dropdown notification-list d-none d-md-block">
            <a class="nav-link dropdown-toggle arrow-none waves-effect" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                <img src="assets/images/flags/us_flag.jpg" class="mr-2" height="12" alt=""/> English <span class="mdi mdi-chevron-down"></span>
            </a>
            <div class="dropdown-menu dropdown-menu-right language-switch">
                <a class="dropdown-item" href="#"><img src="assets/images/flags/germany_flag.jpg" alt="" height="16" /><span> German </span></a>
                <a class="dropdown-item" href="#"><img src="assets/images/flags/italy_flag.jpg" alt="" height="16" /><span> Italian </span></a>
                <a class="dropdown-item" href="#"><img src="assets/images/flags/french_flag.jpg" alt="" height="16" /><span> French </span></a>
                <a class="dropdown-item" href="#"><img src="assets/images/flags/spain_flag.jpg" alt="" height="16" /><span> Spanish </span></a>
                <a class="dropdown-item" href="#"><img src="assets/images/flags/russia_flag.jpg" alt="" height="16" /><span> Russian </span></a>
            </div>
        </li> --}}
               <!-- full screen -->
               <li class="dropdown notification-list d-none d-md-block">
                   <a class="nav-link waves-effect" href="#" id="btn-fullscreen">
                       <i class="mdi mdi-fullscreen noti-icon"></i>
                   </a>
               </li>


               <!-- notification -->
               {{-- <li class="dropdown notification-list">
            <a class="nav-link dropdown-toggle arrow-none waves-effect" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                <i class="mdi mdi-bell-outline noti-icon"></i>
                <span class="badge badge-pill badge-danger noti-icon-badge">3</span>
            </a>
            <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg">
                <!-- item-->
                <h6 class="dropdown-item-text">
                        Notifications (258)
                    </h6>
                <div class="slimscroll notification-item-list">
                    <!-- item-->
                    <a href="javascript:void(0);" class="dropdown-item notify-item active">
                        <div class="notify-icon bg-success"><i class="mdi mdi-cart-outline"></i></div>
                        <p class="notify-details">Your order is placed<span class="text-muted">Dummy text of the printing and typesetting industry.</span></p>
                    </a>
                    <!-- item-->
                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                        <div class="notify-icon bg-warning"><i class="mdi mdi-message-text-outline"></i></div>
                        <p class="notify-details">New Message received<span class="text-muted">You have 87 unread messages</span></p>
                    </a>
                    <!-- item-->
                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                        <div class="notify-icon bg-info"><i class="mdi mdi-glass-cocktail"></i></div>
                        <p class="notify-details">Your item is shipped<span class="text-muted">It is a long established fact that a reader will</span></p>
                    </a>
                    <!-- item-->
                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                        <div class="notify-icon bg-primary"><i class="mdi mdi-cart-outline"></i></div>
                        <p class="notify-details">Your order is placed<span class="text-muted">Dummy text of the printing and typesetting industry.</span></p>
                    </a>
                    <!-- item-->
                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                        <div class="notify-icon bg-danger"><i class="mdi mdi-message-text-outline"></i></div>
                        <p class="notify-details">New Message received<span class="text-muted">You have 87 unread messages</span></p>
                    </a>
                </div>
                <!-- All-->
                <a href="javascript:void(0);" class="dropdown-item text-center text-primary">
                        View all <i class="fi-arrow-right"></i>
                    </a>
            </div>
        </li> --}}
               <li class="dropdown notification-list">
                   <div class="dropdown notification-list nav-pro-img">
                       @if($slug=='admin')
                       <a class="dropdown-toggle nav-link arrow-none waves-effect nav-user" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                           <img src="{{ asset('assets/images/blue-background.PNG') }}" alt="user" class="rounded-circle mr-1"><span class="d-none d-md-inline-block ml-1">{{auth()->user()->name}} <i class="mdi mdi-chevron-down"></i> </span>
                       </a>
                       @elseif($slug=='employee')
                       @php
                       $emp_id = get_emp_id(auth()->user()->id);
                       $employee = employee_details($emp_id);
                       $profilePic = $employee->profile_pic;
                       @endphp
                       @if($profilePic !=null)
                       <a class="dropdown-toggle nav-link arrow-none waves-effect nav-user" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                           <img src="{{ asset('storage/assets/profile_pics/' . $profilePic) }}" alt="user" class="rounded-circle mr-1"><span class="d-none d-md-inline-block ml-1">{{auth()->user()->name}} <i class="mdi mdi-chevron-down"></i> </span>
                       </a>
                       @else
                       <a class="dropdown-toggle nav-link arrow-none waves-effect nav-user" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                           <img src="{{ asset('assets/images/blue-background.PNG') }}" alt="user" class="rounded-circle mr-1"><span class="d-none d-md-inline-block ml-1">{{auth()->user()->name}} <i class="mdi mdi-chevron-down"></i> </span>
                       </a>
                       @endif
                       @endif

                       <div class="dropdown-menu dropdown-menu-right profile-dropdown ">
                           <!-- item-->
                           <a class="dropdown-item" href="#" data-toggle="modal" data-target="#userProfile"><i class="mdi mdi-account-circle m-r-5"></i> Profile</a>
                           @if($slug=='admin')
                           <a class="dropdown-item d-block" href="{{ route('setting') }}"><span class="badge badge-success float-right">11</span><i class="mdi mdi-settings m-r-5"></i> Settings</a>
                           @elseif($slug=='employee')
                           @php
                           $emp_id = get_emp_id(auth()->user()->id);
                           $employee = employee_details($emp_id);
                           $probation_comp = strtotime('+' . $employee->probation . 'months', strtotime($employee->joining_date));
                           $today = strtotime(date('d-m-Y'));
                           @endphp
                           <a class="dropdown-item d-block" href="{{ route('policies', 'policy_file') }}"><i class="mdi mdi-book-edit m-r-5"></i> Company Polices</a>
                           @if($probation_comp<=$today) <a class="dropdown-item d-block" href="{{ route('policies', 'leave_policy_file') }}"><i class="mdi mdi-book-edit m-r-5"></i> Leave Polices</a>
                               @endif
                               @endif
                               <a class="dropdown-item" href="#"><i class="mdi mdi-lock-open-outline m-r-5"></i> Lock screen</a>
                               <div class="dropdown-divider"></div>
                               <a class="dropdown-item text-danger" href="{{ route('logout') }}" onclick="event.preventDefault();
                    document.getElementById('logout-form').submit();"><i class="mdi mdi-power text-danger"></i> {{ __('Logout') }}</a>
                               <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                   @csrf
                               </form>
                       </div>
                   </div>
               </li>
           </ul>
           <ul class="list-inline menu-left mb-0">
               <li class="float-left">
                   <button class="button-menu-mobile open-left waves-effect">
                       <i class="mdi mdi-menu"></i>
                   </button>
               </li>
               {{-- <li class="d-none d-sm-block">
            <div class="dropdown pt-3 d-inline-block">
                <a class="btn btn-light dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Create
                    </a>

                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                    <a class="dropdown-item" href="#">Action</a>
                    <a class="dropdown-item" href="#">Another action</a>
                    <a class="dropdown-item" href="#">Something else here</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#">Separated link</a>
                </div>
            </div>
        </li> --}}
           </ul>

       </nav>

   </div>
   <!-- Top Bar End -->