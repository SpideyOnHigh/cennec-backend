<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StaffStoreRequest;
use App\Http\Requests\StaffUpdateRequest;
use App\Models\User;
use App\Services\GeneralService;
use App\Services\StaffService;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class StaffController extends Controller
{
    public $staffService;
    public $generalService;

    public function __construct(StaffService $staffService, GeneralService $generalService)
    {
        $this->staffService = $staffService;
        $this->generalService = $generalService;
    }

    public function index()
    {
        $user = auth()->user();
        if ($user->canany(["staff-management", "create-staff-management", "update-staff-management", "delete-staff-management"])) {
            return view('backend.staff.index');
        }
        return redirect()->back()->with('error', 'You do not have access to do this action. Please try again!');
    }

    public function fetch(Request $request)
    {
        $columns = ['users.id', 'users.name', 'users.contact', 'users.email', 'users.created_at', 'users.user_status'];

        $response = $this->staffService->fetch($request->all(), $columns);
        $user = auth()->user();
        $data = [];
        foreach ($response['data'] as $value) {

            $editAction = $user->can("update-staff-management") ? editBtn(route('staff.edit', $value->id)) : '';
            $editStatus = $user->can("update-staff-management") ? '<button class="toggle-status ml-2 px-4 py-2 rounded text-white ' .
                ($value->user_status == '1' ? 'bg-red-500' : 'bg-green-500') . '" ' .
                'data-id="' . $value->id . '" data-status="' . $value->user_status . '">' .
                ($value->user_status == '1' ? 'Block' : 'Activate') .
                '</button>' .
                '</div>' : '';
            $deleteAction = $user->can("delete-staff-management") ? deleteBtn(route('staff.destroy', $value->id)) : '';

            $btn = '<div class="flex">' . $editAction . ' ' . $deleteAction . ' ' . $editStatus;


            $data[] = [
                'name' => $value->name ?? '',
                'email' => $value->email ?? '',
                'contact' => $value->contact ?? '',
                'status' => $value->user_status == '1' ? 'Active' : 'Blocked',
                'role' => (isset($value->userRole) && $value->userRole->first()) ? $value->userRole->first()->name : '',
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

    public function create()
    {
        $user = auth()->user();
        if ($user->can("create-staff-management")) {
            $roles = Role::whereNotIn('name', ['App User', 'Super Admin'])->pluck('name', 'id')->toArray();
            return view('backend.staff.create', compact('roles'));
        }
        return redirect()->back()->with('error', 'You do not have access to do this action. Please try again!');
    }

    public function store(StaffStoreRequest $request)
    {
        $user = auth()->user();
        if ($user->can("create-staff-management")) {
            $this->staffService->store($request->all());
            return redirect()->route('staff.index')->with('success', 'Staff Created successfully & Password set link sent successfully!');
        }
        return redirect()->back()->with('error', 'You do not have access to do this action. Please try again!');
    }

    public function edit(User $staff)
    {
        $authUser = auth()->user();
        if ($authUser->can("update-staff-management") && !$staff->hasRole('Super Admin') && !$staff->hasRole('App User')) {
            if ($staff->userRole->first()->name === 'App User' || $staff->userRole->first()->name === 'Super Admin') {
                return redirect()->route('staff.index')->with('error', 'Staff not found!');
            }
            $roles = Role::whereNotIn('name', ['App User', 'Super Admin'])->pluck('name', 'id')->toArray();
            return view('backend.staff.edit', compact('roles', 'staff'));
        }
        return redirect()->back()->with('error', 'You do not have access to do this action. Please try again!');
    }

    public function update(StaffUpdateRequest $request, User $staff)
    {
        $authUser = auth()->user();
        if ($authUser->can("update-staff-management") && !$staff->hasRole('Super Admin') && !$staff->hasRole('App User')) {
            $data = $this->staffService->update($staff, $request->all());
            if ($data) {
                return redirect()->route('staff.index')->with('success', 'Saved successfully!');
            } else {
                return redirect()->route('staff.index')->with('error', 'Staff not found!');
            }
        }
        return redirect()->back()->with('error', 'You do not have access to do this action. Please try again!');
    }

    public function destroy(User $staff)
    {
        $authUser = auth()->user();
        if ($authUser->can("delete-staff-management") && !$staff->hasRole('Super Admin') && !$staff->hasRole('App User')) {
            $this->staffService->delete($staff);
            return redirect()->route('staff.index')->with('success', 'Deleted successfully!');
        }
        return redirect()->back()->with('error', 'You do not have access to do this action. Please try again!');
    }

    public function updateStatus(Request $request, $id)
    {
        $authUser = auth()->user();
        if ($authUser->can("update-staff-management")) {
            $currentUser = auth()->user();
            if ($currentUser->userRole->contains('name', 'App User')) {
                return redirect()->route('staff.index')->with('error', 'You do not have permission to update user status.');
            }
            $user = User::find($id);
            if ($user && !$user->hasRole('Super Admin')) {
                $status = $request->input('status');
                $user->user_status = $status;
                $user->save();
                return redirect()->route('staff.index')->with('success', 'Status updated successfully!');
            }
            return redirect()->route('staff.index')->with('error', 'Something went wrong, please try again!');
        }
        return redirect()->back()->with('error', 'You do not have access to do this action. Please try again!');
    }
}
