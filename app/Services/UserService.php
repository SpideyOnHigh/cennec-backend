<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserDetail;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function fetch($requestData, $columns)
    {
        $query = User::with('userRole')->leftjoin('user_details', 'user_details.user_id', 'users.id')
            ->whereHas('userRole', function ($q) {
                $q->where('name', 'App User');
            })
            ->select($columns);

        if (! empty($requestData['search']['value'])) {
            $searchValue = $requestData['search']['value'];
            $query->where(function ($q) use ($searchValue) {
                $q->where('users.name', 'LIKE', '%' . $searchValue . '%')
                    ->orWhere('users.id', 'LIKE', '%' . $searchValue . '%')
                    ->orWhere('users.email', 'LIKE', '%' . $searchValue . '%')
                    ->orWhere('users.created_at', 'LIKE', '%' . $searchValue . '%');
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

    // public function store($requestData)
    // {
    //     $userData = [
    //         'name' => $requestData['name'],
    //         'username' => $requestData['username'],
    //         'email' => $requestData['email'],
    //         'contact' => $requestData['contact'],
    //         'password' => Hash::make($requestData['password']),
    //         'address' => $requestData['address'],
    //         'created_by' => Auth::user()->id,
    //     ];

    //     $user = User::create($userData);
    //     // if ($user) {
    //     //     $user->assignRole($requestData['role']);
    //     // }
    //     return $user;
    // }
    public function update(User $user, $requestData)
    {
        $userData = [
            'username' => $requestData['username'],
            'name' => $requestData['name'],
            'email' => $requestData['email'],
            'user_status' => $requestData['status'],
        ];
        if (! empty($requestData['password'])) {
            $userData['password'] = Hash::make($requestData['password']);
        }
        $update = $user->update($userData);
        if ($update && $requestData['status'] == 0) {
            $user->tokens()->each(function ($token) {
                $token->revoke();
            });
            $user->update(['fcm_token' => null]);
        }
        $userDetailsobj = UserDetail::where('user_id', $user->id)->first();
        if ($update) {
            $userDetails = [
                'location' => $requestData['location'],
                'bio' => $requestData['bio'],
            ];
            $userDetailsobj->update($userDetails);
        }

        return $user;
    }

    public function delete(User $user)
    {
        $user = User::find($user->id);
        if ($user) {
            UserDetail::where('user_id', $user->id)->delete();
        }
        return $user->delete();
    }
}
