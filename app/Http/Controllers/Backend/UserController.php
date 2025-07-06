<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserUpdateRequest;
use App\Models\User;
use App\Models\UserProfileImage;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public $userService;
    public $generalService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
        // $this->generalService = $generalService;
    }

    public function index()
    {
        $user = auth()->user();
        if ($user->canany(["user-list", "user-update", "user-delete", "user-detail"])) {
            return view('backend.user.index');
        }
        return redirect()->back()->with('error', 'You do not have access to do this action. Please try again!');
    }

    public function fetch(Request $request)
    {
        $user = auth()->user();
        if ($user->can("user-list")) {
            $columns = ['users.id', 'users.username', 'users.created_at', 'users.email', 'user_details.dob', 'user_details.gender', 'users.user_status'];
            $response = $this->userService->fetch($request->all(), $columns);
            $user = auth()->user();
            $formattedData = [];
            $gender = config('enum.gender');
            foreach ($response['data'] as $value) {

                $editAction = $user->can("user-update") ? editBtn(route("users.edit", $value)) : '';
                $deleteAction = $user->can("user-delete") ? deleteBtn(route('users.destroy', $value->id)) : '';
                $viewAction = $user->can("user-detail") ? showBtn(route('users.show', $value->id)) : '';

                $btn = '<div class="flex">' . $viewAction . ' ' . $editAction . ' ' . $deleteAction;

                $formattedData[] = [
                    'id' => $value->id,
                    // 'name' => ucfirst($value->name) ?? '',
                    'username' => $value->username ?? '',
                    'joined' => $value->created_at ? $value->created_at->format('m/d/Y') : '',
                    'email' => $value->email ?? '',
                    'dob' => $value->dob ?? '',
                    'gender' => $value->gender ? $gender[$value->gender] : '',
                    'status' => $value->user_status == '1' ? 'Active' : 'Blocked',
                    'actions' => $btn . '<button type="submit" data-email="' . $value->email . '" data-id="' . $value->id . '" id="confirmResetPassword" class="btn btn-warning btn-sm ml-2 bg-black text-white reset-password">Reset Password</button>' .
                        '</div>',
                ];
            }
            return response()->json([
                'draw' => intval($request->input('draw')),
                'recordsTotal' => $response['total'],
                'recordsFiltered' => $response['total'],
                'data' => $formattedData,
            ]);
        }
        return redirect()->back()->with('error', 'You do not have access to do this action. Please try again!');
    }

    public function create()
    {
        // $roles = $this->generalService->getRoleList();
        return view('backend.user.create');
    }

    // public function store(OperatorRequest $request)
    // {
    //     $this->userService->store($request->all());
    //     return redirect()->route('operator.index')->with('success', 'Saved successfully!');
    // }

    public function edit(User $user)
    {
        $authUser = auth()->user();
        if ($authUser->can("user-update") && $user->hasRole('App User')) {
            $userDetail = $user->details;
            return view('backend.user.edit', compact('user', 'userDetail'));
        }
        return redirect()->back()->with('error', 'You do not have access to do this action. Please try again!');
    }

    public function show($id)
    {
        $user = User::find($id);
        $authUser = auth()->user();
        $userDefaultImage = UserProfileImage::where('user_id', $id)->where('is_default', 1)->first();
        $path = !empty($userDefaultImage->image_name) ? ($userDefaultImage->image_name) : NULL;
        $userDefaultImage = concatAppUrl($path);

        if ($authUser->can("user-detail") && $user->hasRole('App User')) {
            $user = User::with('details', 'invitationCode', 'userRole', 'firstProfileImage')->find($id);
            if (! $user) {
                return redirect()->route('users.index')->with('error', 'Unable to load user details. Please try again!');
            }
            return view('backend.user.show', compact('user', 'userDefaultImage'));
        }
        return redirect()->back()->with('error', 'You do not have access to do this action. Please try again!');
    }

    public function update(UserUpdateRequest $request, User $user)
    {
        $authUser = auth()->user();
        if ($authUser->can("user-update") && $user->hasRole('App User')) {
            $this->userService->update($user, $request->all(), $user->id);
            return redirect()->route('users.index')->with('success', 'Saved successfully!');
        }
        return redirect()->back()->with('error', 'You do not have access to do this action. Please try again!');
    }

    public function destroy(User $user)
    {
        $authUser = auth()->user();
        if ($authUser->can("user-delete") && $user->hasRole('App User')) {
            $this->userService->delete($user);
            return redirect()->route('users.index')->with('success', 'Deleted successfully!');
        }
        return redirect()->back()->with('error', 'You do not have access to do this action. Please try again!');
    }
}
