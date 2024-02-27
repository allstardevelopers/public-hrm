<!-- Add -->
<style>
    textarea {
        width: 100%;
        height: 100px;
        background: none repeat scroll 0 0 rgba(0, 0, 0, 0.07);
        border-color: -moz-use-text-color #FFFFFF #FFFFFF -moz-use-text-color;
        border-image: none;
        border-radius: 6px 6px 6px 6px;
        border-style: none solid solid none;
        border-width: medium 1px 1px medium;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.12) inset;
        color: #555555;
        font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
        font-size: 1em;
        line-height: 1.4em;
        padding: 5px 8px;
        transition: background-color 0.2s ease 0s;
    }


    textarea:focus {
        background: none repeat scroll 0 0 #FFFFFF;
        outline-width: 0;
    }
</style>
<div class="modal fade" id="addreview">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><b>Leave a Reason</b></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>

            </div>
            <p>{{$data->message}}</p>
            <div class="modal-body">

                <div class="card-body text-left">

                    <form method="POST" id="add-reason">
                        @csrf
                        <div class="form-group">
                            <input type="hidden" id="emp_id" name="emp_id" value="{{$data->emp_id}}">
                            <input type="hidden" id="duration" name="duration" value="{{$data->duration}}">
                            <input type="hidden" id="attendance_status" name="attendance_status" value="{{$data->attendance_status}}">
                            <textarea class="with-border" placeholder="Comment" name="reason" id="reason" cols="7" required=""></textarea>
                        </div>
                        <div class="form-group">
                            <div>
                                <button type="submit" class="popup-with-zoom-anim btn btn-primary reason-btn w-md waves-effect waves-light full-width">
                                    Leave a Reason </button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>


        </div>

    </div>
</div>
</div>
<script>

$('#add-reason').submit(function(event) {
        console.log('check form submit')
        $('.reason-btn').attr("disabled", true);
        event.preventDefault();
        let formData = $(this).serialize();
        $.ajax({
            url: "{{route('reason')}}",
            method: "POST",
            data: formData,
            success: function(data) {
                window.location = "{{route('dashboard')}}";
                $('.reason-btn').attr("disabled", true);
            }
        });
    });

</script>