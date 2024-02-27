<!-- App favicon -->
<link rel="shortcut icon" href="{{ URL::asset('assets/images/ast-favicon.ico') }}">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}" />
@yield('css')
<style>
    .event-card {
        background-color: #f9f9f9;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 15px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .event-title {
        font-size: 18px;
        font-weight: bold;
        margin-bottom: 5px;
    }

    .event-date {
        color: #888;
    }

    .event-badge {
        padding: 5px 10px;
        border-radius: 5px;
        font-size: 12px;
        font-weight: bold;
        text-transform: uppercase;
        margin-top: 10px;
    }

    .event-badge-open {
        background-color: #66BB6A;
        color: #fff;
    }

    .event-badge-closed {
        background-color: #FF5252;
        color: #fff;
    }

    .toggle-button {
        cursor: pointer;
    }
</style>
<!-- App css -->
<link href="{{ URL::asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('assets/css/metismenu.min.css') }}" rel="stylesheet" type="text/css">
<link href="{{ URL::asset('assets/css/icons.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('assets/css/style.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('assets/css/custom-style.css') }}" rel="stylesheet" type="text/css" />


{{-- <link href="{{ URL::asset('plugins/sweet-alert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css"> --}}
<link href="{{ asset('plugins/sweetalert.min.css') }}" rel="stylesheet">
<!-- Table css -->
<link href="{{ URL::asset('plugins/RWD-Table-Patterns/dist/css/rwd-table.min.css') }}" rel="stylesheet" type="text/css" media="screen">
<!-- DataTables -->
<link href="{{ URL::asset('plugins/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('plugins/datatables/buttons.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
<!-- Responsive datatable examples -->
<link href="{{ URL::asset('plugins/datatables/responsive.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
<!-- Jquery Min Js 3.3.1 -->
<!-- Add this in the <head> section -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<!-- Add this before the closing </body> tag -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
{{-- Select 2 --}}
<link rel="stylesheet" href="{{URL::asset('plugins/select2/css/select2.min.css')}}">
<link rel="stylesheet" href="{{URL::asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
{{-- end --}}
<script src="{{ URL::asset('assets/js/jquery.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-success',
            cancelButton: 'btn btn-danger'
        },
        buttonsStyling: true
    })
</script>


{{------ Fire Base Notification Enabale--- --}}