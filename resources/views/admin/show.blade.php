@extends('admin::admin.layouts.master')

@section('title', 'User Roles Management')

@section('page-title', 'User Role Details')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('admin.user_roles.index') }}">Manage User Roles</a></li>
    <li class="breadcrumb-item active" aria-current="page">User Role Details</li>
@endsection

@section('content')
    <!-- Container fluid  -->
    <div class="container-fluid">
        <!-- Start Email Content -->
        <div class="row">
            <div class="col-12">
                <div class="card">                    
                    <div class="table-responsive">
                         <div class="card-body">      
                            <table class="table table-striped">
                                <tbody>
                                    <tr>
                                        <th scope="row">Name</th>
                                        <td scope="col">{{ $user_role->name ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Status</th>
                                        <td scope="col"> {!! config('admin.constants.aryStatusLabel.' . $user_role->status, 'N/A') !!}</td>
                                    </tr>    
                                    <tr>
                                        <th scope="row">Created At</th>
                                        <td scope="col">{{ $user_role->created_at
                                            ? $user_role->created_at->format(config('GET.admin_date_time_format') ?? 'Y-m-d H:i:s')
                                            : '—' }}</td>
                                    </tr>                                
                                </tbody>
                            </table>   
                                             
                            <a href="{{ route('admin.user_roles.index') }}" class="btn btn-secondary">Back</a> 
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End user role Content -->
    </div>
    <!-- End Container fluid  -->
@endsection
