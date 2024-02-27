<style>
    .card {
        border: none;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .card-header {
        border: none;
        border-radius: 0;
    }

    .card-title {
        font-size: 24px;
    }

    .card-title-desc {
        font-size: 14px;
    }

    .role-card {
        background-color: #fff;
        transition: box-shadow 0.3s ease-in-out;
    }

    .role-card:hover {
        box-shadow: 0 6px 10px rgba(0, 0, 0, 0.15);
    }

    .role-name {
        font-weight: bold;
        font-size: 20px;
        display: block;
        margin-bottom: 10px;
    }

    .permissions-list {
        margin-top: 10px;
    }

    .permission-checkbox {
        display: block;
        position: relative;
        padding-left: 30px;
        margin-bottom: 5px;
        cursor: pointer;
        font-size: 14px;
    }

    .permission-input {
        position: absolute;
        opacity: 0;
        cursor: pointer;
    }

    .checkmark {
        position: absolute;
        top: 0;
        left: 0;
        height: 18px;
        width: 18px;
        background-color: #fff;
        border: 1px solid #ced4da;
        border-radius: 3px;
        transition: background-color 0.3s ease-in-out;
    }

    .permission-checkbox:hover .permission-input~.checkmark {
        background-color: #f0f0f0;
    }

    .permission-checkbox .checkmark:after {
        content: "";
        position: absolute;
        display: none;
    }

    .permission-input:checked~.checkmark:after {
        display: block;
        left: 5px;
        top: 1px;
        width: 5px;
        height: 10px;
        border: solid #007bff;
        border-width: 0 2px 2px 0;
        transform: rotate(45deg);
    }
</style>
<div class="col-md-6">
    <div class="container">
        <div class="permission-manager">
            <div class="card bg-light">
                <div class="card-header bg-primary text-white">
                    <h2 class="card-title">Manage Permissions</h2>
                    <p class="card-title-desc">Empower users with the right permissions</p>
                </div>
                <div class="card-body">
                    <div class="role-list">
                        <h3 class="role-list-title">Available Roles</h3>
                        <div class="row mb-3">
                            @foreach(get_roles() as $role)
                            <div class="col-md-6 mb-3">
                                <div class="role-card p-3 border rounded">
                                    <span class="role-name">{{$role->name}}</span>
                                    <div class="permissions-list mt-3">
                                        @foreach(get_permissions() as $permission)
                                        <label class="permission-checkbox">
                                            <input onclick="setPermission('<?= $permission->name ?>',<?= $role->id ?>,this)" id="<?= $permission->id ?>" type="checkbox" class="permission-input" {{ in_array($permission->name, explode(',', $role->permissions)) ? 'checked' : '' }}>
                                            <span class="checkmark"></span>
                                            {{ucwords(removeUndersquareCapitalize($permission->name))}}
                                        </label>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            @endforeach

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function setPermission(permission_name, role_id, _element) {
        $.ajax({
            url: "{{ route('update.permission') }}",
            method: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                name: permission_name,
                id: role_id,
                isAssign: _element.checked
            },
            success: function(resp) {
                if (resp.status === 'success') {
                    $('.error-container').empty();
                    Swal.fire({
                        position: 'center',
                        icon: 'success',
                        title: resp.status,
                        text: resp.msg,
                        showConfirmButton: false,
                        timer: 500
                    })
                    setTimeout(function() { // wait for 5 secs(2)
                        location.reload(); // then reload the page.(3)
                    }, 500)
                }
            }
        });
    }
</script>