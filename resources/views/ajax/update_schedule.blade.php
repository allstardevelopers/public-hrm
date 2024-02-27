<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span></button>

</div>
<h4 class="modal-title"><b>Update Schedule</b></h4>
<div class="modal-body">
    <div class="card-body text-left">
        <form method="POST" id="update_schedule">
            @csrf
            <input type="hidden" id="schedule_id" name="schedule_id" value="{{$data['id']}}">
            <div class="row">
                <div class="col-md-12">
            <div class="form-group">
                <label for="name" class="col-sm-3 control-label no-padding">Name</label>
                <div class="bootstrap-timepicker">
                    <input type="text" class="form-control timepicker" id="name" name="slug"
                        value="{{ $data['slug'] }} ">
                </div>
            </div>
            </div>
            </div>
            <div class="row">
                <div class="col-md-12">
            <div class="form-group">
                <label for="edit_time_in" class="col-sm-3 control-label no-padding">Time In</label>


                <div class="bootstrap-timepicker">
                    <input type="time" class="form-control timepicker" id="edit_time_in" name="time_in"
                        value="{{ $data['time_in'] }}">
                </div>

            </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
            <div class="form-group">
                <label for="edit_time_out" class="col-sm-3 control-label no-padding">Time out</label>
                <div class="bootstrap-timepicker">
                    <input type="time" class="form-control timepicker" id="edit_time_out" name="time_out"
                        value="{{ $data['time_out'] }}">
                </div>
            </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
            <div class="form-group">
                <label for="edit_friday_break" class="col-sm-12 control-label no-padding">Friday Break (min)</label>
                <div class="bootstrap-timepicker">
                    <input type="text" class="form-control timepicker" id="edit_friday_break" name="friday_break"
                        value="{{ isset($data['friday_break'])?$data['friday_break']:'' }}">
                </div>
            </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="edit_otherday_break" class="col-sm-12 control-label no-padding">OtherDay Br.. (min)</label>
                        <div class="bootstrap-timepicker">
                            <input type="text" class="form-control timepicker" id="edit_otherday_break" name="otherday_break"
                                value="{{ isset($data['otherday_break'])?$data['otherday_break']:'' }}">
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="edit_halfday_break" class="col-sm-12 control-label no-padding">Half Day Br.. (min)</label>
                        <div class="bootstrap-timepicker">
                            <input type="text" class="form-control timepicker" id="edit_halfday_break" name="halfday_break"
                                value="{{ isset($data['halfday_break'])?$data['halfday_break']:'' }}">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-flat pull-left" data-dismiss="modal"><i
                            class="fa fa-close"></i>
                        Close</button>
                    <button type="submit" class="btn btn-success btn-flat"><i class="fa fa-check-square-o"></i>
                        Update</button>
                
            </div>
        </form>
    </div>
</div>
<script>
    $('#update_schedule').submit(function(event) {
        event.preventDefault();
        let formData = $(this).serialize();
        let id = $('#schedule_id').val()
        $.ajax({
            url: "{{ route('schedule.update', $data['id']) }}",
            method: "PUT",
            data: formData,
            success: function(resp) {
                if (resp == 'success') {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'Updated',
                        showConfirmButton: false,
                        timer: 500
                    })
                    setTimeout(function(){// wait for 5 secs(2)
                               location.reload(); // then reload the page.(3)
                    }, 500)
                }
            }
        });
    });
</script>
