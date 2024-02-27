<!-- App's Basic Js  -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        flatpickr(".datepicker", {
            // Set the date format as 'Y-m-d' to match Laravel's date format
            dateFormat: 'Y-m-d',
            // Allow selecting dates from previous years
            yearRange: '1900:' + new Date().getFullYear(),
        });
    });
</script>

<script>
    function uploadProfilePic() {
        const profilePicForm = document.getElementById("profilePicForm");
        const formData = new FormData(profilePicForm);

        fetch("/upload-profile-pic", {
                method: "POST",
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                // Handle the response, e.g., show success or error message
                if (data.status === 200) {
                    $("#file_error_msg").text(data.msg);
                    document.getElementById('file_error_msg').style.color = "#2ec32e";
                    timerIntForElements('file_error_msg', 3000);
                }
            })
            .catch(error => {
                console.error("Error:", error);
                $("#file_error_msg").text(data.error);
                document.getElementById('file_error_msg').style.color = "red";
                timerIntForElements('file_error_msg', 3000);
            });
    }

    function timerIntForElements(elemId, time_period) {
        setTimeout(function() {
            console.log('Wait 3 seconds and I appear just once', elemId);
            $(`#${elemId}`).text('');
        }, time_period);
    }
</script>
{{-- --------------- Function Script--------- --}}
<script>
    // ----------Trim & Update break ------------------

$('.update_trim_break').on('click', function() {
        let id = $(this).data('id');
        let reason = $(this).data('reason');
        let url = '/approveclockout/' + id;
        console.log(url);
        swalWithBootstrapButtons.fire({
            title: 'Do you want to Approve Break?',
            text: reason,
            icon: 'warning',
            showCancelButton: true,
            cancelButtonText: 'Disapprove!',
            confirmButtonText: 'Approve!',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                get_ajax_call(url)
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                url = '/disapproveclockout/' + id;
                getswalinput(url)
            }
        })
    })
    // ----------Update Clockouts ------------------
    $('.update_clockin').on('click', function() {
        let id = $(this).data('id');
        let reason = $(this).data('reason');
        let url = '/approveclockout/' + id;
        console.log(url);
        swalWithBootstrapButtons.fire({
            title: 'Do you want to Approve Break?',
            text: reason,
            icon: 'warning',
            showCancelButton: true,
            cancelButtonText: 'Disapprove!',
            confirmButtonText: 'Approve!',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                get_ajax_call(url)
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                url = '/disapproveclockout/' + id;
                getswalinput(url)
            }
        })
    })
    // ------------View Clockout-------------------------
     // View Clock Out reaso----------------
     $('.view_clockout_reason').on('click', function() {
        console.log($(this).data('id'));
        let id = $(this).data('id');
        let reason = $(this).data('reason')+'<br>'+$(this).data('remarks');
        let url = '/approveclockout/' + id;
        console.log(url);
        swalWithBootstrapButtons.fire({
            title: 'Clock Out Reason',
            html: reason,
            icon: 'warning',
            confirmButtonText: 'OK',
            reverseButtons: true
        }).then((result) => {})
    })
    // ----------Update Leave Request------------------
    $('.update_leaverequest').on('click', function() {
        console.log($(this).data('id'));
        let id = $(this).data('id');
        let url = '/leave_data/' + id;
        console.log(url);
        $.ajax({
            url: url,
            type: "Get",
            dataType: 'json',
            success: function(data) {
                ajax_modal(data, "" + "{{ route('ajax_modal_contents', 'update_leaveRequest') }}")
            }

        })
    })
    // View Leave  reaso----------------
    $('.view_leave_reason').on('click', function() {
        console.log($(this).data('id'));
        let id = $(this).data('id');
        let reason = $(this).data('reason');
        let url = '/approveclockout/' + id;
        console.log(url);
        swalWithBootstrapButtons.fire({
            title: 'Leave Reason',
            text: reason,
            icon: 'warning',
            confirmButtonText: 'OK',
            reverseButtons: true
        }).then((result) => {})
    })
    // ------------Update Leave reason --------------
    function ajaxRenderModal(id, url, method, view) {
        // console.log('Range faza ki chilman me')
        $.ajax({
            url: url + '/' + view,
            type: "Get",
            dataType: 'json',
            success: function(data) {
                ajax_modal(data, "" + "{{ route('ajax_modal_contents', 'update_user') }}")
            }
        })
    }

    function ajaxRenderTab(id, url, method, view) {
        $.ajax({
            url: url + '/' + view,
            type: "Get",
            dataType: 'json',
            success: function(data) {
                console.log(data)
                // ajax_tab(data, "" + "{{ route('ajax_modal_contents', 'update_user') }}")
            }
        })
    }
    // -------------Dekete--------------------
    function deleteSwal(id, title, url, flag) {
        swalWithBootstrapButtons.fire({
            title: title,
            icon: 'warning',
            showCancelButton: true,
            cancelButtonText: 'Cancel',
            confirmButtonText: 'Confirm',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                get_ajax_call(url)
            } 
        })
      
    }
    function saveData(id, $field1, $field2) {
        var newVal = $('.' + columnName + '').val();

    }
