@extends('admin::admin.layouts.master')

@section('title', 'User Roles Management')

@section('page-title', 'User Role Details')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">
        <a href="{{ route('admin.user_roles.index') }}">Manage User Roles</a>
    </li>
    <li class="breadcrumb-item active" aria-current="page">User Role Details</li>
@endsection

@section('content')
    <!-- Container fluid  -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <!-- Header with Back button -->
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h4 class="card-title mb-0">{{ $user_role->name ?? 'N/A' }} - User Role</h4>
                            <div>
                                <a href="{{ route('admin.user_roles.index') }}" class="btn btn-secondary ml-2">
                                    Back
                                </a>
                            </div>
                        </div>

                        <div class="row">
                            <!-- User Role Information -->
                            <div class="col-md-8">
                                <div class="card">
                                    <div class="card-header bg-primary">
                                        <h5 class="mb-0 text-white font-bold">User Role Information</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label class="font-weight-bold">Name:</label>
                                            <p>{{ $user_role->name ?? 'N/A' }}</p>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="font-weight-bold">Status:</label>
                                                    <p>{!! config('user_role.constants.aryStatusLabel.' . $user_role->status, 'N/A') !!}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="font-weight-bold">Created At:</label>
                                                    <p>
                                                        {{ $user_role->created_at ? $user_role->created_at->format(config('GET.admin_date_time_format') ?? 'Y-m-d H:i:s') : '—' }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Quick Actions -->
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-header bg-primary">
                                        <h5 class="mb-0 text-white font-bold">Quick Actions</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-flex flex-column">
                                            @admincan('user_roles_manager_edit')
                                                <a href="{{ route('admin.user_roles.edit', $user_role) }}" class="btn btn-warning mb-2">
                                                    <i class="mdi mdi-pencil"></i> Edit User Role
                                                </a>
                                            @endadmincan

                                            @admincan('user_roles_manager_delete')
                                                <button type="button" class="btn btn-danger delete-btn delete-record"
                                                    title="Delete this record"
                                                    data-url="{{ route('admin.user_roles.destroy', $user_role) }}"
                                                    data-redirect="{{ route('admin.user_roles.index') }}"
                                                    data-text="Are you sure you want to delete this record?"
                                                    data-method="DELETE">
                                                    <i class="mdi mdi-delete"></i> Delete User Role
                                                </button>
                                            @endadmincan
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div><!-- row end -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Container fluid  -->
@endsection
