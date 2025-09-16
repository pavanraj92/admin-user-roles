@extends('admin::admin.layouts.master')

@section('title', 'User Roles Management')

@section('page-title', 'Create User Role')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('admin.user_roles.index') }}">Manage User
            Roles</a></li>
    <li class="breadcrumb-item active" aria-current="page">Create User Role</li>
@endsection

@section('content')
    <div class="container-fluid">
        <!-- Start user_role Content -->
        <div class="row">
            <div class="col-12">
                <div class="card card-body">
                    <form
                        action="{{ isset($user_role) ? route('admin.user_roles.update', $user_role->id) : route('admin.user_roles.store') }}"
                        method="POST" id="userRoleForm" enctype="multipart/form-data">
                        @if (isset($user_role))
                            @method('PUT')
                        @endif
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Name<span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control alphabets-only"
                                        value="{{ $user_role?->name ?? old('name') }}" required>
                                    @error('name')
                                        <div class="text-danger validation-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Status<span class="text-danger">*</span></label>
                                    <select name="status" class="form-control select2" required>
                                        @foreach (config('user_role.constants.status', []) as $key => $label)
                                            <option value="{{ $key }}"
                                                {{ (isset($user_role) && (string) $user_role?->status === (string) $key) || old('status') === (string) $key ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('status')
                                        <div class="text-danger validation-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary" id="saveBtn">Save</button>
                            <a href="{{ route('admin.user_roles.index') }}" class="btn btn-secondary">Back</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- End user_role Content -->
    </div>
@endsection

@push('scripts')

    <script>
        $(document).ready(function() {
            // Initialize Select2 for any select elements with the class 'select2'
            $('.select2').select2();

            $.validator.addMethod(
                "alphabetsOnly",
                function(value, element) {
                    return this.optional(element) || /^[a-zA-Z\s]+$/.test(value);
                },
                "Please enter letters only"
            );

            //jquery validation for the form
            $('#userRoleForm').validate({
                ignore: [],
                rules: {
                    name: {
                        required: true,
                        minlength: 3,
                        alphabetsOnly: true
                    }
                },
                messages: {
                    name: {
                        required: "Please enter name",
                        minlength: "Name must be at least 3 characters long"
                    }
                },
                submitHandler: function(form) {
                    const $btn = $('#saveBtn');
                    $btn.prop('disabled', true).text('Saving...');
                    form.submit();
                },
                errorElement: 'div',
                errorClass: 'text-danger custom-error',
                errorPlacement: function(error, element) {
                    $('.validation-error').hide(); // hide blade errors
                    error.insertAfter(element);
                }
            });

            $('#imageInput').on('change', function(event) {
                const input = event.target;
                const preview = $('#imagePreview');
                preview.empty(); // Remove old image

                if (input.files && input.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        preview.html('<img src="' + e.target.result +
                            '" style="max-width:200px; max-height:120px;" />');
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            });
        });
    </script>
@endpush
