@extends('layouts.master')

@section('css')
@endsection

@section('breadcrumb')
<div class="col-sm-6">
    <h4 class="page-title text-left">Teams</h4>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
        <li class="breadcrumb-item"><a href="javascript:void(0);">Teams</a></li>
    </ol>
</div>
@endsection
@section('button')
{{-- <a href="" data-toggle="modal" class="btn btn-primary btn-sm btn-flat"><i class="mdi mdi-plus mr-2"></i>Add New Roles</a> --}}
@endsection
@section('content')
@include('includes.flash')


<div class="row">

    <div class="col-md-12 text-right">
        <a href="" data-toggle="modal" data-target="#add_new_team" class="btn btn-primary btn-sm btn-flat mb-3"><i class="mdi mdi-plus mr-2"></i>Add New Team</a>

    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">

                    <thead class="bg-primary text-white">
                        <tr>
                            <th data-priority="1">#</th>
                            <th data-priority="2">Team Name</th>
                            <!-- <th data-priority="4">Slug</th> -->
                            <th data-priority="5">Manager Name</th>
                            <th data-priority="7">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($teams as $team)
                        <tr>
                            <td>{{ $team->id }}</td>
                            <td>{{ $team->name }}</td>
                            <!-- <td>{{ $team->slug }}</td> -->
                            <td>
                                <div class="row">
                                    <div style="background: #0c2f52; left: 3px;" class="col-md-6">
                                        <h6 style="color: white;">Team Members</h6>
                                    </div>
                                </div>
                                <div class="row">

                                    <div style="background: #1c4e80; left: 3px; color:white" class="col-md-6">
                                        <ol style="padding-top: 10px;">
                                            @foreach ($team->users as $user)
                                            @php 
                                            $emp_id = get_emp_id($user->id);
                                            $emp = employee_details($emp_id);
                                            @endphp
                                            @if($emp->status == 1)
                                            <li>
                                                {{ $user->name }}
                                                @if ($user->pivot->is_manager)
                                                ({{getLastRole($user->id)->name}})
                                                @else
                                                (Team Member)
                                                @endif

                                            </li>
                                            @endif
                                            @endforeach
                                        </ol>
                                    </div>
                                    <div class="col md-3">
                                        <select onchange="assignTeam(this.value, '{{$team->id}}')" class="form-control" name="manager_id" required>
                                            <option value="" selected>- Select Person -</option>
                                            @foreach ($team->activeMembers as $user)
                                            @foreach ($user->roles as $role)
                                            @if ($role->slug !='employee')
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                            @endif
                                            @endforeach
                                            @endforeach
                                        </select>
                                    </div>
                                </div>


                            </td>
                            <td>
                                {{-- <a href="#" data-toggle="modal" data-target="#ajax_update_modal"
                                            class="btn btn-success  btn-sm edit btn-flat"
                                            onclick="ajaxRenderModal('{{ $user->id }}', '/get_data/{{ $user->id }}', 'GET', 'update_user')"><i class='fa fa-edit'></i></a> --}}
                                {{-- <a href="#delete{{$user->id}}" data-toggle="modal" class="btn btn-danger btn-sm delete btn-flat"><i class='fa fa-trash'></i> Delete</a> --}}
                                <a href="#team-info-content" onclick="renderteamcard('show.team','{{$team->id}}')" class="btn btn-info btn-sm btn-flat"><i class='fa fa-eye'></i></a>
                                {{-- <a href="#" data-toggle="modal" data-target="#ajax_update_modal"
                                            data-id="{{ $user->id }}"
                                class="btn btn-danger btn-sm dactive_user btn-flat"><i class='fa fa-minus-circle'></i></a> --}}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card team-info-content" id="team-info-content">

        </div>
    </div>
</div>
@include('includes.ajax_modal')
@include('includes.add_new_team')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">

                    <thead class="bg-primary text-white">
                        <tr>
                            <th data-priority="1">#</th>
                            <th data-priority="2">Name</th>
                            <th data-priority="4">Email</th>
                            <th data-priority="5">Role</th>
                            <th data-priority="7">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @php
                                $role = $user->roles->last();
                                @endphp
                                {{-- @foreach ($user->roles as $role) --}}
                                {{ $role->slug }}
                                {{-- @endforeach --}}
                            </td>
                            <td>
                                <a href="#" data-toggle="modal" data-target="#ajax_update_modal" class="btn btn-success  btn-sm edit btn-flat" onclick="ajaxRenderModal('{{ $user->id }}', '/get_data/{{ $user->id }}', 'GET', 'update_user')"><i class='fa fa-edit'></i></a>
                                {{-- <a href="#delete{{$user->id}}" data-toggle="modal" class="btn btn-danger btn-sm delete btn-flat"><i class='fa fa-trash'></i> Delete</a> --}}
                                {{-- <a href="#" onclick="ajaxRenderTab('{{ $user->id }}', '/get_data/{{ $user->id }}', 'GET', 'team_details')" class="btn btn-info btn-sm btn-flat"><i class='fa fa-eye'></i></a> --}}
                                {{-- <a href="#" data-toggle="modal" data-target="#ajax_update_modal" data-id="{{ $user->id }}" class="btn btn-danger btn-sm dactive_user btn-flat"><i class='fa fa-minus-circle'></i></a> --}}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection
@section('script')
<script>
    function assignTeam(_id, team_id) {
        const csrfTokenMeta = document.querySelector('meta[name="csrf-token"]');
        const csrfToken = csrfTokenMeta.getAttribute('content');
        $.ajax({
            url: "/update.manager",
            type: "Post",
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            data: {
                'team_id': team_id,
                'user_id': _id
            },
            success: function(resp) {
                if (resp) {
                    console.log(resp);
                    setTimeout(function() {
                        location.reload();
                    }, 3000);
                }
            }

        })
    }

    function renderteamcard(url, team_id) {
        console.log(url)
        $.ajax({
            url: `${url}/${team_id}`,
            type: "Get",
            success: function(resp) {
                if (resp) {
                    $('.team-info-content').html(resp)
                }
            }

        })
    }
</script>
@endsection