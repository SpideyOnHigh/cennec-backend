@extends('layouts.master')
@section('title')
    {{ __('Edit Staff') }}
@endsection
@section('css')
    <!-- alertifyjs Css -->
    <link href="{{ URL::asset('build/libs/alertifyjs/build/css/alertify.min.css') }}" rel="stylesheet" type="text/css" />

    <!-- alertifyjs default themes  Css -->
    <link href="{{ URL::asset('build/libs/alertifyjs/build/css/themes/default.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
    <div class="main-content group-data-[sidebar-size=sm]:ml-[70px]">
        <div class="page-content dark:bg-zinc-700">
            <div class="container-fluid px-[0.625rem]">

                <!-- page title -->
                <x-page-title title="Edit Staff" pagetitle="Staff" />

                <div class="grid grid-cols-1 mt-3">
                    <div class="card dark:bg-zinc-800 dark:border-zinc-600">
                        <div class="card-body">
                            <form method="POST" action="{{ route('staff.update', $staff) }}"class="staff-edit-form"
                                id="staff_edit_form">
                                @csrf
                                <div class="grid grid-cols-12 gap-x-6">
                                    <div class="col-span-12 lg:col-span-6">
                                        <div class="mb-4">
                                            <label for="name"
                                                class="block mb-2 font-medium text-gray-700 dark:text-gray-100">Name<span
                                                    class="text-sm text-red-600">*</span></label>
                                            <input type="text" name="name" id="name" placeholder="Enter name"
                                                class="w-full placeholder:text-13 text-13 py-1.5 rounded border-gray-100 focus:border focus:border-violet-50 focus:ring focus:ring-violet-500/20  dark:bg-zinc-700/50 dark:border-zinc-600 dark:placeholder:text-zinc-300 placeholder:text-gray-400 dark:text-zinc-100"
                                                value="{{ old('name', $staff->name) }}">
                                            @error('name')
                                                <p class="text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="mb-4">
                                            <label for="email"
                                                class="block mb-2 font-medium text-gray-700 dark:text-gray-100">Email
                                                Id<span class="text-sm text-red-600">*</span></label>
                                            <input type="text" name="email" id="email" placeholder="Enter Email"
                                                class="w-full placeholder:text-13 text-13 py-1.5 rounded border-gray-100 focus:border focus:border-violet-50 focus:ring focus:ring-violet-500/20  dark:bg-zinc-700/50 dark:border-zinc-600 dark:placeholder:text-zinc-300 placeholder:text-gray-400 dark:text-zinc-100"
                                                value="{{ old('email', $staff->email) }}">
                                            @error('email')
                                                <p class="text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>



                                        <div class="mb-4">
                                            <label for="status"
                                                class="block mb-2 font-medium text-gray-700 dark:text-gray-100">Status<span
                                                    class="text-sm text-red-600">*</span></label>
                                            <div class="mt-1 flex items-center">
                                                <div class="flex items-center" style="margin-right: 10px">
                                                    <input type="radio" id="status1" name="status" value="1"
                                                        {{ old('status', $staff->user_status) === '1' ? 'checked' : '' }}
                                                        class="form-radio h-4 w-4 text-indigo-600 transition duration-150 ease-in-out status">
                                                    <label for="status1"
                                                        class="ml-2 block text-sm leading-5 text-gray-700">Active</label>
                                                </div>

                                                <div class="flex items-center">
                                                    <input type="radio" id="status2" name="status" value="0"
                                                        {{ old('status', $staff->user_status) === '0' ? 'checked' : '' }}
                                                        class="form-radio h-4 w-4 text-indigo-600 transition duration-150 ease-in-out status">
                                                    <label for="status2"
                                                        class="ml-2 block text-sm leading-5 text-gray-700">Inactive</label>
                                                </div>
                                            </div>
                                            @error('status')
                                                <p class="text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>

                                    </div>
                                    <div class="col-span-12 lg:col-span-6">
                                        <div class="mb-4">
                                            <label for="role"
                                                class="block mb-2 font-medium text-gray-700 dark:text-gray-100">Role
                                                <span class="text-sm text-red-600">*</span></label>
                                            <select name="role" id="role"
                                                class="w-full rounded placeholder:text-sm py-2 border-gray-100 dark:bg-zinc-700/50 dark:border-zinc-600 dark:text-gray-100 dark:placeholder:text-zinc-100/60">
                                                <option value="">Select Role</option>
                                                @foreach ($roles as $id => $name)
                                                    <option value="{{ $id }}"
                                                        {{ isset($staff->userRole) && $staff->userRole->first()->id == $id ? 'selected' : '' }}>
                                                        {{ camelCasetoValue($name) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('role')
                                                <p class="text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="mb-4">
                                            <label for="contact"
                                                class="block mb-2 font-medium text-gray-700 dark:text-gray-100">Contact
                                                Number<span class="text-sm text-red-600">*</span></label>
                                            <input type="text" name="contact" id="contact"
                                                placeholder="Enter Contact Number"
                                                class="w-full placeholder:text-13 text-13 py-1.5 rounded border-gray-100 focus:border focus:border-violet-50 focus:ring focus:ring-violet-500/20  dark:bg-zinc-700/50 dark:border-zinc-600 dark:placeholder:text-zinc-300 placeholder:text-gray-400 dark:text-zinc-100"
                                                value="{{ old('contact', $staff->contact) }}">
                                            @error('contact')
                                                <p class="text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-3 col-span-6 sm:col-span-4 flex items-center justify-center">
                                    <button type="submit"
                                        class="mr-1 font-medium text-white border-transparent btn bg-violet-500 w-28 hover:bg-violet-700 focus:bg-violet-700 focus:ring focus:ring-violet-50">Submit</button>
                                    <a class="font-medium text-white border-transparent btn bg-red-500 w-28 hover:bg-red-700 focus:bg-red-700 focus:ring focus:ring-red-50"
                                        href="{{ route('staff.index') }}">
                                        Back
                                    </a>
                                </div>
                        </div>
                        </form>
                    </div>
                </div>
                <!-- footer -->
                @include('layouts.footer')
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script type="text/javascript" src="{{ asset('js/libs/jquery-3.7.1.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/libs/jquery.validate.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/alertifyjs/build/alertify.min.js') }}"></script>

    <script src="{{ asset('js/backend/staff.js') }}"></script>
    <script>
        $(function() {
            staff.edit();
        });
    </script>
@endsection
