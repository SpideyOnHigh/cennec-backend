<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;

class ImportController extends Controller
{
    public function users()
    {
        $users = include(public_path('import/users.php'));

        foreach ($users as $key => $value) {
            dd($value);
            $userRow = [];
        }
    }

    public function smoke()
    {
        $smoke = include(public_path('import/smoke.php'));
        dd($smoke);
        foreach ($smoke as $key => $value) {

            $data = [];
        }
    }


    public function drinker()
    {
        $drinker = include(public_path('import/drinker.php'));
        dd($drinker);
        foreach ($drinker as $key => $value) {

            $data = [];
        }
    }

    public function invitation_codes()
    {
        $invitation_codes = include(public_path('import/invitation_codes.php'));
        dd($invitation_codes);
        foreach ($invitation_codes as $key => $value) {

            $data = [];
        }
    }
}
