@extends('layouts.master')
@section('css')
<!-- Table css -->
<link href="{{ URL::asset('plugins/RWD-Table-Patterns/dist/css/rwd-table.min.css') }}" rel="stylesheet" type="text/css" media="screen">
@endsection

@section('breadcrumb')
<div class="col-sm-6">
    <h4 class="page-title text-left">Schedules</h4>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
        <li class="breadcrumb-item"><a href="javascript:void(0);">Setting</a></li>


    </ol>
</div>
@endsection
@section('button')
{{-- <a href="#addnew" data-toggle="modal" class="btn btn-primary btn-sm btn-flat"><i class="mdi mdi-plus mr-2"></i>Add</a> --}}
@endsection

@section('content')
@include('includes.flash')

<div class="row">
    <div class="col-6">
        {{-- Card-------- --}}
        <div class="card">

            <div class="card-body">

                <h4 class="card-title">Company Policy File</h4>
                <p class="card-title-desc">Update Policy File.</p>

                <form action="#">
                    <div class="mb-3">
                        <label class="form-label">Chose File</label>
                        <input type="file" class="filestyle" data-buttonname="btn-secondary" id="filestyle-0" tabindex="-1" style="position: absolute; clip: rect(0px, 0px, 0px, 0px);">
                        <div class="bootstrap-filestyle input-group">
                            <div name="filedrag" style="position: absolute; width: 100%; height: 35px; z-index: -1;">
                            </div>
                            <input type="text" class="form-control " id="filestyle-1" placeholder="" disabled="" style="border-top-left-radius: 0.25rem; border-bottom-left-radius: 0.25rem;">
                            <span class="group-span-filestyle input-group-btn" tabindex="0"><label for="filestyle-0" style="margin-bottom: 0;" class="btn btn-secondary chose-btn setting-choose-file-btn"><span class="buttonText">Choose
                                        file</span></label></span>
                        </div>
                        <p class="file_error"></p>
                    </div>
                    <div class="mb-0">
                        <a href="{{ route('view.policies', 'policy_file') }}" class="btn btn-primary waves-effect">View
                            Policy</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!--end col-->
    <div class="col-6">
        {{-- Card-------- --}}
        <div class="card">

            <div class="card-body">

                <h4 class="card-title">Leave Policy File</h4>
                <p class="card-title-desc">Update Leave Policy File.</p>

                <form action="#">
                    <div class="mb-3">
                        <label class="form-label">Chose File</label>
                        <input type="file" class="filestyle" data-buttonname="btn-secondary" id="leavefile-0" tabindex="-1" style="position: absolute; clip: rect(0px, 0px, 0px, 0px);">
                        <div class="bootstrap-filestyle input-group">
                            <div name="filedrag" style="position: absolute; width: 100%; height: 35px; z-index: -1;">
                            </div>
                            <input type="text" class="form-control " id="leavefile-1" placeholder="" disabled="" style="border-top-left-radius: 0.25rem; border-bottom-left-radius: 0.25rem;">
                            <span class="group-span-filestyle input-group-btn" tabindex="0"><label for="leavefile-0" style="margin-bottom: 0;" class="btn btn-secondary chose-btn setting-choose-file-btn"><span class="buttonText">Choose
                                        file</span></label></span>
                        </div>
                        <p class="leavefile_error"></p>
                    </div>
                    <div class="mb-0">
                        <a href="{{ route('view.policies', 'leave_policy_file') }}" class="btn btn-primary waves-effect">View
                            Policy</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!--end col-->
</div>
<!--end row-->
<div class="row">
    @include('includes.add_permissions')
    @include('includes.manage_permissions')
</div>
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">System Setting Attributes</h4>
                <p class="card-title-desc">Global attributes</p>
                <table class="table table-bordered table-striped client-info-table">
                    <tbody>
                        <tr>
                            <td width="37%" valign="middle lab">
                                <div class="lab">Break Time</div>
                            </td>
                            {{-- <td width="63%" valign="middle">
                                <div class="row">
                                    <div style="display: flex; justify-content: space-between;" class="col-sm-12">
                                        <span>Daily 45 min</span>
                                        <span>On Friday 75 min</span>
                                        <input id="edit21" onclick="showEditDiv('daily_break_time', 'update_time', 'friday_break_time')" type="button" value="Edit" class="small-edit-btn">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="">
                                            <input id="daily_break_time" type="number" class="form-control hide  daily_break_time custom-shadow border-0" placeholder="00" value="45">
                                            <p style="color:red;" id="warning-message-daily_break_time" class="warning"></p>
                                        </div>

                                    </div>
                                    <div class="col-sm-4">
                                        <div class="">
                                            <input id="friday_break_time" type="number" class="form-control hide pbb friday_break_time custom-shadow border-0" placeholder="00" value="75">
                                            <p style="color:red;" id="warning-message-friday_break_time" class="warning"></p>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="cc"><button id="update_time" onclick="saveData('3809', 'daily_break_time','friday_break_time' )" class="btn btn-success hide mb-2">Save</button></div>
                                    </div>
                                </div>
                            </td> --}}

                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@include('includes.ajax_modal')
@include('includes.add_schedule')
@endsection
@section('script')
<!-- Responsive-table-->
<script src="{{ URL::asset('plugins/RWD-Table-Patterns/dist/js/rwd-table.min.js') }}"></script>
<script>
    $('#filestyle-0').on('change', function() {
        const splitFileName = $(this).val().split(/\\/);
        $('#filestyle-1').val(splitFileName[splitFileName.length - 1])
        let input_file = $(this).val()
        var formData = new FormData();
        let csrf_token = '{{ csrf_token() }}';
        formData.append('setting_name', 'policy_file');
        formData.append('file', $(this).prop('files')[0]);
        formData.append('_token', csrf_token);
        console.log()
        let url = '/upload_policy_file';
        settingFileUpload(url, formData, '.file_error');

    })
    $('#leavefile-0').on('change', function() {
        const splitFileName = $(this).val().split(/\\/);
        $('#leavefile-1').val(splitFileName[splitFileName.length - 1])
        let input_file = $(this).val()
        var formData = new FormData();
        let csrf_token = '{{ csrf_token() }}';
        formData.append('setting_name', 'leave_policy_file');
        formData.append('file', $(this).prop('files')[0]);
        formData.append('_token', csrf_token);
        console.log()
        let url = '/upload_policy_file';
        settingFileUpload(url, formData, '.leavefile_error');
    })
</script>
@endsection

@section('script')
<script>
    $(function() {
        $('.table-responsive').responsiveTable({
            addDisplayAllBtn: 'btn btn-secondary'
        });
    });
</script>
@endsection