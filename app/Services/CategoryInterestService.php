<?php

namespace App\Services;

use App\Models\InterestCategoryMaster;
use App\Models\InterestMaster;
use App\Models\User;
use Illuminate\Support\Facades\DB;


class CategoryInterestService
{
    public function fetch($requestData, $columns)
    {
        $query = InterestMaster::leftjoin('interest_category_masters', 'interest_category_masters.id', 'interest_masters.interest_category_id')->leftjoin('users', 'users.id', 'interest_masters.sponsor_id')->select($columns);

        if (!empty($requestData['search']['value'])) {
            $searchValue = $requestData['search']['value'];
            $query->where(function ($q) use ($searchValue) {
                $q->where('interest_masters.interest_name', 'LIKE', '%' . $searchValue . '%')
                    ->orWhere('interest_masters.id', 'LIKE', '%' . $searchValue . '%')
                    ->orWhere('interest_masters.interest_color', 'LIKE', '%' . $searchValue . '%')
                    ->orWhere('interest_category_masters.interest_category_name', 'LIKE', '%' . $searchValue . '%')
                    ->orWhere('users.username', 'LIKE', '%' . $searchValue . '%')
                    ->orWhere('interest_masters.description_link', 'LIKE', '%' . $searchValue . '%');
            });
        }

        $total = $query->count();

        $orderByColumn = $columns[$requestData['order'][0]['column']];
        $orderDirection = $requestData['order'][0]['dir'];
        $query->orderBy($orderByColumn, $orderDirection);

        $start = $requestData['start'];
        $length = $requestData['length'];
        $query->skip($start)->take($length);

        $filteredCount = $query->count();

        $data = $query->get();

        return [
            'data' => $data,
            'total' => $total,
            'filteredCount' => $filteredCount,
        ];
    }

    public function store($requestData)
    {
        DB::beginTransaction();
        try {
            $data = [
                'interest_name' => $requestData['interest_name'] ?? '',
                'interest_color' => $requestData['interest_color'] ?? '',
                'interest_category_id' => $requestData['interest_category_id'] ?? '',
                'description_link' => $requestData['description_link'] ?? '',
                // 'sponsor_id' => $requestData['sponsor_id'] ?? '',
            ];
            InterestMaster::create($data);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
        }
    }

    public function update(InterestMaster $interest, $requestData)
    {
        DB::beginTransaction();
        try {
            $data = [
                'interest_name' => $requestData['interest_name'] ?? '',
                'interest_color' => $requestData['interest_color'] ?? '',
                'interest_category_id' => $requestData['interest_category_id'] ?? '',
                'description_link' => $requestData['description_link'] ?? '',
                // 'sponsor_id' => $requestData['sponsor_id'] ?? '',
            ];
            $interest->update($data);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    public function delete(InterestMaster $interest)
    {
        $interest = InterestMaster::find($interest->id);
        if ($interest) {
            return $interest->delete();
        }
        return;
    }

    public function allCategory()
    {
        $data = InterestCategoryMaster::pluck('interest_category_name', 'id')->toArray();
        return $data;
    }

    public function allSponsors()
    {
        $data = User::with('userRole')->whereDoesntHave('userRole', function ($q) {
            $q->where('name', 'Super Admin');
        })->pluck('username', 'id')->toArray();
        return $data;
    }
}
