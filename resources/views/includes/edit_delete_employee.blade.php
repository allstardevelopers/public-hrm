<!-- Edit -->
<div class="modal fade" id="edit{{ $employee->id }}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><b><span class="employee_id">Update Employee</span></b></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
            </div>

            <div class="modal-body text-left">
                <form class="form-horizontal" id="up-employee" method="POST" action="{{ route('employees.update', $employee->id) }}">
                    @csrf
                    <input type="hidden" name="_method" value="PUT">
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="name" class="col-sm-3 control-label no-padding">Name</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ $employee->name }}" required>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="name" class="col-sm-3 control-label no-padding">Position</label>
                                <input type="text" class="form-control" id="position" name="position" value="{{ $employee->position }}" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="joining_date">Date of Joining</label>
                                <div class="bootstrap-datepicker">
                                    <input type="Date" class="form-control datepicker" id="joining_date" name="joining_date" required value="{{$employee->joining_date}}" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="dob">Date of Birth</label>
                                <div class="bootstrap-datepicker">
                                    <!-- <input type="Date" class="form-control datepicker" id="dob" name="dob" required value="{{ Date('Y-m-d') }}" required> -->
                                    <input type="text" id="dob" name="dob" value="{{$employee->dob}}" class="form-control datepicker">
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
                                    @for ($i = 2; $i <= 6; $i++) <option {{ $employee->probation == $i ? " selected ": "" }} value="{{ $i }}">{{ $i }}-months </option>
                                        @endfor

                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="contact_no">Contact No:</label>
                                <input type="tel" class="form-control no-arrows" value="{{$employee->contact_no}}" placeholder="Enter Contact No" id="contact_no" name="contact_no" required />
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="emergency_no">Emergency Contact No:</label>
                                <input type="tel" class="form-control" value="{{$employee->emergency_no}}" placeholder="Enter Emergency No" id="emergency_no" name="emergency_no" required />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="schedule" class="col-sm-3 control-label no-padding">Schedule</label>
                                <select class="form-control" id="schedule" name="schedule" required>
                                    <option value="" selected>- Select -</option>

                                    @foreach ($schedules as $schedule)
                                    <option {{ $employee->schedules->first()->slug == $schedule->slug ? " selected ": "" }} value="{{ $schedule->slug }}">{{ $schedule->slug }} -> from
                                        {{ $schedule->time_in }} to {{ $schedule->time_out }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="form-group">
                                <label for="gender">Gender</label>
                                <select class="form-control" id="gender" name="gender" required>
                                    <option {{ $employee->gender == 'male' ? " selected ": "" }} value="male"  >Male</option>
                                    <option {{ $employee->gender == 'female' ? " selected ": "" }} value="female">Female</option>
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="email" class="col-sm-3 control-label no-padding">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ $employee->email }}">
                    </div>
                    <div class="form-group">
                        <label for="password" class="col-sm-3 control-label no-padding">Password</label>
                        <input type="password" class="form-control" id="password" name="password">
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
                <button type="submit" class="btn btn-success btn-flat" name="edit"><i class="fa fa-check-square-o"></i>
                    Update</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Delete -->
<div class="modal fade" id="delete{{ $employee->id }}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header " style="align-items: center">

                <h4 class="modal-title "><span class="employee_id">Delete Employee</span></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" method="POST" action="{{ route('employees.destroy', $employee->id) }}">
                    @csrf
                    {{ method_field('DELETE') }}
                    <div class="text-center">
                        <h6>Are you sure you want to delete:</h6>
                        <h2 class="bold del_employee_name">{{$employee->name}}</h2>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
                <button type="submit" class="btn btn-danger btn-flat"><i class="fa fa-trash"></i> Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>