@extends('layouts.master')
@section('title')
    {{ __('Edit User') }}
@endsection
@section('css')
    <style>
        .select2-results__option {
            padding-right: 20px;
            vertical-align: middle;
        }

        .select2-container {
            max-width: 300px;
        }

        .select2-selection--multiple {
            overflow: hidden !important;
            height: auto !important;
        }

        .select2-results__option:before {
            content: "";
            display: inline-block;
            position: relative;
            height: 20px;
            width: 20px;
            border: 2px solid #e9e9e9;
            border-radius: 4px;
            background-color: #fff;
            margin-right: 20px;
            vertical-align: middle;
        }

        .select2-results__option[aria-selected=true]::before {
            content: "\2714";
            color: #fff;
            background-color: #67a272;
            border: 0;
            display: inline-block;
            margin-right: 5px;
            padding: 2px 6px;
            font-size: 14px;
            line-height: 1;
            border-radius: 3px;
        }
    </style>
    <!-- alertifyjs Css -->
    <link href="{{ URL::asset('build/libs/alertifyjs/build/css/alertify.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('css/libs/select2.min.css') }}">

    <!-- alertifyjs default themes  Css -->
    <link href="{{ URL::asset('build/libs/alertifyjs/build/css/themes/default.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
    <div class="main-content group-data-[sidebar-size=sm]:ml-[70px]">
        <div class="page-content dark:bg-zinc-700">
            <div class="container-fluid px-[0.625rem]">

                <!-- page title -->
                <x-page-title title="Edit User" pagetitle="User" />

                <div class="grid grid-cols-1 mt-3">
                    <div class="card dark:bg-zinc-800 dark:border-zinc-600">
                        <div class="card-body">
                            <form method="POST" action="{{ route('users.update', $user->id) }}"
                                class="operator-edit-form" id="operator_form_edit">
                                @csrf
                                <div class="grid grid-cols-12 gap-x-6">
                                    <div class="col-span-12 lg:col-span-6">
                                        <div class="mb-4">
                                            <div class="flex items-start mb-6">
                                                <div class="flex items-center h-5">
                                                    <label
                                                        class="block mb-2 font-medium text-gray-700 dark:text-gray-100">Name:</label>
                                                </div>
                                                <span
                                                    class="block mb-2 font-medium text-gray-700 dark:text-gray-100"><b>{{ $user->name ?? '' }}</b></span>
                                            </div>
                                        </div>
                                        <div class="mb-4">
                                            <label for="name"
                                                class="block mb-2 font-medium text-gray-700 dark:text-gray-100">Display
                                                Name</label>
                                            <input type="text" name="name" id="name" placeholder="Enter Name"
                                                class="w-full placeholder:text-13 text-13 py-1.5 rounded border-gray-100 focus:border focus:border-violet-50 focus:ring focus:ring-violet-500/20  dark:bg-zinc-700/50 dark:border-zinc-600 dark:placeholder:text-zinc-300 placeholder:text-gray-400 dark:text-zinc-100"
                                                value="{{ $user->name ?? '' }}">
                                            @error('name')
                                                <p class="error">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="mb-4">
                                            <label for="location"
                                                class="block mb-2 font-medium text-gray-700 dark:text-gray-100">Location<span
                                                    class="text-sm text-red-600">*</span></label>
                                            <input type="text" name="location" id="location"
                                                placeholder="Enter Location"
                                                class="w-full placeholder:text-13 text-13 py-1.5 rounded border-gray-100 focus:border focus:border-violet-50 focus:ring focus:ring-violet-500/20  dark:bg-zinc-700/50 dark:border-zinc-600 dark:placeholder:text-zinc-300 placeholder:text-gray-400 dark:text-zinc-100"
                                                value="{{ $userDetail->location ?? '' }}">
                                            @error('location')
                                                <p class="error">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        {{-- <div class="mb-4">
                                            <label for="address"
                                                class="block mb-2 font-medium text-gray-700 dark:text-gray-100">Address<span
                                                    class="text-sm text-red-600">*</span></label>
                                            <textarea type="text" name="address" id="address" placeholder="Enter address"
                                                class="w-full placeholder:text-13 text-13 py-1.5 rounded border-gray-100 focus:border focus:border-violet-50 focus:ring focus:ring-violet-500/20  dark:bg-zinc-700/50 dark:border-zinc-600 dark:placeholder:text-zinc-300 placeholder:text-gray-400 dark:text-zinc-100">{{ old('address', $user->address ?? '') }}</textarea>
                                            @error('address')
                                                <p class="error">{{ $message }}</p>
                                            @enderror
                                        </div> --}}

                                        {{-- <div class="mb-4">
                                            <label for="gender"
                                                class="block mb-2 font-medium text-gray-700 dark:text-gray-100">Gender<span
                                                    class="text-sm text-red-600">*</span></label>
                                            <div class="mt-1 flex items-center">
                                                <div class="flex items-center" style="margin-right: 10px">
                                                    <input type="radio" id="gender2" name="gender" value="0"
                                                        {{ old('gender', $user->gender) == '0' ? 'checked' : '' }}
                                                        class="form-radio h-4 w-4 text-indigo-600 transition duration-150 ease-in-out">
                                                    <label for="gender2"
                                                        class="ml-2 block text-sm leading-5 text-gray-700">Male</label>
                                                </div>
                                                <div class="flex items-center">
                                                    <input type="radio" id="gender1" name="gender" value="1"
                                                        {{ old('gender', $user->gender) == '1' ? 'checked' : '' }}
                                                        class="form-radio h-4 w-4 text-indigo-600 transition duration-150 ease-in-out">
                                                    <label for="gender1"
                                                        class="ml-2 block text-sm leading-5 text-gray-700">Female</label>
                                                </div>
                                                <div class="flex items-center ml-2">
                                                    <input type="radio" id="gender3" name="gender" value="2"
                                                        {{ old('gender', $user->gender) == '1' ? 'checked' : '' }}
                                                        class="form-radio h-4 w-4 text-indigo-600 transition duration-150 ease-in-out">
                                                    <label for="gender1"
                                                        class="ml-2 block text-sm leading-5 text-gray-700">Other</label>
                                                </div>

                                            </div>
                                            @error('gender')
                                                <p class="error">{{ $message }}</p>
                                            @enderror
                                        </div> --}}
                                        <div class="mb-4">
                                            <label for="status"
                                                class="block mb-2 font-medium text-gray-700 dark:text-gray-100">Status<span
                                                    class="text-sm text-red-600">*</span></label>
                                            <div class="mt-1 flex items-center">
                                                <div class="flex items-center" style="margin-right: 10px">
                                                    <input type="radio" id="status1" name="status" value="1"
                                                        {{ old('status', $user->user_status) === '1' ? 'checked' : '' }}
                                                        class="form-radio h-4 w-4 text-indigo-600 transition duration-150 ease-in-out status">
                                                    <label for="status1"
                                                        class="ml-2 block text-sm leading-5 text-gray-700">Active</label>
                                                </div>

                                                <div class="flex items-center">
                                                    <input type="radio" id="status2" name="status" value="0"
                                                        {{ old('status', $user->user_status) === '0' ? 'checked' : '' }}
                                                        class="form-radio h-4 w-4 text-indigo-600 transition duration-150 ease-in-out status">
                                                    <label for="status2"
                                                        class="ml-2 block text-sm leading-5 text-gray-700">Block</label>
                                                </div>
                                            </div>
                                            @error('status')
                                                <p class="text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="mt-4 form-check">
                                            <label class="font-medium text-gray-700 ltr:mr-2 rtl:ml-2 dark:text-zinc-100"
                                                for="formrow-customCheck">Do you want to change the password?</label>
                                            <input type="checkbox"
                                                class="align-middle rounded focus:ring-0 focus:ring-offset-0 dark:bg-zinc-700 dark:border-zinc-400 checked:bg-violet-500 dark:checked:bg-violet-500 ckb-password"
                                                name="is_password" value="0">
                                        </div>
                                        
                                        <div class="mb-4 hidden password-div">
                                            <label for="password"
                                                class="block mb-2 font-medium text-gray-700 dark:text-gray-100">Password<span
                                                    class="text-sm text-red-600">*</span></label>
                                            <input type="password" name="password" id="password"
                                                placeholder="Enter password"
                                                class="w-full placeholder:text-13 text-13 py-1.5 rounded border-gray-100 focus:border focus:border-violet-50 focus:ring focus:ring-violet-500/20  dark:bg-zinc-700/50 dark:border-zinc-600 dark:placeholder:text-zinc-300 placeholder:text-gray-400 dark:text-zinc-100"
                                                value="{{ old('password') }}">
                                            @error('password')
                                                <p class="text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="mb-4 hidden password-div">
                                            <label for="confirm_password"
                                                class="block mb-2 font-medium text-gray-700 dark:text-gray-100">Confirm
                                                Password<span class="text-sm text-red-600">*</span></label>
                                            <input type="password" name="confirm_password" id="confirm_password"
                                                placeholder="Enter confirm password"
                                                class="w-full placeholder:text-13 text-13 py-1.5 rounded border-gray-100 focus:border focus:border-violet-50 focus:ring focus:ring-violet-500/20  dark:bg-zinc-700/50 dark:border-zinc-600 dark:placeholder:text-zinc-300 placeholder:text-gray-400 dark:text-zinc-100"
                                                value="{{ old('confirm_password') }}">
                                            @error('confirm_password')
                                                <p class="text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-span-12 lg:col-span-6">
                                        <div class="mb-4">
                                            <div class="flex items-start mb-6">
                                                <div class="flex items-center h-5">
                                                    <label
                                                        class="block mb-2 font-medium text-gray-700 dark:text-gray-100">Username:</label>
                                                </div>
                                                <span
                                                    class="block mb-2 font-medium text-gray-700 dark:text-gray-100"><b>{{ $user->username ?? '' }}</b></span>
                                            </div>
                                        </div>
                                        <div class="mb-4">
                                            <label for="username"
                                                class="block mb-2 font-medium text-gray-700 dark:text-gray-100">Username<span
                                                    class="text-sm text-red-600">*</span></label>
                                            <input type="text" name="username" id="username"
                                                placeholder="Enter Username"
                                                class="w-full placeholder:text-13 text-13 py-1.5 rounded border-gray-100 focus:border focus:border-violet-50 focus:ring focus:ring-violet-500/20  dark:bg-zinc-700/50 dark:border-zinc-600 dark:placeholder:text-zinc-300 placeholder:text-gray-400 dark:text-zinc-100"
                                                value="{{ $user->username ?? '' }}">
                                            @error('username')
                                                <p class="error">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="mb-4">
                                            <label for="email"
                                                class="block mb-2 font-medium text-gray-700 dark:text-gray-100">Email<span
                                                    class="text-sm text-red-600">*</span></label>
                                            <input type="email" name="email" id="email"
                                                placeholder="Enter email"
                                                class="w-full placeholder:text-13 text-13 py-1.5 rounded border-gray-100 focus:border focus:border-violet-50 focus:ring focus:ring-violet-500/20  dark:bg-zinc-700/50 dark:border-zinc-600 dark:placeholder:text-zinc-300 placeholder:text-gray-400 dark:text-zinc-100"
                                                value="{{ old('email', $user->email ?? '') }}">
                                            @error('email')
                                                <p class="error">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        {{-- <div class="mb-4">
                                            <label for="name"
                                                class="block mb-2 font-medium text-gray-700 dark:text-gray-100">City<span
                                                    class="text-sm text-red-600">*</span></label>
                                            <input type="text" name="city" id="city" placeholder="Enter city"
                                                class="w-full placeholder:text-13 text-13 py-1.5 rounded border-gray-100 focus:border focus:border-violet-50 focus:ring focus:ring-violet-500/20  dark:bg-zinc-700/50 dark:border-zinc-600 dark:placeholder:text-zinc-300 placeholder:text-gray-400 dark:text-zinc-100"
                                                value="{{ old('city', $user->city ?? '') }}">
                                            @error('city')
                                                <p class="error">{{ $message }}</p>
                                            @enderror
                                        </div> --}}




                                        {{-- <div class="mb-4">
                                            <label for="description"
                                                class="block mb-2 font-medium text-gray-700 dark:text-gray-100">Description<span
                                                    class="text-sm text-red-600">*</span></label>
                                            <textarea type="text" name="description" id="description" placeholder="Enter description"
                                                class="w-full border-gray-100 rounded placeholder:text-13 text-13 py-1.5 focus:border focus:ring focus:ring-violet-500/20 focus:border-violet-100 dark:bg-zinc-700/50 dark:border-zinc-600 dark:placeholder:text-zinc-100 dark:text-zinc-100">{{ old('description', $user->description ?? '') }}</textarea>
                                            @error('description')
                                                <p class="error">{{ $message }}</p>
                                            @enderror
                                        </div> --}}
                                        {{-- <div class="mb-4">
                                            <label for="status"
                                                class="block mb-2 font-medium text-gray-700 dark:text-gray-100">Status<span
                                                    class="text-sm text-red-600">*</span></label>
                                            <div class="mt-1 flex items-center">
                                                <div class="flex items-center" style="margin-right: 10px">
                                                    <input type="radio" id="status1" name="status" value="1"
                                                        {{ old('status', $user->status) == '1' ? 'checked' : '' }}
                                                        class="form-radio h-4 w-4 text-indigo-600 transition duration-150 ease-in-out">
                                                    <label for="status1"
                                                        class="ml-2 block text-sm leading-5 text-gray-700">Active</label>
                                                </div>

                                                <div class="flex items-center">
                                                    <input type="radio" id="status2" name="status" value="0"
                                                        {{ old('status', $user->status) == '0' ? 'checked' : '' }}
                                                        class="form-radio h-4 w-4 text-indigo-600 transition duration-150 ease-in-out">
                                                    <label for="status2"
                                                        class="ml-2 block text-sm leading-5 text-gray-700">Inactive</label>
                                                </div>
                                            </div>
                                            @error('status')
                                                <p class="error">{{ $message }}</p>
                                            @enderror
                                        </div> --}}

                                        <div class="mb-4">
                                            <label for="bio"
                                                class="block mb-2 font-medium text-gray-700 dark:text-gray-100">Bio</label>
                                            
                                            <textarea name="bio"
                                                class="w-full rounded placeholder:text-sm py-2 border-gray-100 dark:bg-zinc-700/50 dark:border-zinc-600 dark:text-gray-100 dark:placeholder:text-zinc-100/60"
                                                id="bio" placeholder="Enter Bio">{{ old('bio', $userDetail->bio ?? '') }}</textarea>
                                            @error('bio')
                                                <p class="error">{{ $message }}</p>
                                            @enderror
                                        </div>

                                    </div>
                                </div>
                                <div class="mt-3 col-span-6 sm:col-span-4 flex items-center justify-center">
                                    <button type="submit"
                                        class="mr-1 font-medium text-white border-transparent btn bg-violet-500 w-28 hover:bg-violet-700 focus:bg-violet-700 focus:ring focus:ring-violet-50">Submit</button>
                                    <a class="font-medium text-white border-transparent btn bg-red-500 w-28 hover:bg-red-700 focus:bg-red-700 focus:ring focus:ring-red-50"
                                        href="{{ route('users.index') }}">
                                        Back
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @include('layouts.footer')
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script type="text/javascript" src="{{ asset('js/libs/jquery-3.7.1.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/libs/jquery.validate.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/libs/select2.full.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/alertifyjs/build/alertify.min.js') }}"></script>
    <script src="{{ asset('js/backend/user.js') }}"></script>
    <script>
        $(function() {
            operator.edit();
        });
    </script>
@endsection
