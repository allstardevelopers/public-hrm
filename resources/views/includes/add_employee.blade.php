<style>
    .datepicker {
        /* border: 1px solid #ced4da; */
        padding: 8px;
    }

    .no-arrows {
        -moz-appearance: textfield;
        /* Firefox */
        appearance: textfield;
        /* other browsers */
    }
</style>
<!-- Add -->
<div class="modal fade " id="addnew">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><b>Add Employee</b></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="card-body text-left">
                    <form method="POST" id="add-employees" action="{{ route('employees.store') }}">
                        @csrf
                        <ul class="error-container">
                        </ul>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" class="form-control" placeholder="Enter Employee Name" id="name" name="name" required />
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="position">Position</label>
                                    <input type="text" class="form-control" placeholder="Position Title" id="position" name="position" required />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="joining_date">Date of Joining</label>
                                    <div class="bootstrap-datepicker">
                                        <input type="Date" class="form-control datepicker" id="joining_date" name="joining_date" required value="{{ Date('Y-m-d') }}" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="dob">Date of Birth</label>
                                    <div class="bootstrap-datepicker">
                                        <!-- <input type="Date" class="form-control datepicker" id="dob" name="dob" required value="{{ Date('Y-m-d') }}" required> -->
                                        <input type="text" id="dob" name="dob" class="form-control datepicker">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="probation">Probation</label>
                                    <select class="form-control" id="probation" name="probation" required>
                                        <option value="" selected>- Select -</option>
                                        @for ($i = 2; $i <= 6; $i++) <option value="{{ $i }}">{{ $i }}-months </option>
                                            @endfor

                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="contact_no">Contact No:</label>
                                    <input type="number" class="form-control no-arrows" placeholder="Enter Contact No" id="contact_no" name="contact_no" required />
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="emergency_no">Emergency Contact No:</label>
                                    <input type="number" class="form-control" placeholder="Enter Emergency No" id="emergency_no" name="emergency_no" required />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="schedule" class="">Schedule</label>
                                    <select class="form-control" id="schedule" name="schedule" required>
                                        <option value="" selected>- Select -</option>
                                        @foreach ($schedules as $schedule)
                                        <option value="{{ $schedule->slug }}">{{ $schedule->slug }} -> from
                                            {{ $schedule->time_in }}
                                            to {{ $schedule->time_out }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="form-group">
                                    <label for="gender">Gender</label>
                                    <select class="form-control" id="gender" name="gender" required>
                                        <option value="male" selected>Male</option>
                                        <option value="female">Female</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="email" class="">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="password" class="">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="modal-footer">
                            <button type="reset" class="btn btn-secondary waves-effect m-l-5" data-dismiss="modal">
                                Cancel
                            </button>
                            <button type="submit" class="btn btn-primary waves-effect waves-light">
                                Submit
                            </button>
                        </div>
                    </form>

                </div>
            </div>


        </div>

    </div>
</div>
</div>


<script>
    $('#add-employees').submit(function(event) {
        event.preventDefault();
        let formData = $(this).serialize();
        $.ajax({
            url: "{{ route('employees.store') }}",
            method: "POST",
            data: formData,
            success: function(resp) {
                if (resp == 'success') {
                    $('.error-container').empty();
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'Created',
                        showConfirmButton: false,
                        timer: 500
                    })
                    setTimeout(function() { // wait for 5 secs(2)
                        location.reload(); // then reload the page.(3)
                    }, 500)
                }
            },
            error: function(xhr, status, error) {
                if (xhr.status === 422) {
                    var errorData = xhr.responseJSON;
                    // Process the error data
                    console.log(errorData);
                    // Display error messages to the user
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
                    var $errorItem = $('<li>' + error + '</li>');
                    $('.error-container').append($errorItem);
                }
            }
        });
    });
</script>