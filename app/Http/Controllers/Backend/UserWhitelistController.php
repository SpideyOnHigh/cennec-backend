<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryInterestStoreRequest;
use App\Http\Requests\WhiteListUserStoreRequest;
use App\Models\UserWhiteList;
use App\Services\UserWhitelistService;
use Illuminate\Http\Request;

class UserWhitelistController extends Controller
{
    protected $userWhitelistService;

    public function __construct(UserWhitelistService $userWhitelistService)
    {
        $this->userWhitelistService = $userWhitelistService;
    }

    public function index()
    {
        $authUser = auth()->user();
        if ($authUser->can("user-whitelist")) {
            return view('backend.user-whitelist.index');
        }
        return redirect()->back()->with('error', 'You do not have access to do this action. Please try again!');
    }

    public function fetchDomains(Request $request)
    {
        $authUser = auth()->user();
        if ($authUser->can("list-user-whitelist")) {
            $columns = [
                'id',
                'first_name',
                'last_name',
                'email',
            ];

            $response = $this->userWhitelistService->fetch($request->all(), $columns, $domain = '1');
            $formattedData = [];

            foreach ($response['data'] as $value) {
                $editAction = $authUser->can("update-user-whitelist") ? editBtn(route("user-whitelist.edit", $value->id)) : '';
                $deleteAction = $authUser->can("delete-user-whitelist") ? deleteBtn(route('user-whitelist.destroy', $value->id)) : '';

                $btn = '<div class="flex">' . $editAction . ' ' . $deleteAction;
                $fullName = $value->first_name . ' ' . $value->last_name;
                $formattedData[] = [
                    'name' => $fullName ?? '--',
                    'email' => $value->email ?? '--',
                    'actions' => $btn . '</div>',
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

    public function fetchEmails(Request $request)
    {
        $authUser = auth()->user();
        if ($authUser->can("list-user-whitelist")) {
            $columns = [
                'id',
                'first_name',
                'last_name',
                'email',
                'created_at',
            ];

            $response = $this->userWhitelistService->fetch($request->all(), $columns, $domain = '0');
            $formattedData = [];

            foreach ($response['data'] as $value) {
                $editAction = $authUser->can("update-user-whitelist") ? editBtn(route("user-whitelist.edit", $value->id)) : '';
                $deleteAction = $authUser->can("delete-user-whitelist") ? deleteBtn(route('user-whitelist.destroy', $value->id)) : '';

                $btn = '<div class="flex">' . $editAction . ' ' . $deleteAction;
                $formattedData[] = [
                    'first_name' => $value->first_name ?? '--',
                    'last_name' => $value->last_name ?? '--',
                    'email' => $value->email ?? '--',
                    'created_at' => $value->created_at->format('d/m/Y') ?? '--',
                    'actions' => $btn . '</div>',
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
        $authUser = auth()->user();
        if ($authUser->can("create-user-whitelist")) {
            return view('backend.user-whitelist.create');
        }
        return redirect()->back()->with('error', 'You do not have access to do this action. Please try again!');
    }

    public function store(WhiteListUserStoreRequest $request)
    {
        $authUser = auth()->user();
        if ($authUser->can("create-user-whitelist")) {
            $this->userWhitelistService->store($request->all());
            return redirect()->route('user-whitelist.index')->with('success', 'Saved successfully!');
        }
        return redirect()->back()->with('error', 'You do not have access to do this action. Please try again!');
    }

    public function edit(UserWhiteList $userWhitelist)
    {
        $authUser = auth()->user();
        if ($authUser->can("update-user-whitelist")) {
            return view('backend.user-whitelist.edit', compact('userWhitelist'));
        }
        return redirect()->back()->with('error', 'You do not have access to do this action. Please try again!');
    }

    public function update(WhiteListUserStoreRequest $request, UserWhiteList $userWhitelist)
    {
        $authUser = auth()->user();
        if ($authUser->can("update-user-whitelist")) {
            $this->userWhitelistService->update($userWhitelist, $request->all());
            return redirect()->route('user-whitelist.index')->with('success', 'Saved successfully!');
        }
        return redirect()->back()->with('error', 'You do not have access to do this action. Please try again!');
    }

    public function destroy(UserWhiteList $userWhitelist)
    {
        $authUser = auth()->user();
        if ($authUser->can("delete-user-whitelist")) {
            $this->userWhitelistService->delete($userWhitelist);
            return redirect()->route('user-whitelist.index')->with('success', 'Deleted successfully!');
        }
        return redirect()->back()->with('error', 'You do not have access to do this action. Please try again!');
    }
}
