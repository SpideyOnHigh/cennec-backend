<?php

namespace App\Services;

use App\Models\UserWhiteList;
use Illuminate\Support\Facades\DB;

class UserWhitelistService
{
    public function fetch($requestData, $columns, $domain)
    {
        $query = UserWhiteList::where('domain', $domain)->select($columns);

        if (!empty($requestData['search']['value'])) {
            $searchValue = $requestData['search']['value'];
            $query->where(function ($q) use ($searchValue) {
                $q->where('first_name', 'LIKE', '%' . $searchValue . '%')
                    ->orWhere('last_name', 'LIKE', '%' . $searchValue . '%')
                    ->orWhere('email', 'LIKE', '%' . $searchValue . '%');
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
                'first_name' => $requestData['first_name'] ?? '',
                'last_name' => $requestData['last_name'] ?? '',
                'email' => $requestData['email'] ?? '',
                'domain' => $requestData['is_domain'] ?? '',
            ];
            UserWhiteList::create($data);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
        }
    }

    public function update(UserWhiteList $userWhitelist, $requestData)
    {
        DB::beginTransaction();
        try {
            $data = [
                'first_name' => $requestData['first_name'] ?? '',
                'last_name' => $requestData['last_name'] ?? '',
                'email' => $requestData['email'] ?? '',
                'domain' => $requestData['is_domain'] ?? '',
            ];
            $userWhitelist->update($data);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    public function delete(UserWhiteList $userWhitelist)
    {
        $userWhitelist = UserWhiteList::find($userWhitelist->id);
        if ($userWhitelist) {
            return $userWhitelist->delete();
        }
        return;
    }
}
