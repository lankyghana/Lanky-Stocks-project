<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;

class AdminManagementController extends Controller
{
    public function index()
    {
        $pageTitle = "Manage Admins";
        $admins = Admin::where('id', '!=', auth()->guard('admin')->id())
                      ->orderBy('created_at', 'desc')
                      ->paginate(getPaginate());
        
        return view('admin.admins.index', compact('pageTitle', 'admins'));
    }

    public function create()
    {
        $pageTitle = "Add New Admin";
        $permissions = $this->getAvailablePermissions();
        $roles = $this->getAvailableRoles();
        
        return view('admin.admins.create', compact('pageTitle', 'permissions', 'roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:admins,username',
            'email' => 'required|string|email|max:255|unique:admins,email',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|in:super_admin,admin,manager,viewer',
            'permissions' => 'nullable|array',
            'permissions.*' => 'string',
            'status' => 'nullable|in:1,0'
        ]);

        $admin = new Admin();
        $admin->name = $request->name;
        $admin->username = $request->username;
        $admin->email = $request->email;
        $admin->password = Hash::make($request->password);
        $admin->role = $request->role;
        
        // Only set these fields if columns exist
        try {
            if (Schema::hasColumn('admins', 'permissions')) {
                $admin->permissions = $request->permissions ?? [];
            }
        } catch (Exception $e) {
            // Permissions column doesn't exist, skip
        }
        
        try {
            if (Schema::hasColumn('admins', 'status')) {
                $admin->status = $request->has('status') ? 1 : 0;
            }
        } catch (Exception $e) {
            // Status column doesn't exist, skip
        }
        
        $admin->save();

        $notify[] = ['success', 'Admin created successfully'];
        return redirect()->route('admin.admins.index')->withNotify($notify);
    }

    public function edit($id)
    {
        $pageTitle = "Edit Admin";
        $admin = Admin::findOrFail($id);
        
        // Prevent editing super admin if current user is not super admin
        if ($admin->role === 'super_admin' && auth()->guard('admin')->user()->role !== 'super_admin') {
            $notify[] = ['error', 'You cannot edit super admin account'];
            return redirect()->back()->withNotify($notify);
        }
        
        $permissions = $this->getAvailablePermissions();
        $roles = $this->getAvailableRoles();
        
        return view('admin.admins.edit', compact('pageTitle', 'admin', 'permissions', 'roles'));
    }

