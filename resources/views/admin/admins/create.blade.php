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
                        <i class="las la-user-plus"></i> @lang('Add New Admin')
                    </h5>
                    <a href="{{ route('admin.admins.index') }}" class="btn btn-sm btn-outline--primary">
                        <i class="las la-arrow-left"></i> @lang('Back to List')
                    </a>
                </div>
                <form action="{{ route('admin.admins.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Name') <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="name" value="{{ old('name') }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Username') <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="username" value="{{ old('username') }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Email') <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" name="email" value="{{ old('email') }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Role') <span class="text-danger">*</span></label>
                                    <select class="form-control" name="role" required>
                                        <option value="">@lang('Select Role')</option>
                                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>@lang('Admin')</option>
                                        <option value="manager" {{ old('role') == 'manager' ? 'selected' : '' }}>@lang('Manager')</option>
                                        <option value="viewer" {{ old('role') == 'viewer' ? 'selected' : '' }}>@lang('Viewer')</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Password') <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control" name="password" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Confirm Password') <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control" name="password_confirmation" required>
                                </div>
                            </div>
                        </div>

                        @if(Schema::hasColumn('admins', 'status'))
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>@lang('Status') <span class="text-danger">*</span></label>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="status" value="1" {{ old('status', 1) ? 'checked' : '' }}>
                                            <label class="form-check-label">
                                                @lang('Active')
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if(Schema::hasColumn('admins', 'permissions'))
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
                                                            <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $permission }}" {{ in_array($permission, old('permissions', [])) ? 'checked' : '' }}>
                                                            <label class="form-check-label">{{ $description }}</label>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn--primary">@lang('Create Admin')</button>
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