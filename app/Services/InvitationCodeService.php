<?php

namespace App\Services;

use App\Models\InvitationCodeMaster;
use App\Models\User;

class InvitationCodeService
{
    public function fetch($requestData, $columns)
    {
        $query = InvitationCodeMaster::leftJoin('users', 'users.id', 'invitation_code_masters.sponsor_id')
            ->select('invitation_code_masters.*', 'users.username', 'users.email');

        if (!empty($requestData['search']['value'])) {
            $searchValue = $requestData['search']['value'];
            $query->where(function ($q) use ($searchValue) {
                $q->where('users.username', 'LIKE', '%' . $searchValue . '%')
                    ->orWhere('invitation_code_masters.created_at', 'LIKE', '%' . $searchValue . '%')
                    ->orWhere('users.email', 'LIKE', '%' . $searchValue . '%')
                    ->orWhere('invitation_code_masters.code', 'LIKE', '%' . $searchValue . '%')
                    ->orWhere('invitation_code_masters.expiration_date', 'LIKE', '%' . $searchValue . '%')
                    ->orWhere('invitation_code_masters.comment', 'LIKE', '%' . $searchValue . '%')
                    ->orWhere('invitation_code_masters.max_user_allow', 'LIKE', '%' . $searchValue . '%');
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
        $data = [
            'code' => $requestData['code'],
            'sponsor_id' => $requestData['sponsor_id'],
            'expiration_date' => $requestData['expiration_date'],
            'max_user_allow' => $requestData['max_user_allow'],
            'comment' => $requestData['comment'],
        ];

        return InvitationCodeMaster::create($data);
    }

    public function allSponsors()
    {
        return User::pluck('email', 'id')->toArray();
    }
}
