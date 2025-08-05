@extends('admin::admin.layouts.master')

@section('title', 'User Roles Management')

@section('page-title', 'Manage User Roles')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Manage User Roles</li>
@endsection

@section('content')
    <!-- Container fluid  -->
    <div class="container-fluid">
        <!-- Start user role Content -->

        <div class="row">
            <div class="col-12">
                <div class="card card-body">
                    <h4 class="card-title">Filter</h4>
                    <form action="{{ route('admin.user_roles.index') }}" method="GET" id="filterForm">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="role">Role</label>
                                    <input type="text" name="role" id="role" class="form-control"
                                        value="{{ app('request')->query('role') }}" placeholder="Enter role">                                   
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="status">Status</label>
                                    <select name="status" id="status" class="form-control select2">
                                        <option value="">All</option>
                                        <option value="0" {{ app('request')->query('status') == '0' ? 'selected' : '' }}>Inactive</option>
                                        <option value="1" {{ app('request')->query('status') == '1' ? 'selected' : '' }}>Active</option>
                                    </select>                                   
                                </div>
                            </div>
                            <div class="col-auto mt-1 text-right">
                                <div class="form-group">
                                    <label for="created_at">&nbsp;</label>
                                    <button type="submit" form="filterForm" class="btn btn-primary mt-4">Filter</button>
                                    <a href="{{ route('admin.user_roles.index') }}" class="btn btn-secondary mt-4">Reset</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="text-right">
                            <a href="{{ route('admin.user_roles.create') }}" class="btn btn-primary mb-3">Create New User Role</a>
                        </div>
                    
                        <div class="table-responsive">
                            <table class="table">
                                <thead class="thead-light">
                                    <tr>
                                        <th scope="col">S. No.</th>
                                        <th scope="col">@sortablelink('name', 'Name', [], ['class' => 'text-dark'])</th>
                                        <th scope="col">@sortablelink('status', 'Status', [], ['class' => 'text-dark'])</th>
                                        <th scope="col">@sortablelink('created_at', 'Created At', [], ['class' => 'text-dark'])</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (isset($user_roles) && $user_roles->count() > 0)
                                        @php
                                            $i = ($user_roles->currentPage() - 1) * $user_roles->perPage() + 1;
                                        @endphp
                                        @foreach ($user_roles as $user_role)
                                            <tr>
                                                <th scope="row">{{ $i }}</th>
                                                <td>{{ $user_role->name ?? '' }}</td>
                                                <td>
                                                    <!-- create update status functionality-->
                                                    @if ($user_role->status == '1')
                                                        <a href="javascript:void(0)" data-toggle="tooltip" data-placement="top"
                                                            title="Click to change status to inactive"
                                                            data-url="{{ route('admin.user_roles.updateStatus') }}"
                                                            data-method="POST" data-status="0" data-id="{{ $user_role->id }}"
                                                            class="btn btn-success btn-sm update-status">Active</a>
                                                    @else
                                                        <a href="javascript:void(0)" data-toggle="tooltip" data-placement="top"
                                                            title="Click to change status to active"
                                                            data-url="{{ route('admin.user_roles.updateStatus') }}"
                                                            data-method="POST" data-status="1"
                                                            data-id="{{ $user_role->id }}"
                                                            class="btn btn-warning btn-sm update-status">InActive</a>
                                                    @endif
                                                </td>
                                                <td>
                                                    {{ $user_role->created_at
                                                        ? $user_role->created_at->format(config('GET.admin_date_time_format') ?? 'Y-m-d H:i:s')
                                                        : '—' }}
                                                </td>
                                                <td style="width: 10%;">
                                                    @admincan('user_roles_manager_view')
                                                    <a href="{{ route('admin.user_roles.show', $user_role) }}" 
                                                        data-toggle="tooltip"
                                                        data-placement="top"
                                                        title="View this record"
                                                        class="btn btn-warning btn-sm"><i class="mdi mdi-eye"></i></a>
                                                    @endadmincan
                                                    @admincan('user_roles_manager_edit') 
                                                    <a href="{{ route('admin.user_roles.edit', $user_role) }}"
                                                        data-toggle="tooltip"
                                                        data-placement="top"
                                                        title="Edit this record"
                                                        class="btn btn-success btn-sm"><i class="mdi mdi-pencil"></i></a>
                                                    @endadmincan
                                                    @admincan('user_roles_manager_delete')
                                                    <a href="javascript:void(0)" 
                                                        data-toggle="tooltip" 
                                                        data-placement="top"
                                                        title="Delete this record" 
                                                        data-url="{{ route('admin.user_roles.destroy', $user_role) }}"
                                                        data-text="Are you sure you want to delete this record?"                                                    
                                                        data-method="DELETE"
                                                        class="btn btn-danger btn-sm delete-record" ><i class="mdi mdi-delete"></i></a>
                                                    @endadmincan
                                                </td>
                                            </tr>
                                            @php
                                                $i++;
                                            @endphp
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="6" class="text-center">No user role found.</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>

                            <!--pagination move the right side-->
                            @if ($user_roles->count() > 0)
                                {{ $user_roles->links('admin::pagination.custom-admin-pagination') }}
                            @endif                        
                            
                        </div>
                    </div>
                </div>
            </div>


        </div>
        <!-- End user role Content -->
    </div>
    <!-- End Container fluid  -->
@endsection
