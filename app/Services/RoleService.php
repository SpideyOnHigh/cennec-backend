<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class RoleService
{
    public function fetch($roleData, $columns)
    {
        $user = Auth::user();
        $isSuperAdmin = $user && $user->userRole->first()->name == 'Super Admin';
        $rolesToExclude = $isSuperAdmin
            ? config('enum.role_exclude')
            : array_merge(config('enum.role_exclude'), ['Super Admin']);

        $query = Role::whereNotIn('name', $rolesToExclude)->whereNot('name', 'Super Admin')->select($columns);

        // $query = Role::whereNotIn('name', config('enum.role_exclude'))->select($columns);

        if (! empty($roleData['search']['value'])) {
            $searchValue = $roleData['search']['value'];
            $query->where(function ($q) use ($searchValue) {
                $q->where('roles.name', 'LIKE', '%' . $searchValue . '%')
                    ->orWhere('roles.id', 'LIKE', '%' . $searchValue . '%')
                    ->orWhereDate('roles.created_at', 'LIKE', '%' . $searchValue . '%')
                    ->orWhere('roles.description', 'LIKE', '%' . $searchValue . '%');
            });
        }
        $total = $query->count();

        $orderByColumn = $columns[$roleData['order'][0]['column']];
        $orderDirection = $roleData['order'][0]['dir'];
        $query->orderBy($orderByColumn, $orderDirection);

        $start = $roleData['start'];
        $length = $roleData['length'];
        $query->skip($start)->take($length);

        $filteredCount = $query->count();
        $data = $query->get();

        return [
            'data' => $data,
            'total' => $total,
            'filteredCount' => $filteredCount,
        ];
    }
    public function store($roleData)
    {
        $permissions = $roleData['permission'] ?? '';
        $roleName = preg_replace('/\s+/', ' ', trim($roleData['name']));

        $roleData = [
            'name' => $roleName,
            'description' => $roleData['description'],
            'created_by' => Auth::user()->id,
        ];
        $role = Role::create($roleData);
        if ($role) {
            $role->syncPermissions($permissions);
        }
    }
    public function update(Role $role, $roleData)
    {
        $permissions = $roleData['permission'] ?? '';
        $roleName = preg_replace('/\s+/', ' ', trim($roleData['name']));
        $roleData = [
            'name' => $roleName,
            'description' => $roleData['description'],
        ];

        $roleUpdate = $role->update($roleData);
        if ($roleUpdate) {
            $role->syncPermissions($permissions);
        }
        return $role;
    }
    public function delete(Role $role)
    {
        if ($role->delete()) {
            $role->permissions()->detach();
        }
        return $role;
    }
}
