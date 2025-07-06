<?php

namespace App\Services;

use App\Models\User;
use App\Notifications\StaffUserNotification;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Password;
use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\PasswordBroker;
use Laravel\Fortify\Contracts\FailedPasswordResetLinkRequestResponse;
use Laravel\Fortify\Contracts\SuccessfulPasswordResetLinkRequestResponse;
use Laravel\Fortify\Fortify;


class StaffService
{
    public function fetch($requestData, $columns)
    {
        $query = User::with('userRole')->whereNot('users.id', auth()->id())
            ->whereDoesntHave('userRole', function ($q) {
                $q->where('name', 'App User')
                    ->orWhere('name', 'Super Admin');
            })->select($columns);

        if (! empty($requestData['search']['value'])) {
            $searchValue = $requestData['search']['value'];
            $query->where(function ($q) use ($searchValue) {
                $q->where('users.name', 'LIKE', '%' . $searchValue . '%')
                    ->orWhereDate('users.created_at', 'LIKE', '%' . $searchValue . '%')
                    ->orWhere('users.email', 'LIKE', '%' . $searchValue . '%')
                    ->orWhere('users.contact', 'LIKE', '%' . $searchValue . '%')
                    ->orWhereHas('userRole', function ($q) use ($searchValue) {
                        $q->where('name', 'LIKE', '%' . $searchValue . '%');
                    });
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
        // $data = [
        //     'name' => $requestData['name'] ?? '',
        //     'email' => $requestData['email'] ?? '',
        //     'contact' => $requestData['contact'] ?? '',
        //     'invitation_code_id' => null,
        //     'password' => $requestData['password'] ? bcrypt($requestData['password']) : '',
        //     'created_by' => Auth::user()->id,
        // ];
        // $staff = User::create($data);
        // if (isset($requestData['role'])) {
        //     $roleId = $requestData['role'];
        //     $role = Role::find($roleId);
        //     $staff->assignRole($role);
        // }
        // return $staff;

        DB::beginTransaction();
        try {
            $data = [
                'name' => $requestData['name'] ?? '',
                'email' => $requestData['email'] ?? '',
                'contact' => $requestData['contact'] ?? '',
                'invitation_code_id' => null,
                'user_status' => '1',
                'password' => $this->generateStrongPassword(),
                'created_by' => Auth::user()->id,
            ];
            $existingUser = User::withTrashed()->where('email', $requestData['email'])->first();
            if ($existingUser) {
                if ($existingUser->trashed()) {
                    $existingUser->restore();
                    $existingUser->update($data);
                    $staff = $existingUser;
                }
            } else {
                $staff = User::create($data);
            }

            if (isset($requestData['role'])) {
                $roleId = $requestData['role'];
                $role = Role::find($roleId);

                if ($role) {
                    $staff->assignRole($role);
                } else {
                    DB::rollBack();
                }
            }

            $this->sendResetPasswordLink($requestData['email']);
            DB::commit();

            return $staff;
        } catch (\Exception $e) {
            DB::rollBack();
        }
    }
    public function update(User $staff, $requestData)
    {
        DB::beginTransaction();
        try {
            $staff = User::find($staff->id);
            if (!$staff || $staff->userRole->first()->name == 'App User') {
                return false;
            }
            $data = [
                'name' => $requestData['name'] ?? $staff->name,
                'email' => $requestData['email'] ?? $staff->email,
                'contact' => $requestData['contact'] ?? $staff->contact,
                'invitation_code_id' => null,
                'user_status' => $requestData['status'] ?? $staff->user_status,
                'created_by' => Auth::user()->id,
            ];
            if (isset($requestData['password']) && !empty($requestData['password'])) {
                $data['password'] = bcrypt($requestData['password']);
            }

            $staff->update($data);

            if (isset($requestData['role'])) {
                $roleId = $requestData['role'];
                $role = Role::find($roleId);

                if ($role) {
                    $staff->roles()->detach();
                    $staff->assignRole($role);
                } else {
                    DB::rollBack();
                }
            }
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
    }
    public function delete(User $staff)
    {
        DB::beginTransaction();
        try {
            $deleted = $staff->delete();
            if ($deleted) {
                $staff->roles()->detach();
            }
            DB::commit();

            return $deleted ? $staff : null;
        } catch (\Exception $e) {
            DB::rollBack();
            return null;
        }
    }

    private function generateStrongPassword($length = 12)
    {
        $length = max($length, 8);
        $password = Str::random($length);
        $specialChars = '!@#$%^&*()_+-=[]{}|;:,.<>?';
        $password .= $specialChars[random_int(0, strlen($specialChars) - 1)];
        return bcrypt($password);
    }


    /**
     * Send a reset link to the given user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\Support\Responsable
     */
    public function sendResetPasswordLink($email)
    {
        $user = User::where('email', $email)->first();

        if ($user) {
            $token = app('auth.password.broker')->createToken($user);
            $resetUrl = url('password/reset', $token) . '?email=' . urlencode($email);

            // Send the custom notification
            $user->notify(new StaffUserNotification($user->name, $resetUrl));
        }
        return $user;
    }

    protected function broker(): PasswordBroker
    {
        return Password::broker(config('fortify.passwords'));
    }
}
