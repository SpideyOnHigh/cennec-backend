<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryInterestStoreRequest;
use App\Models\InterestMaster;
use App\Services\CategoryInterestService;
use Illuminate\Http\Request;

class CategoryInterestController extends Controller
{
    protected $categoryInterestService;

    public function __construct(CategoryInterestService $categoryInterestService)
    {
        $this->categoryInterestService = $categoryInterestService;
    }

    public function index()
    {
        $authUser = auth()->user();
        if ($authUser->can("list-interests")) {
            return view('backend.category-interest.index');
        }
        return redirect()->back()->with('error', 'You do not have access to do this action. Please try again!');
    }

    public function fetch(Request $request)
    {
        $authUser = auth()->user();
        if ($authUser->can("list-interests")) {
            $columns = [
                'interest_masters.interest_name',
                'interest_masters.interest_color',
                'interest_masters.interest_category_id',
                'interest_masters.description_link',
                'interest_masters.sponsor_id',
                'interest_masters.created_at',
                'interest_masters.id',
            ];

            $response = $this->categoryInterestService->fetch($request->all(), $columns);
            $formattedData = [];

            foreach ($response['data'] as $value) {
                $editAction = $authUser->can("update-interests") ? editBtn(route("interest.edit", $value->id)) : '';
                $deleteAction = $authUser->can("delete-interests") ? deleteBtn(route('interest.destroy', $value->id)) : '';

                $descriptionLink = $value->description_link ? '<a href="' . $value->description_link . '" class="bg-blue-100" target="_blank">' . $value->description_link . '</a>' : '--';
                $colorBox = $value->interest_color ? '<div style="width: 50px; height: 20px; background-color: ' . $value->interest_color . '; border: 1px solid #000;"></div>' : '--';

                $btn = '<div class="flex">' . $editAction . ' ' . $deleteAction;

                $formattedData[] = [
                    'name' => $value->interest_name ?? '--',
                    'color' => $colorBox,
                    'category' => $value->interestCategory->interest_category_name ?? '--',
                    'description_link' => $descriptionLink,
                    'sponsor_id' => $value->sponsorName->username ?? '--',
                    'created_at' => $value->created_at ? $value->created_at->format('m/d/Y') : '--',
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
        $categories = $this->categoryInterestService->allCategory();
        $sponsors = $this->categoryInterestService->allSponsors();
        if ($authUser->can("create-interests")) {
            return view('backend.category-interest.create', compact('categories', 'sponsors'));
        }
        return redirect()->back()->with('error', 'You do not have access to do this action. Please try again!');
    }

    public function store(CategoryInterestStoreRequest $request)
    {
        $authUser = auth()->user();
        if ($authUser->can("create-interests")) {
            $this->categoryInterestService->store($request->all());
            return redirect()->route('interest.index')->with('success', 'Saved successfully!');
        }
        return redirect()->back()->with('error', 'You do not have access to do this action. Please try again!');
    }

    public function edit(InterestMaster $interest)
    {
        $authUser = auth()->user();
        if ($authUser->can("update-interests")) {
            $categories = $this->categoryInterestService->allCategory();
            $sponsors = $this->categoryInterestService->allSponsors();
            return view('backend.category-interest.edit', compact('categories', 'sponsors', 'interest'));
        }
        return redirect()->back()->with('error', 'You do not have access to do this action. Please try again!');
    }

    public function update(CategoryInterestStoreRequest $request, InterestMaster $interest)
    {
        $authUser = auth()->user();
        if ($authUser->can("update-interests")) {
            $this->categoryInterestService->update($interest, $request->all());
            return redirect()->route('interest.index')->with('success', 'Saved successfully!');
        }
        return redirect()->back()->with('error', 'You do not have access to do this action. Please try again!');
    }

    public function destroy(InterestMaster $interest)
    {
        $authUser = auth()->user();
        if ($authUser->can("delete-interests")) {
            $this->categoryInterestService->delete($interest);
            return redirect()->route('interest.index')->with('success', 'Deleted successfully!');
        }
        return redirect()->back()->with('error', 'You do not have access to do this action. Please try again!');
    }
}
