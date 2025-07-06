<?php

namespace App\Services;

use App\Models\Policy;

class PolicyService
{
    public function getAllPages()
    {
        return Policy::all();
    }

    public function fetch($requestData, $columns)
    {
        $query = Policy::select($columns);

        if (!empty($requestData['search']['value'])) {
            $searchValue = $requestData['search']['value'];
            $query->where(function ($q) use ($searchValue) {
                $q->where('title', 'LIKE', '%' . $searchValue . '%')
                    // ->orWhere('slug', 'LIKE', '%' . $searchValue . '%')
                    ->orWhere('content', 'LIKE', '%' . $searchValue . '%')
                    ->orWhere('created_at', 'LIKE', '%' . $searchValue . '%');
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

    public function update(Policy $policy, $requestData)
    {
        $data = [
            'title' => $requestData['title'] ?? '',
            'slug' => $requestData['slug'] ?? '',
            'content' => $requestData['content'] ?? '',
            'policies_status' => $requestData['policies_status'] ?? '0',
        ];
        $policy->update($data);
        return $policy;
    }

    public function delete(Policy $policy)
    {
        $policy = Policy::find($policy->id);
        return $policy->delete();
    }
}
