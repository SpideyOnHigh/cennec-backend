@extends('layouts.master')
@section('title')
    {{ __('Invitation Code') }}
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
                <x-page-title title="Invitation Code" pagetitle="Create Invitation Code" />

                <div class="grid grid-cols-1 mt-3">
                    <div class="card dark:bg-zinc-800 dark:border-zinc-600">
                        <?php
                            $randCode = str_pad(rand(0, 99999999), 8, '0', STR_PAD_LEFT);
                        ?>
                        <div class="card-body">
                            <form method="POST" action="{{ route('invitation-codes.store') }}" class="invitation-code-create-form">
                                @csrf
                                <div class="grid grid-cols-12 gap-x-6">
                                    <div class="col-span-12 lg:col-span-6">
                                        <div class="mb-4">
                                            <label for="code"
                                                class="block mb-2 font-medium text-gray-700 dark:text-gray-100">Code<span
                                                    class="text-sm text-red-600">*</span></label>
                                            <input type="number" name="code" id="code" placeholder="Enter code"
                                                class="w-full placeholder:text-13 text-13 py-1.5 rounded border-gray-100 focus:border focus:border-violet-50 focus:ring focus:ring-violet-500/20  dark:bg-zinc-700/50 dark:border-zinc-600 dark:placeholder:text-zinc-300 placeholder:text-gray-400 dark:text-zinc-100"
                                                value="{{ old('code', $randCode) }}">
                                            @error('code')
                                                <p class="error">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div class="mb-4">
                                            <label for="max_user_allow"
                                                class="block mb-2 font-medium text-gray-700 dark:text-gray-100">Maximum
                                                Number Of Users<span class="text-sm text-red-600">*</span></label>
                                            <input type="number" name="max_user_allow" id="max_user_allow"
                                                placeholder="Enter Max User"
                                                class="w-full placeholder:text-13 text-13 py-1.5 rounded border-gray-100 focus:border focus:border-violet-50 focus:ring focus:ring-violet-500/20  dark:bg-zinc-700/50 dark:border-zinc-600 dark:placeholder:text-zinc-300 placeholder:text-gray-400 dark:text-zinc-100"
                                                value="{{ old('max_user_allow', 10) }}">
                                            @error('max_user_allow')
                                                <p class="error">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="mb-4">
                                            <label for="comment"
                                                class="block mb-2 font-medium text-gray-700 dark:text-gray-100">Description<span
                                                    class="text-sm text-red-600">*</span></label>
                                            <textarea type="text" name="comment" id="comment" placeholder="Enter Description"
                                                class="w-full placeholder:text-13 text-13 py-1.5 rounded border-gray-100 focus:border focus:border-violet-50 focus:ring focus:ring-violet-500/20  dark:bg-zinc-700/50 dark:border-zinc-600 dark:placeholder:text-zinc-300 placeholder:text-gray-400 dark:text-zinc-100"
                                                value="{{ old('comment') }}"></textarea>
                                            @error('comment')
                                                <p class="error">{{ $message }}</p>
                                            @enderror
                                        </div>

                                    </div>
                                    <div class="col-span-12 lg:col-span-6">
                                        <div class="mb-4">
                                            <label for="expiration_date"
                                                class="block mb-2 font-medium text-gray-700 dark:text-gray-100">Expiration<span
                                                    class="text-sm text-red-600">*</span></label>
                                            <input type="date" name="expiration_date" id="expiration_date"
                                                placeholder="Enter Expiration"
                                                class="w-full placeholder:text-13 text-13 py-1.5 rounded border-gray-100 focus:border focus:border-violet-50 focus:ring focus:ring-violet-500/20  dark:bg-zinc-700/50 dark:border-zinc-600 dark:placeholder:text-zinc-300 placeholder:text-gray-400 dark:text-zinc-100"
                                                value="{{ old('expiration_date') }}">
                                            @error('expiration_date')
                                                <p class="error">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div class="mb-4">
                                            <label for="sponsor_id"
                                                class="block mb-2 font-medium text-gray-700 dark:text-gray-100">Sponsor<span
                                                    class="text-sm text-red-600">*</span></label>
                                            <select name="sponsor_id" id="sponsor_id"
                                                class="w-full rounded placeholder:text-sm py-2 border-gray-100 dark:bg-zinc-700/50 dark:border-zinc-600 dark:text-gray-100 dark:placeholder:text-zinc-100/60">
                                                <option value="">Select Sponsor</option>
                                                @foreach ($sponsorIds as $key => $value)
                                                    <option value="{{ $key }}">
                                                        {{ $value }}</option>
                                                @endforeach
                                            </select>
                                            @error('sponsor_id')
                                                <p class="text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>

                                    </div>
                                </div>
                                <div class="mt-3 col-span-6 sm:col-span-4 flex items-center justify-center">
                                    <button type="submit"
                                        class="mr-1 font-medium text-white border-transparent btn bg-violet-500 w-28 hover:bg-violet-700 focus:bg-violet-700 focus:ring focus:ring-violet-50">Submit</button>
                                    <a class="font-medium text-white border-transparent btn bg-red-500 w-28 hover:bg-red-700 focus:bg-red-700 focus:ring focus:ring-red-50"
                                        href="{{ route('invitation-codes.index') }}">
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
    <script src="{{ URL::asset('build/libs/alertifyjs/build/alertify.min.js') }}"></script>
    <script src="{{ asset('js/backend/invitation-code.js') }}"></script>
    <script>
        $(function() {
            invitationCode.create();
        });
    </script>
@endsection
