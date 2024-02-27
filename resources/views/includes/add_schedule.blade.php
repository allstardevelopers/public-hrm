<!-- Add -->
<div class="modal fade" id="addnew">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><b>Add Schedule</b></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>

            </div>

            <div class="modal-body text-left">
                <form class="form-horizontal" id="add-schedule" method="POST" action="{{ route('schedule.store') }}">
                    @csrf
                    <ul class="error-container">
                    </ul>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="name" class="col-sm-3 control-label no-padding">Name</label>
                                <div class="">
                                    <input type="text" class="form-control timepicker" id="name" name="slug"
                                        required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="time_in" class="col-sm-3 control-label no-padding">Time In</label>


                                <div class="bootstrap-timepicker">
                                    <input type="time" class="form-control timepicker" id="time_in" name="time_in"
                                        required>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="time_out" class="col-sm-3 control-label no-padding">Time Out</label>

                                <div class="bootstrap-timepicker">
                                    <input type="time" class="form-control timepicker" id="time_out" name="time_out" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="friday_break" class="col-sm-12 control-label no-padding">Friday Break (min)</label>
                                <div class="">
                                    <input type="text" class="form-control timepicker" id="friday_break" name="friday_break" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="otherday_break" class="col-sm-12 control-label no-padding">Other Day Br... (min)</label>
                                <div class="">
                                    <input type="text" class="form-control timepicker" id="otherday_break"
                                        name="otherday_break" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="halfday_break" class="col-sm-12 control-label no-padding">Half Day Break (min)</label>
                                <div class="">
                                    <input type="text" class="form-control timepicker" id="halfday_break"
                                        name="halfday_break" required>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i
                        class="fa fa-close"></i> Close</button>
                <button type="submit" class="btn btn-primary btn-flat"><i class="fa fa-save"></i> Save</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    $('#add-schedule').submit(function(event) {
        console.log('check form submit')
        event.preventDefault();
        let formData = $(this).serialize();
        $.ajax({
            url: "{{ route('schedule.store') }}",
            method: "POST",
            data: formData,
            success: function(data) {
                window.location.reload();
            },
            error: function(xhr, status, error) {
                if (xhr.status === 422) {
                    var errorData = xhr.responseJSON;
                    // Process the error data
                    console.log(errorData);
                    // Display error messages to the 
                    var errorMessages = errorData.errors;
                    for (var field in errorMessages) {
                        if (errorMessages.hasOwnProperty(field)) {
                            var errorMessage = errorMessages[field];
                            var $errorItem = $('<li>' + field + ': ' + errorMessage + '</li>');
                            $('.error-container').append($errorItem);
                            // You can display the error message to the user using your preferred UI approach
                        }
                    }
                } else {
                    // Handle other error scenarios
                    console.log(error);
                    console.log(error);
                    var $errorItem = $('<li>' + error + '</li>');
                    $('.error-container').append($errorItem);
                }
            }
        })
    });
</script>
