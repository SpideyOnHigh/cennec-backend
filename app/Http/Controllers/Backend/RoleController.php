<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\RoleRequest;
use App\Http\Requests\RoleUpdateRequest;
use App\Services\GeneralService;
use App\Services\RoleService;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public $roleService;
    public $generalService;

    public function __construct(RoleService $roleService, GeneralService $generalService)
    {
        $this->roleService = $roleService;
        $this->generalService = $generalService;
    }

    public function index()
    {
        $user = auth()->user();
        if ($user->canany(["list-roles", "delete-roles", "update-roles", "create-roles"])) {
            return view('backend.role.index', compact('user'));
        }
        return redirect()->back()->with('error', 'You do not have access to do this action. Please try again!');
    }

    public function fetch(Request $request)
    {
        $user = auth()->user();
        if ($user->can("list-roles")) {
            $columns = ['roles.id', 'roles.name', 'roles.description', 'roles.created_at'];

            $response = $this->roleService->fetch($request->all(), $columns);

            $data = [];
            foreach ($response['data'] as $value) {
                $editAction = $user->can("update-roles") ? editBtn(route('role.edit', $value->id)) : '';
                $deleteAction = $user->can("delete-roles") ? deleteBtn(route('role.destroy', $value->id)) : '';
                $btn = '<div class="flex">' . $editAction . ' ' . $deleteAction;
                if ($value->name === 'Admin') {
                    $btn = '<div class="flex">' . $editAction;
                }
                $data[] = [
                    'id' => $value->id,
                    'name' => $value->name ? $value->name : '',
                    'description' => $value->description ?? '',
                    'created_date' => date('Y-m-d H:i:s', strtotime($value->created_at)),
                    'actions' => $btn,

                ];
            }
            return response()->json([
                'draw' => intval($request->input('draw')),
                'recordsTotal' => $response['total'],
                'recordsFiltered' => $response['total'],
                'data' => $data,
            ]);
        }
        return redirect()->back()->with('error', 'You do not have access to do this action. Please try again!');
    }

    public function create()
    {
        $user = auth()->user();
        if ($user->can("create-roles")) {
            $permissions = $this->generalService->getPermissionList('0');
            return view('backend.role.create', compact('permissions'));
        }
        return redirect()->back()->with('error', 'You do not have access to do this action. Please try again!');
    }

    public function store(RoleRequest $request)
    {
        $user = auth()->user();
        if ($user->can("create-roles")) {
            $roleName = preg_replace('/\s+/', ' ', trim($request->name));
            $existingRole = Role::where('name', $roleName)->first();
            if ($existingRole) {
                $message = "Role with name '{$request->name}' already exists.";
                return redirect()->back()->withErrors(['name' => $message])->withInput();
            }
            $this->roleService->store($request->all());
            return redirect()->route('role.index')->with('success', 'Saved successfully!');
        }
        return redirect()->back()->with('error', 'You do not have access to do this action. Please try again!');
    }

    public function edit(Role $role)
    {
        $user = auth()->user();
        if ($user->can("update-roles") && $role->name != 'Super Admin') {
            $authUser = auth()->user();
            if ($authUser && $authUser->userRole->first()->name == 'Admin') {
                $exclude = ['App User', 'Super Admin'];
            } else if ($authUser && $authUser->userRole->first()->name == 'Super Admin') {
                $exclude = ['App User'];
            } else {
                $exclude = ['App User', 'Admin', 'Super Admin'];
            }
            if (in_array($role->name, $exclude)) {
                return redirect()->route('role.index')->with('error', 'You can\'t perform this action.');
            }
            $rolePermission = ! empty($role->permissions) ? $role->permissions->pluck('name')->toArray() : [];
            $permissions = $this->generalService->getPermissionList('0');
            return view('backend.role.edit', compact('role', 'permissions', 'rolePermission'));
        }
        return redirect()->back()->with('error', 'You do not have access to do this action. Please try again!');
    }

    public function update(RoleUpdateRequest $request, Role $role)
    {
        $user = auth()->user();
        if ($user->can("update-roles") && $role->name != 'Super Admin') {
            $roleName = preg_replace('/\s+/', ' ', trim($request->name));
            $existingRole = Role::where('name', $roleName)->whereNot('id', $role->id)->first();
            if ($existingRole) {
                $message = "Role with name '{$request->name}' already exists.";
                return redirect()->back()->withErrors(['name' => $message])->withInput();
            }
            $this->roleService->update($role, $request->all());
            return redirect()->route('role.index')->with('success', 'Saved successfully!');
        }
        return redirect()->back()->with('error', 'You do not have access to do this action. Please try again!');
    }

    public function destroy(Role $role)
    {
        $user = auth()->user();
        if ($user->can("delete-roles") && $role->name != 'Super Admin') {
            $exclude = config('enum.admin_role');
            if (in_array($role->name, $exclude)) {
                return redirect()->route('role.index')->with('error', 'You can\'t perform this action.');
            }
            $this->roleService->delete($role);
            return redirect()->route('role.index')->with('success', 'Deleted successfully!');
        }
        return redirect()->back()->with('error', 'You do not have access to do this action. Please try again!');
    }
}