</script>

<!-- <script src="{{ URL::asset('assets/js/jquery.min.js') }}"></script> -->
<script src="{{ URL::asset('assets/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ URL::asset('assets/js/metisMenu.min.js') }}"></script>
<script src="{{ URL::asset('assets/js/jquery.slimscroll.js') }}"></script>
<script src="{{ URL::asset('assets/js/waves.min.js') }}"></script>

@yield('script')

<!-- App js-->
<script src="{{ URL::asset('assets/js/app.js') }}"></script>
<script src="{{ URL::asset('assets/js/custom.js') }}"></script>



<!-- Sweet-Alert  -->
{{-- <script src="{{ URL::asset('plugins/sweet-alert2/sweetalert2.min.js') }}"></script>
<script src="{{ URL::asset('assets/pages/sweet-alert.init.js') }}"></script> --}}
<script src="/js/sweetalert.min.js"></script>
<!-- Responsive-table-->
<script src="{{ URL::asset('plugins/RWD-Table-Patterns/dist/js/rwd-table.min.js') }}"></script>
<!-- Required datatable js -->
<script src="{{ URL::asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ URL::asset('plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>
<!-- Buttons examples -->
<script src="{{ URL::asset('plugins/datatables/dataTables.buttons.min.js') }}"></script>
<script src="{{ URL::asset('plugins/datatables/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ URL::asset('plugins/datatables/jszip.min.js') }}"></script>
<script src="{{ URL::asset('plugins/datatables/pdfmake.min.js') }}"></script>
<script src="{{ URL::asset('plugins/datatables/vfs_fonts.js') }}"></script>
<script src="{{ URL::asset('plugins/datatables/buttons.html5.min.js') }}"></script>
<script src="{{ URL::asset('plugins/datatables/buttons.print.min.js') }}"></script>
<script src="{{ URL::asset('plugins/datatables/buttons.colVis.min.js') }}"></script>
<!-- Responsive examples -->
<script src="{{ URL::asset('plugins/datatables/dataTables.responsive.min.js') }}"></script>
<script src="{{ URL::asset('plugins/datatables/responsive.bootstrap4.min.js') }}"></script>

<!-- Datatable init js -->
<script src="{{ URL::asset('assets/pages/datatables.init.js') }}"></script>
<script src="{{ URL::asset('plugins/select2/js/select2.full.min.js') }}"></script>
<!-- ---------birthday slider----------- -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.6.0/slick.js"></script>
<script>
    $(document).ready(function() {
        $('.birthday-slider').slick({
            slidesToShow: 1,
            slidesToScroll: 1,
            autoplay: true,
            autoplaySpeed: 3000,
            arrows: false,
            dots: false,
            pauseOnHover: false,
            responsive: [{
                breakpoint: 768,
                settings: {
                    slidesToShow: 1
                }
            }, {
                breakpoint: 520,
                settings: {
                    slidesToShow: 1
                }
            }]
        });
    });
</script>
@if(isset($total_count))
<script>
    $(document).ready(function() {
        var tableId = "{{ $slug === 'admin' ? 'datatable-buttons' : 'datatable' }}"; // Dynamically get the table ID based on $slug
        let page_length = "{{$total_count}}";

        // Check if DataTable is already initialized
        if ($.fn.DataTable.isDataTable('#' + tableId)) {
            $('#' + tableId).DataTable().destroy(); // Destroy existing DataTable
        }

        // Initialize DataTable with options
        $('#' + tableId).DataTable({
            columnDefs: [{
                type: 'date-eu',
                targets: 0
            }],
            order: [
                [0, 'desc']
            ],
            "pageLength": page_length,
        });
    });
</script>
@endif
@yield('script-bottom')