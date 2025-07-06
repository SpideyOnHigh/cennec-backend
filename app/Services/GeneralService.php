<?php

namespace App\Services;

use App\Models\Policy;
use App\Models\UserProfileImage;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class GeneralService
{
    public function getRoleList()
    {
        return Role::whereNotIn('name', ['Super Admin'])->pluck('description', 'name')->toArray();
    }

    public function getCurrentUserRoleName($user)
    {
        return $user->userRole->first()->name ?? '';
    }

    public function getPermissionList($type)
    {
        return Permission::get();
    }

    public function getPolicy($type)
    {
        return Policy::where('slug', $type)->select('content')->first();
    }

    public function getDefaultProfilePicture()
    {
        $image =  UserProfileImage::where('user_id', auth()->id())->where('is_default', true)->value('image_name');
        if (!is_null($image)) {
            return concatAppUrl($image);
        }
        return null;
    }
}
