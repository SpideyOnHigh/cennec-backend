<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\InvitationCodeStoreRequest;
use App\Models\User;
use App\Services\InvitationCodeService;
use Illuminate\Http\Request;

class InvitationCodeController extends Controller
{
    public $invitationCodeService;

    public function __construct(InvitationCodeService $invitationCodeService)
    {
        $this->invitationCodeService = $invitationCodeService;
    }

    public function index()
    {
        $user = auth()->user();
        if ($user->canany(["view-invitation-code", "create-invitation-code"])) {
            return view('backend.invitation-codes.index');
        }
        return redirect()->back()->with('error', 'You do not have access to do this action. Please try again!');
    }

    public function fetch(Request $request)
    {
        $user = auth()->user();
        if ($user->can("view-invitation-code")) {
            $columns = ['users.username', 'users.email', 'invitation_code_masters.created_at', 'invitation_code_masters.id', 'invitation_code_masters.comment', 'invitation_code_masters.code', 'invitation_code_masters.expiration_date', 'invitation_code_masters.max_user_allow'];
            $response = $this->invitationCodeService->fetch($request->all(), $columns);
            $formattedData = [];

            foreach ($response['data'] as $value) {
                $usedCode = User::where('invitation_code_id', $value->id)->count();

                $formattedData[] = [
                    'sponsor_id' => $value->username ?? '--',
                    'email' => $value->email ?? '--',
                    'code' => $value->code,
                    'expires' => $value->expiration_date ?? '--',
                    'total' => $value->max_user_allow ?? '--',
                    'used' => $usedCode ?? '--',
                    'description' => $value->comment ?? '--',
                    'created_at' => $value->created_at ? $value->created_at->format('m/d/Y') : '',
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
        $user = auth()->user();
        if ($user->can("create-invitation-code")) {
            $sponsorIds = $this->invitationCodeService->allSponsors();
            return view('backend.invitation-codes.create', compact('sponsorIds'));
        }
        return redirect()->back()->with('error', 'You do not have access to do this action. Please try again!');
    }

    public function store(InvitationCodeStoreRequest $request)
    {
        $user = auth()->user();
        if ($user->can("create-invitation-code")) {
            $this->invitationCodeService->store($request->all());
            return redirect()->route('invitation-codes.index')->with('success', 'Saved successfully!');
        }
        return redirect()->back()->with('error', 'You do not have access to do this action. Please try again!');
    }
}
