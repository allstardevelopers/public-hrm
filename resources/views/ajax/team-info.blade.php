<style>
    /* .user-details {
        background-color: #ffffff;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.1);
        display: flex;
        align-items: center;
        margin: 10px;
    }

    .checkbox {
        margin-right: 10px;
    }

    .full-name {
        font-size: 18px;
        font-weight: bold;
    }

    .position {
        color: #ec4561;
        padding-left: 5px;
        font-size: 10px;
    } */
    .user-details {
        background-color: #ffffff;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        /* Center content vertically and horizontally */
        margin: 10px;
        position: relative;
        overflow: hidden;
        height: 100px;
        /* Initial height */
        transition: transform 0.3s, box-shadow 0.3s, height 0.3s;
        cursor: pointer;
    }

    .user-details:hover {
        height: 112px;
        transform: scale(1.05);
        box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.2);
    }

    .checkbox {
        margin-right: 10px;
    }

    .full-name {
        font-size: 18px;
        font-weight: bold;
    }

    .position {
        color: #ec4561;
        padding-left: 5px;
        font-size: 10px;
    }

    .user-details:before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(to right, #9e9add, #ffffff);
        opacity: 0.9;
        z-index: -1;
        transition: opacity 0.3s;
    }

    .user-details:hover:before {
        opacity: 1;
    }
</style>
<div class="card-body">
    <h4 class="card-title">{{$team_name}}</h4>
    <hr>
    <!-- Tab panes -->
    <div class="tab-content">
        <div class="tab-pane active p-3" id="home1" role="tabpanel">
            <form method="POST" action="{{ route('update.team') }}">
                @csrf
                <input type="hidden" name='team_id' value="{{ $team->id }}">
                <table>
                    <thead>

                    </thead>
                    <tbody>
                    </tbody>

                    <div class="row">
                        <div style="margin: 20px; background:#1c4e80; color:#ffffff" class="col-md-12">
                            <h4>Team Members</h4>
                        </div>
                        @foreach ($team->users as $user)
                        @php
                        $emp_id = get_emp_id($user->id);
                        $emp = employee_details($emp_id);
                        if($emp->status == 1){
                        @endphp
                        @php

                        $profile_pic_url = URL::asset('assets/images/profile1.png');

                        $position='';

                        @endphp
                        @if($emp)
                        @php
                        if($emp->profile_pic!=''){
                        $profile_pic_url = URL::asset('storage/assets/profile_pics/' . $emp->profile_pic);
                        }
                        $position = $emp->position;
                        @endphp
                        @endif
                        <div class="col-md-4">
                            <div class="user-details card">
                                <div class="card-content">
                                    <img src="{{ URL::asset($profile_pic_url) }}" class="mx-2 rounded-circle text-center" style="width: 60px; height: 60px; border-radius: 50%; object-fit: cover;" alt="">
                                    <span class="full-name">{{ $user->name }}
                                        {{ $user->pivot->is_manager ? '(Manager)' : '(Member)' }}</span>
                                </div>
                            </div>
                        </div>

                        @php
                        }
                        @endphp
                        @endforeach

                    </div>
                    <div class="row">
                        <div style="margin: 20px; background:#1c4e80; color:#ffffff" class="col-md-12">
                            <h4>Other Users</h4>
                        </div>
                        @foreach ($users as $user)
                        @unless ($team->users->contains('id', $user->id))
                        @php
                        $emp_id = get_emp_id($user->id);
                        $emp = employee_details($emp_id);
                        if($emp->status == 1){
                        @endphp

                        @php
                        $profile_pic_url = URL::asset('assets/images/profile1.png');
                        $position='';

                        @endphp
                        @if($emp)
                        @php
                        if($emp->profile_pic!=''){
                        $profile_pic_url = URL::asset('storage/assets/profile_pics/' . $emp->profile_pic);
                        }
                        $position = $emp->position;
                        @endphp
                        @endif
                        <div class="card col-md-4">
                            <div class="user-details">

                                <input id="check_box" name="users[]" type="checkbox" value="{{ $user->id }}" class="checkbox">
                                <img src="{{ URL::asset($profile_pic_url) }}" class="mx-2 rounded-circle text-center" style="width: 60px; height: 60px; border-radius: 50%; object-fit: cover;" alt="">
                                <span class="full-name">{{ $user->name }}</span>
                                <span class="position">({{$position}})</span>
                            </div>
                        </div>
                        @php
                        }
                        @endphp
                        @endunless
                        @endforeach

                    </div>
                </table>


                <div class="form-group">
                    <div class="float-right">
                        <button type="submit" class="btn btn-primary waves-effect waves-light">
                            Update
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>