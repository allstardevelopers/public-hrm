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
    <div class="wrapper-page" style="margin-bottom: -26px">
        <div class="card overflow-hidden account-card mx-3">
            <div class="bg-dark p-2 text-white d-flex justify-content-between position-relative">
                <a href="/" class="">
                    <span style="color: white; ">AST
                    </span>
                </a>
                <a class=" text-danger" href="{{ route('logout') }}"
                    onclick="event.preventDefault();
            document.getElementById('logout-form').submit();"><i
                        class="mdi mdi-power text-danger"></i> {{ __('Logout') }}</a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>

        </div>
    </div>
    <div class="wrapper-page">
        <div class="card overflow-hidden account-card mx-3">
            <div class="bg-primary p-4 text-white text-center position-relative">
                <h4 class="font-20 m-b-5">Welcome Back !</h4>
                <p class="text-white-50 mb-4">We're glad to see you again!</p>
                <a href="#" class="logo logo-admin">
                    <img src="{{ URL::asset('assets/images/ast-favicon.ico') }}" width="100%" alt="favicon">
                </a>
            </div>
            <div class="account-card-content">
                <div class="text-center ">
                    <span class="text-danger">Please first Check in from Clock App</span>
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
