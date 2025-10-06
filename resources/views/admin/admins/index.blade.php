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
                        <i class="las la-users-cog"></i> @lang('Manage Admins')
                    </h5>
                    @if(auth('admin')->user()->hasPermission('admins.create'))
                        <a href="{{ route('admin.admins.create') }}" class="btn btn-sm btn-primary">
                            <i class="las la-plus"></i> @lang('Add New Admin')
                        </a>
                    @endif
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive--lg table-responsive">
                        <table class="table table--light">
                            <thead>
                                <tr>
                                    <th>@lang('Admin')</th>
                                    <th>@lang('Username')</th>
                                    <th>@lang('Email')</th>
                                    <th>@lang('Role')</th>
                                    @if(Schema::hasColumn('admins', 'permissions'))
                                        <th>@lang('Permissions')</th>
                                    @endif
                                    <th>@lang('Status')</th>
                                    <th>@lang('Last Login')</th>
                                    <th>@lang('Actions')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($admins as $admin)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-preview">
                                                    <div class="profilePicPreview" style="width: 40px; height: 40px; background-image: url('{{ asset('assets/images/default.png') }}')"></div>
                                                </div>
                                                <div class="ms-3">
                                                    <h6 class="mb-0">{{ $admin->name }}</h6>
                                                    <small class="text-muted">{{ $admin->created_at->format('M d, Y') }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="fw-bold">{{ $admin->username }}</span>
                                        </td>
                                        <td>{{ $admin->email }}</td>
                                        <td>
                                            @if($admin->role === 'super_admin')
                                                <span class="badge badge--primary">@lang('Super Admin')</span>
                                            @elseif($admin->role === 'admin')
                                                <span class="badge badge--info">@lang('Admin')</span>
                                            @elseif($admin->role === 'manager')
                                                <span class="badge badge--warning">@lang('Manager')</span>
                                            @else
                                                <span class="badge badge--secondary">@lang('Viewer')</span>
                                            @endif
                                        </td>
                                        @if(Schema::hasColumn('admins', 'permissions'))
                                            <td>
                                                @if($admin->role === 'super_admin')
                                                    <span class="badge badge--success">@lang('All Permissions')</span>
                                                @else
                                                    @php
                                                        $permissionCount = count($admin->permissions ?? []);
                                                    @endphp
                                                    @if($permissionCount > 0)
                                                        <span class="badge badge--info">{{ $permissionCount }} @lang('permissions')</span>
                                                    @else
                                                        <span class="badge badge--secondary">@lang('No permissions')</span>
                                                    @endif
                                                @endif
                                            </td>
                                        @endif
                                        <td>
                                            @if(Schema::hasColumn('admins', 'status'))
                                                @if($admin->status)
                                                    <span class="badge badge--success">@lang('Active')</span>
                                                @else
                                                    <span class="badge badge--danger">@lang('Inactive')</span>
                                                @endif
                                            @else
                                                <span class="badge badge--success">@lang('Active')</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="text-muted">{{ $admin->updated_at->diffForHumans() }}</span>
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-outline--primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                    <i class="las la-ellipsis-v"></i> @lang('Action')
                                                </button>
                                                <ul class="dropdown-menu">
                                                    @if(auth('admin')->user()->hasPermission('admins.edit'))
                                                        <li>
                                                            <a class="dropdown-item" href="{{ route('admin.admins.edit', $admin->id) }}">
                                                                <i class="las la-edit"></i> @lang('Edit')
                                                            </a>
                                                        </li>
                                                    @endif
                                                    @if($admin->role !== 'super_admin')
                                                        @if(Schema::hasColumn('admins', 'status') && auth('admin')->user()->hasPermission('admins.manage'))
                                                            <li>
                                                                <button class="dropdown-item confirmationBtn" 
                                                                        data-action="{{ route('admin.admins.status', $admin->id) }}"
                                                                        data-question="@lang('Are you sure to change the status of this admin?')">
                                                                    <i class="las la-eye{{ $admin->status ? '-slash' : '' }}"></i> 
                                                                    {{ $admin->status ? __('Disable') : __('Enable') }}
                                                                </button>
                                                            </li>
                                                        @endif
                                                        @if(auth('admin')->user()->hasPermission('admins.delete'))
                                                            <li>
                                                                <button class="dropdown-item confirmationBtn text-danger" 
                                                                        data-action="{{ route('admin.admins.destroy', $admin->id) }}"
                                                                        data-question="@lang('Are you sure to delete this admin?')">
                                                                    <i class="las la-trash"></i> @lang('Delete')
                                                                </button>
                                                            </li>
                                                        @endif
                                                    @endif
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-muted text-center" colspan="100%">{{ __('No admins found') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($admins->hasPages())
                    <div class="card-footer">
                        {{ paginateLinks($admins) }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Confirmation Modal --}}
    <div id="confirmationModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Confirmation Alert!')</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <form action="" method="POST">
                    @csrf
                    {{-- method spoofing will be set dynamically by script --}}
                    <div class="modal-body">
                        <p class="question"></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn--dark" data-bs-dismiss="modal">@lang('No')</button>
                        <button type="submit" class="btn btn--primary">@lang('Yes')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        (function($) {
            'use strict';
            
            $('.confirmationBtn').on('click', function() {
                var modal = $('#confirmationModal');
                var action = $(this).data('action');
                var question = $(this).data('question');
                modal.find('.question').text(question);
                var form = modal.find('form');
                form.attr('action', action);
                // Reset previous spoofed method
                form.find('input[name="_method"]').remove();
                // Use DELETE only for destroy routes; POST for status and others
                if(action.includes('/destroy/')){
                    form.append('<input type="hidden" name="_method" value="DELETE">');
                }
                modal.modal('show');
            });
        })(jQuery);
    </script>
@endpush
