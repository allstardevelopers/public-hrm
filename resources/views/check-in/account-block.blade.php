@extends('layouts.master-blank')

@section('content')

<style>
    .full-width {
        max-width: 100%;
        width: 100%;
        text-align: center;
        display: block;
    }
</style>

<div class="wrapper-page">
    <div class="card overflow-hidden account-card mx-3">
        <div class="bg-primary p-4 text-white text-center position-relative">
            <h4 class="font-20 m-b-5">Account Block !</h4>
            <p class="text-white-50 mb-4">Your Account Suspended!</p>
            <a href="#" class="logo logo-admin">
                <img src="{{ URL::asset('assets/images/ast-favicon.ico') }}" width="100%" alt="favicon">
            </a>
        </div>
        <div class="account-card-content">
            <div class="text-center ">
                <span class="text-danger">Contact Admin if You think this is by mistake </span>
                {{-- <a {{$data->link}} class="popup-with-zoom-anim btn btn-primary w-md waves-effect waves-light full-width" form="login-form">Check In</a> --}}
            </div>
        </div>
    </div>
</div>
<!-- end wrapper-page -->
{{-- @include('includes.add_review') --}}

@endsection
@section('script')
@endsection