    public function update(Request $request, $id)
    {
        $admin = Admin::findOrFail($id);
        
        // Prevent editing super admin if current user is not super admin
        if ($admin->role === 'super_admin' && auth()->guard('admin')->user()->role !== 'super_admin') {
            $notify[] = ['error', 'You cannot edit super admin account'];
            return redirect()->back()->withNotify($notify);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'username' => ['required', 'string', 'max:255', Rule::unique('admins')->ignore($admin->id)],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('admins')->ignore($admin->id)],
            'password' => 'nullable|string|min:6|confirmed',
            'role' => 'required|in:super_admin,admin,manager,viewer',
            'permissions' => 'nullable|array',
            'permissions.*' => 'string',
            'status' => 'nullable|in:1,0'
        ]);

        $admin->name = $request->name;
        $admin->username = $request->username;
        $admin->email = $request->email;
        
        if ($request->filled('password')) {
            $admin->password = Hash::make($request->password);
        }
        
        $admin->role = $request->role;
        
        // Only set these fields if columns exist
        try {
            if (Schema::hasColumn('admins', 'permissions')) {
                $admin->permissions = $request->permissions ?? [];
            }
        } catch (Exception $e) {
            // Permissions column doesn't exist, skip
        }
        
        try {
            if (Schema::hasColumn('admins', 'status')) {
                $admin->status = $request->has('status') ? 1 : 0;
            }
        } catch (Exception $e) {
            // Status column doesn't exist, skip
        }
        
        $admin->save();

        $notify[] = ['success', 'Admin updated successfully'];
        return redirect()->route('admin.admins.index')->withNotify($notify);
    }

    public function destroy($id)
    {
        $admin = Admin::findOrFail($id);
        
        // Prevent deleting super admin or self
        if ($admin->role === 'super_admin' || $admin->id === auth()->guard('admin')->id()) {
            $notify[] = ['error', 'You cannot delete this admin account'];
            return redirect()->back()->withNotify($notify);
        }
        
        $admin->delete();
        
        $notify[] = ['success', 'Admin deleted successfully'];
        return redirect()->back()->withNotify($notify);
    }

    public function status($id)
    {
        $admin = Admin::findOrFail($id);
        
        // Prevent changing status of super admin or self
        if ($admin->role === 'super_admin' || $admin->id === auth()->guard('admin')->id()) {
            $notify[] = ['error', 'You cannot change status of this admin account'];
            return redirect()->back()->withNotify($notify);
        }
        
        $admin->status = $admin->status ? 0 : 1;
        $admin->save();
        
        $message = $admin->status ? 'Admin enabled successfully' : 'Admin disabled successfully';
        $notify[] = ['success', $message];
        return redirect()->back()->withNotify($notify);
    }

    private function getAvailablePermissions()
    {
        return [
            'User Management' => [
                'users.view' => 'View Users',
                'users.manage' => 'Manage Users',
                'users.create' => 'Create Users',
                'users.edit' => 'Edit Users',
                'users.delete' => 'Delete Users',
            ],
            'Service Management' => [
                'services.view' => 'View Services',
                'services.manage' => 'Manage Services',
                'services.create' => 'Create Services',
                'services.edit' => 'Edit Services',
                'services.delete' => 'Delete Services',
            ],
            'Order Management' => [
                'orders.view' => 'View Orders',
                'orders.manage' => 'Manage Orders',
                'orders.create' => 'Create Orders',
                'orders.edit' => 'Edit Orders',
                'orders.delete' => 'Delete Orders',
            ],
            'Financial Management' => [
                'deposits.view' => 'View Deposits',
                'deposits.manage' => 'Manage Deposits',
                'withdrawals.view' => 'View Withdrawals',
                'withdrawals.manage' => 'Manage Withdrawals',
                'transactions.view' => 'View Transactions',
            ],
            'System Management' => [
                'system.view' => 'View System Info',
                'system.manage' => 'Manage System',
                'settings.view' => 'View Settings',
                'settings.manage' => 'Manage Settings',
            ],
            'Reports & Analytics' => [
                'reports.view' => 'View Reports',
                'reports.generate' => 'Generate Reports',
                'reports.export' => 'Export Reports',
            ],
            'Admin Management' => [
                'admins.view' => 'View Admins',
                'admins.manage' => 'Manage Admins',
                'admins.create' => 'Create Admins',
                'admins.edit' => 'Edit Admins',
                'admins.delete' => 'Delete Admins',
            ],
            'Category Management' => [
                'categories.view' => 'View Categories',
                'categories.manage' => 'Manage Categories',
                'categories.create' => 'Create Categories',
                'categories.edit' => 'Edit Categories',
                'categories.delete' => 'Delete Categories',
            ],
            'Support Management' => [
                'tickets.view' => 'View Support Tickets',
                'tickets.manage' => 'Manage Support Tickets',
                'tickets.reply' => 'Reply to Tickets',
            ],
            'Provider Management' => [
                'providers.view' => 'View API Providers',
                'providers.manage' => 'Manage API Providers',
                'providers.create' => 'Create API Providers',
                'providers.edit' => 'Edit API Providers',
                'providers.delete' => 'Delete API Providers',
            ]
        ];
    }

    private function getAvailableRoles()
    {
        return [
            'super_admin' => 'Super Admin (Full Access)',
            'admin' => 'Admin (Custom Permissions)',
            'manager' => 'Manager (Limited Access)',
            'viewer' => 'Viewer (Read Only)'
        ];
    }
}
