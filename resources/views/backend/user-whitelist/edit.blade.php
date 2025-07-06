@extends('layouts.master')
@section('title')
    {{ __('Edit Whitelist') }}
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
                <x-page-title title="Edit Whitelist" pagetitle="Whitelist" />

                <div class="grid grid-cols-1 mt-3">
                    <div class="card dark:bg-zinc-800 dark:border-zinc-600">
                        <div class="card-body">
                            <form method="POST"
                                action="{{ route('user-whitelist.update', $userWhitelist) }}"class="whitelist-table"
                                id="whitelist-table">
                                @csrf
                                <div class="grid grid-cols-12 gap-x-6">
                                    <div class="col-span-12 lg:col-span-6">
                                        <div class="mb-4">
                                            <label for="first_name"
                                                class="block mb-2 font-medium text-gray-700 dark:text-gray-100">First
                                                Name<span class="text-sm text-red-600">*</span></label>
                                            <input type="text" name="first_name" id="first_name"
                                                placeholder="Enter First Name"
                                                class="w-full placeholder:text-13 text-13 py-1.5 rounded border-gray-100 focus:border focus:border-violet-50 focus:ring focus:ring-violet-500/20  dark:bg-zinc-700/50 dark:border-zinc-600 dark:placeholder:text-zinc-300 placeholder:text-gray-400 dark:text-zinc-100"
                                                value="{{ old('first_name', $userWhitelist->first_name) }}">
                                            @error('first_name')
                                                <p class="text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-span-12 lg:col-span-6">
                                        <div class="mb-4">
                                            <label for="last_name"
                                                class="block mb-2 font-medium text-gray-700 dark:text-gray-100">Last
                                                Name<span class="text-sm text-red-600">*</span></label>
                                            <input type="text" name="last_name" id="last_name"
                                                placeholder="Enter Last Name"
                                                class="w-full placeholder:text-13 text-13 py-1.5 rounded border-gray-100 focus:border focus:border-violet-50 focus:ring focus:ring-violet-500/20  dark:bg-zinc-700/50 dark:border-zinc-600 dark:placeholder:text-zinc-300 placeholder:text-gray-400 dark:text-zinc-100"
                                                value="{{ old('last_name', $userWhitelist->last_name) }}">
                                            @error('last_name')
                                                <p class="text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-span-12 lg:col-span-6">
                                        <div class="mb-4">
                                            <label for="email"
                                                class="block mb-2 font-medium text-gray-700 dark:text-gray-100">Email<span
                                                    class="text-sm text-red-600">*</span></label>
                                            <input type="text" name="email" id="email" placeholder="Enter Email"
                                                class="w-full placeholder:text-13 text-13 py-1.5 rounded border-gray-100 focus:border focus:border-violet-50 focus:ring focus:ring-violet-500/20  dark:bg-zinc-700/50 dark:border-zinc-600 dark:placeholder:text-zinc-300 placeholder:text-gray-400 dark:text-zinc-100"
                                                value="{{ old('email', $userWhitelist->email) }}">
                                            @error('email')
                                                <p class="text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-span-12 lg:col-span-6 mt-3">
                                        <div class="mb-4">
                                            <label for="is_domain"
                                                class="block mb-2 font-medium text-gray-700 dark:text-gray-100">Is
                                                Domain<span class="text-sm text-red-600">*</span></label>
                                            <div class="mt-1 flex items-center">
                                                <div class="flex items-center" style="margin-right: 10px">
                                                    <input type="radio" id="status1" name="is_domain" value="1"
                                                        {{ old('status', $userWhitelist->domain) === '1' ? 'checked' : '' }}
                                                        class="form-radio h-4 w-4 text-indigo-600 transition duration-150 ease-in-out status">
                                                    <label for="status1"
                                                        class="ml-2 block text-sm leading-5 text-gray-700">Yes</label>
                                                </div>

                                                <div class="flex items-center">
                                                    <input type="radio" id="status2" name="is_domain" value="0"
                                                        {{ old('status', $userWhitelist->domain) === '0' ? 'checked' : '' }}
                                                        class="form-radio h-4 w-4 text-indigo-600 transition duration-150 ease-in-out status">
                                                    <label for="status2"
                                                        class="ml-2 block text-sm leading-5 text-gray-700">No</label>
                                                </div>
                                            </div>
                                            @error('is_domain')
                                                <p class="text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-3 col-span-6 sm:col-span-4 flex items-center justify-center">
                                    <button type="submit"
                                        class="mr-1 font-medium text-white border-transparent btn bg-violet-500 w-28 hover:bg-violet-700 focus:bg-violet-700 focus:ring focus:ring-violet-50">Submit</button>
                                    <a class="font-medium text-white border-transparent btn bg-red-500 w-28 hover:bg-red-700 focus:bg-red-700 focus:ring focus:ring-red-50"
                                        href="{{ route('user-whitelist.index') }}">
                                        Back
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- footer -->
                    @include('layouts.footer')
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script type="text/javascript" src="{{ asset('js/libs/jquery-3.7.1.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/libs/jquery.validate.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/alertifyjs/build/alertify.min.js') }}"></script>
    <script src="{{ asset('js/backend/user-whitelist.js') }}"></script>
    <script>
        $(function() {
            whitelist.edit();
        });
    </script>
@endsection
