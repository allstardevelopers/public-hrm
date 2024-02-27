<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span></button>
</div>
<h4 class="modal-title"><b>Employee Details</b></h4>
<div class="modal-body">
    <div class="card" style="border-radius: 15px;">
        <div class="card-body text-center">
            {{-- <div class="mt-3 mb-4">
        <img src="{{ asset('assets/images/blue-background.PNG')}}"
          class="rounded-circle img-fluid" style="width: 100px;" />
      </div> --}}
            <h4 class="mb-2">{{ $data['name'] }}</h4>
            <p class="text-muted mb-4">{{ $data['position'] }} <span class="mx-2">|</span> <a href="#!">
                    @if (!$data['joining_date']=='')
                        {{ date('d-m-Y', strtotime($data['joining_date'])) }}
                    @else
                        {{ date('d-m-Y', strtotime($data['created_at'])) }}
                    @endif
                </a></p>
            {{-- <div class="mb-4 pb-2">
        <button type="button" class="btn btn-outline-primary btn-floating">
          <i class="fab fa-facebook-f fa-lg"></i>
        </button>
        <button type="button" class="btn btn-outline-primary btn-floating">
          <i class="fab fa-twitter fa-lg"></i>
        </button>
        <button type="button" class="btn btn-outline-primary btn-floating">
          <i class="fab fa-skype fa-lg"></i>
        </button>
      </div> --}}
            <div class="row">
                <div class="col-12">
                    <div class="row">
                        <div class="col-sm-4">
                            <h6 class="mt-0 text-left">Email:</h6>
                        </div>
                        <div class="col-sm-8 text-secondary text-left">
                            <span>{{ $data['email'] }}</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <h6 class="mt-0 text-left">Joining Date:</h6>
                        </div>
                        <div class="col-sm-8 text-secondary text-left">
                            <span>
                                @if (!$data['joining_date']=='')
                                    {{ date('d-m-Y', strtotime($data['joining_date'])) }}
                                @else
                                    {{ date('d-m-Y', strtotime($data['created_at'])) }}
                                @endif
                            </span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <h6 class="mt-0 text-left">Contact No:</h6>
                        </div>
                        <div class="col-sm-8 text-secondary text-left">
                            <span>{{ $data['contact_no'] }}</span>
                        </div>
                    </div>
                    {{-- <div class="row">
                        <div class="col-sm-4">
                            <h6 class="mt-0 text-left">Gender</h6>
                        </div>
                        <div class="col-sm-8 text-secondary text-left">
                            <span>{{ $data['gender'] }}</span>
                        </div>
                    </div> --}}
                    <div class="row">
                        <div class="col-sm-4">
                            <h6 class="mt-0 text-left">Emerg No:</h6>
                        </div>
                        <div class="col-sm-8 text-secondary text-left">
                            <span>{{ $data['emergency_no'] }}</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <h6 class="mt-0 text-left">Status:</h6>
                        </div>
                        <div class="col-sm-8 text-secondary text-left">
                            @if($data['status']==1)
                                <span class="badge badge-success badge-pill float-left">Active</span>
                            @elseif($data['status']==2)
                                <span class="badge badge-warning badge-pill float-left">Resigned</span>
                            @elseif($data['status']==0)
                                <span class="badge badge-danger badge-pill float-left">Inactive</span>    
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-between text-center mt-5 mb-2">
                <div class="shadow rounded p-3">
                    <p class="mb-2 h5">{{ get_workingHour_monthly($data['id'], Date('m')) }}</p>
                    <p class="text-muted mb-0">This Month</p>
                </div>
                <div class="shadow rounded p-3">
                    <p class="mb-2 h5">{{ get_workingHour_monthly($data['id'], Date('m', strtotime('-1 month'))) }}</p>
                    <p class="text-muted mb-0">Last Month</p>
                </div>
                <div class="shadow rounded p-3">
                    <p class="mb-2 h5">{{ get_thisweek_hour($data['id']) }}</p>
                    <p class="text-muted mb-0">This week</p>
                </div>
            </div>
        </div>
    </div>
</div>
