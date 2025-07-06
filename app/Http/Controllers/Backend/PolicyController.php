<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdatePolicyRequest;
use App\Models\Policy;
use Illuminate\Http\Request;
use App\Services\PolicyService;

class PolicyController extends Controller
{
    protected $policyService;

    public function __construct(PolicyService $policyService)
    {
        $this->policyService = $policyService;
    }

    public function index()
    {
        $user = auth()->user();
        if ($user->can("view-policy-page")) {
            return view('backend.policy-page.index');
        }
        return redirect()->back()->with('error', 'You do not have access to do this action. Please try again!');
    }

    public function fetch(Request $request)
    {
        $user = auth()->user();
        if ($user->can("view-policy-page")) {
            $columns = ['id', 'title', 'slug', 'content', 'policies_status', 'created_at',];

            $response = $this->policyService->fetch($request->all(), $columns);

            $formattedData = [];

            foreach ($response['data'] as $value) {

                $editAction = $user->can("update-policy-page") ? editBtn(route('policy-page.edit', $value->id)) : '';
                // $deleteAction = $user->can("delete-policy-page") ? deleteBtn(route('policy-page.destroy', $value->id)) : '';

                $btn = '<div class="flex">' . $editAction;

                $truncatedContent = strlen($value->content) > 30 ? substr($value->content, 0, 30) . '...' : $value->content;
                $formattedData[] = [
                    'id' => $value->id,
                    'title' => $value->title,
                    // 'slug' => $value->slug,
                    'content' => $truncatedContent,
                    'policies_status' => $value->policies_status === '1' ? 'Active' : 'Inactive',
                    'created_at' => $value->created_at ? $value->created_at->format('m/d/Y') : '',
                    'actions' => $btn,
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

    public function edit(Policy $policy)
    {
        $user = auth()->user();
        if ($user->can("update-policy-page")) {
            return view('backend.policy-page.edit', compact('policy'));
        }
        return redirect()->back()->with('error', 'You do not have access to do this action. Please try again!');
    }

    public function update(UpdatePolicyRequest $request, Policy $policy)
    {
        $user = auth()->user();
        if ($user->can("update-policy-page")) {
            $this->policyService->update($policy, $request->all(), $policy->id);
            return redirect()->route('policy-page.index')->with('success', 'Saved successfully!');
        }
        return redirect()->back()->with('error', 'You do not have access to do this action. Please try again!');
    }

    public function destroy(Policy $policy)
    {
        $user = auth()->user();
        if ($user->can("delete-policy-page")) {
            $this->policyService->delete($policy);
            return redirect()->route('policy-page.index')->with('success', 'Deleted successfully!');
        }
        return redirect()->back()->with('error', 'You do not have access to do this action. Please try again!');
    }
}
