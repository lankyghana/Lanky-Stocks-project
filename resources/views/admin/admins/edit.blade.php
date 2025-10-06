@extends('admin.layouts.app')

@php
use Illuminate\Support\Facades\Schema;
@endphp

@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="las la-user-edit"></i> @lang('Edit Admin')
                    </h5>
                    @if(auth('admin')->user()->hasPermission('admins.view'))
                    <a href="{{ route('admin.admins.index') }}" class="btn btn-sm btn-outline--primary">
                        <i class="las la-arrow-left"></i> @lang('Back to List')
                    </a>
                    @endif
                </div>
                <form action="{{ route('admin.admins.update', $admin->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Name') <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="name" value="{{ old('name', $admin->name) }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Username') <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="username" value="{{ old('username', $admin->username) }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Email') <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" name="email" value="{{ old('email', $admin->email) }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Role') <span class="text-danger">*</span></label>
                                    <select class="form-control" name="role" required {{ $admin->role === 'super_admin' ? 'disabled' : '' }}>
                                        <option value="">@lang('Select Role')</option>
                                        <option value="admin" {{ old('role', $admin->role) == 'admin' ? 'selected' : '' }}>@lang('Admin')</option>
                                        <option value="manager" {{ old('role', $admin->role) == 'manager' ? 'selected' : '' }}>@lang('Manager')</option>
                                        <option value="viewer" {{ old('role', $admin->role) == 'viewer' ? 'selected' : '' }}>@lang('Viewer')</option>
                                        @if($admin->role === 'super_admin')
                                            <option value="super_admin" selected>@lang('Super Admin')</option>
                                        @endif
                                    </select>
                                    @if($admin->role === 'super_admin')
                                        <input type="hidden" name="role" value="super_admin">
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Password') <small class="text-muted">(@lang('Leave empty to keep current password'))</small></label>
                                    <input type="password" class="form-control" name="password">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Confirm Password')</label>
                                    <input type="password" class="form-control" name="password_confirmation">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>@lang('Status') <span class="text-danger">*</span></label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="status" value="1" {{ old('status', $admin->status) ? 'checked' : '' }}>
                                        <label class="form-check-label">
                                            @lang('Active')
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if($admin->role !== 'super_admin' && Schema::hasColumn('admins', 'permissions') && auth('admin')->user()->hasPermission('admins.manage'))
                            <div class="row">
                                <div class="col-md-12">
                                    <h6 class="mb-3">@lang('Permissions')</h6>
                                    <div class="permission-grid">
                                        <div class="row">
                                            @foreach($permissions as $category => $categoryPermissions)
                                                <div class="col-md-4 mb-4">
                                                    <h6 class="text-muted">{{ $category }}</h6>
                                                    @foreach($categoryPermissions as $permission => $description)
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" 
                                                                   name="permissions[]" 
                                                                   value="{{ $permission }}" 
                                                                   {{ in_array($permission, old('permissions', $admin->permissions ?? [])) ? 'checked' : '' }}>
                                                            <label class="form-check-label">{{ $description }}</label>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        
                                            
                                            <div class="col-md-3">
                                                <h6 class="text-muted">@lang('Service Management')</h6>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="permissions[]" value="services.view" {{ in_array('services.view', old('permissions', $admin->permissions ?? [])) ? 'checked' : '' }}>
                                                    <label class="form-check-label">@lang('View Services')</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="permissions[]" value="services.manage" {{ in_array('services.manage', old('permissions', $admin->permissions ?? [])) ? 'checked' : '' }}>
                                                    <label class="form-check-label">@lang('Manage Services')</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="permissions[]" value="services.delete" {{ in_array('services.delete', old('permissions', $admin->permissions ?? [])) ? 'checked' : '' }}>
                                                    <label class="form-check-label">@lang('Delete Services')</label>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-3">
                                                <h6 class="text-muted">@lang('Order Management')</h6>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="permissions[]" value="orders.view" {{ in_array('orders.view', old('permissions', $admin->permissions ?? [])) ? 'checked' : '' }}>
                                                    <label class="form-check-label">@lang('View Orders')</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="permissions[]" value="orders.manage" {{ in_array('orders.manage', old('permissions', $admin->permissions ?? [])) ? 'checked' : '' }}>
                                                    <label class="form-check-label">@lang('Manage Orders')</label>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-3">
                                                <h6 class="text-muted">@lang('Financial')</h6>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="permissions[]" value="deposits.view" {{ in_array('deposits.view', old('permissions', $admin->permissions ?? [])) ? 'checked' : '' }}>
                                                    <label class="form-check-label">@lang('View Deposits')</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="permissions[]" value="deposits.manage" {{ in_array('deposits.manage', old('permissions', $admin->permissions ?? [])) ? 'checked' : '' }}>
                                                    <label class="form-check-label">@lang('Manage Deposits')</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="permissions[]" value="withdrawals.view" {{ in_array('withdrawals.view', old('permissions', $admin->permissions ?? [])) ? 'checked' : '' }}>
                                                    <label class="form-check-label">@lang('View Withdrawals')</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="permissions[]" value="withdrawals.manage" {{ in_array('withdrawals.manage', old('permissions', $admin->permissions ?? [])) ? 'checked' : '' }}>
                                                    <label class="form-check-label">@lang('Manage Withdrawals')</label>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row mt-3">
                                            <div class="col-md-3">
                                                <h6 class="text-muted">@lang('System')</h6>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="permissions[]" value="system.view" {{ in_array('system.view', old('permissions', $admin->permissions ?? [])) ? 'checked' : '' }}>
                                                    <label class="form-check-label">@lang('View System')</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="permissions[]" value="system.manage" {{ in_array('system.manage', old('permissions', $admin->permissions ?? [])) ? 'checked' : '' }}>
                                                    <label class="form-check-label">@lang('Manage System')</label>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-3">
                                                <h6 class="text-muted">@lang('Reports')</h6>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="permissions[]" value="reports.view" {{ in_array('reports.view', old('permissions', $admin->permissions ?? [])) ? 'checked' : '' }}>
                                                    <label class="form-check-label">@lang('View Reports')</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="permissions[]" value="reports.generate" {{ in_array('reports.generate', old('permissions', $admin->permissions ?? [])) ? 'checked' : '' }}>
                                                    <label class="form-check-label">@lang('Generate Reports')</label>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-3">
                                                <h6 class="text-muted">@lang('Admin Management')</h6>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="permissions[]" value="admins.view" {{ in_array('admins.view', old('permissions', $admin->permissions ?? [])) ? 'checked' : '' }}>
                                                    <label class="form-check-label">@lang('View Admins')</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="permissions[]" value="admins.manage" {{ in_array('admins.manage', old('permissions', $admin->permissions ?? [])) ? 'checked' : '' }}>
                                                    <label class="form-check-label">@lang('Manage Admins')</label>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-3">
                                                <h6 class="text-muted">@lang('Settings')</h6>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="permissions[]" value="settings.view" {{ in_array('settings.view', old('permissions', $admin->permissions ?? [])) ? 'checked' : '' }}>
                                                    <label class="form-check-label">@lang('View Settings')</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="permissions[]" value="settings.manage" {{ in_array('settings.manage', old('permissions', $admin->permissions ?? [])) ? 'checked' : '' }}>
                                                    <label class="form-check-label">@lang('Manage Settings')</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn--primary">@lang('Update Admin')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('style')
    <style>
        .permission-grid .form-check {
            margin-bottom: 0.5rem;
        }
        .permission-grid h6 {
            margin-bottom: 0.75rem;
            margin-top: 0.5rem;
        }
    </style>
@endpush
