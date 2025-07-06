<?php

use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

if (! function_exists('editBtn')) {
    function editBtn($url)
    {
        return '<a class="text-white btn bg-violet-500 border-violet-500 hover:bg-violet-600 hover:border-violet-600 focus:bg-violet-600 focus:border-violet-600 focus:ring focus:ring-violet-500/30 active:bg-violet-600 active:border-violet-600 mr-2" title="Edit" href=' . $url . '><i class="fas fa-pencil-alt" title="Edit"></i></a>';
    }
}

if (! function_exists('showBtn')) {
    function showBtn($url)
    {
        return '<a class="ml-2 text-white btn bg-violet-500 border-violet-500 hover:bg-violet-600 hover:border-violet-600 focus:bg-violet-600 focus:border-violet-600 focus:ring focus:ring-violet-500/30 active:bg-violet-600 active:border-violet-600 mr-2" title="Download" href=' . $url . '><i class="fa fa-eye" title="User Detail"></i></a>';
    }
}

if (! function_exists('downloadBtn')) {
    function downloadBtn($url)
    {
        return '<a class="ml-2 text-white btn bg-violet-500 border-violet-500 hover:bg-violet-600 hover:border-violet-600 focus:bg-violet-600 focus:border-violet-600 focus:ring focus:ring-violet-500/30 active:bg-violet-600 active:border-violet-600 mr-2" title="Download" href=' . $url . '><i class="fa fa-download" title="Download"></i></a>';
    }
}

if (!function_exists('camelCasetoValue')) {
    function camelCasetoValue($string)
    {
        return preg_replace('/(?<!^)[A-Z]/', ' $0', $string);
    }
}

if (!function_exists('concatAppUrl')) {
    function concatAppUrl($path = null)
    {
        $image = url('build/images/default_user.webp');
        if (!empty($path) && file_exists(public_path('storage/' . $path))) {
            $image =  url('storage/' . $path);
        }
        return $image;
    }
}

if (! function_exists('deleteBtn')) {
    function deleteBtn($url)
    {
        return '
            <form action="' . $url . '" method="POST" onsubmit="return confirmSweetAlertDelete(this);">
                ' . method_field('DELETE') . csrf_field() . '
                <button type="submit" title="Delete" class="text-white bg-red-500 border-red-500 btn hover:bg-red-600 hover:border-red-600 focus:bg-red-600 focus:border-red-600 focus:ring focus:ring-red-500/30 active:bg-red-600 active:border-red-600">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </form>
            <script>
                function confirmSweetAlertDelete(form) {
                    Swal.fire({
                        title: "Are you sure?",
                        text: "You won\'t be able to revert this!",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Yes, delete it!"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit(); // Submit the form if the user confirms
                        } else {
                            // User cancelled, do nothing or show a message
                            Swal.fire("Cancelled", "Your data is safe!", "info");
                        }
                    });

                    return false; // Prevent the default form submission
                }
            </script>';
    }
}

if (! function_exists('resetPassword')) {
    function resetPassword($url)
    {
        return '
            <form action="' . $url . '" method="POST" onsubmit="return openResetPasswordModal(this);">
                ' . method_field('DELETE') . csrf_field() . '
                <button type="submit" title="Delete" class="text-white bg-red-500 border-red-500 btn hover:bg-red-600 hover:border-red-600 focus:bg-red-600 focus:border-red-600 focus:ring focus:ring-red-500/30 active:bg-red-600 active:border-red-600">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </form>
            <script>
                function confirmSweetAlertDelete(form) {

                    return false; // Prevent the default form submission
                }
            </script>';
    }
}

if (! function_exists('printBtn')) {
    function printBtn($url)
    {
        return '<form action="' . $url . '" method="POST" target="_blank">
            ' . csrf_field() . '
            <button type="submit" class="text-white bg-violet-500 border-violet-500 btn hover:bg-violet-600 hover:border-violet-600 focus:bg-violet-600 focus:border-violet-600 focus:ring focus:ring-violet-500/30 active:bg-violet-600 active:border-violet-600"><i class="fas fa-print text-white"></i></button>
        </form>';
    }
}

if (! function_exists('getCurrentUserRoleName')) {
    function getCurrentUserRoleName()
    {
        if (Auth::check()) {
            $user = Auth::user();
            return $user->userRole->first()->name ?? '';
        }
    }
}
if (! function_exists('getRoles')) {
    function getRoles()
    {
        return Role::whereNot('roles.name', 'App User')->pluck('name')->toArray();
    }
}

if (!function_exists('formatValidationErrors')) {
    /**
     * Format validation errors as key-value pairs.
     *
     * @param \Illuminate\Support\MessageBag $errors
     * @return array
     */
    function formatValidationErrors($errors)
    {
        $errorsArray = $errors->toArray();
        $formattedErrors = [];
        foreach ($errorsArray as $field => $messages) {
            if ($field === "comment") {
                $formattedErrors['failed'] = $messages[0];
            }
            $formattedErrors[$field] = $messages[0];
        }
        return $formattedErrors;
    }
}
