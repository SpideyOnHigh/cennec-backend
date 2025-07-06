@extends('layouts.master')
@section('title')
    {{ __('Create Interest') }}
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
                <x-page-title title="Create Interest" pagetitle="Interest" />

                <div class="grid grid-cols-1 mt-3">
                    <div class="card dark:bg-zinc-800 dark:border-zinc-600">
                        <div class="card-body">
                            <form method="POST" action="{{ route('interest.store') }}"class="interest-create-form"
                                id="interest_create_form">
                                @csrf
                                <div class="grid grid-cols-12 gap-x-6">
                                    <div class="col-span-12 lg:col-span-6">
                                        <div class="mb-4">
                                            <label for="interest_name"
                                                class="block mb-2 font-medium text-gray-700 dark:text-gray-100">Name<span
                                                    class="text-sm text-red-600">*</span></label>
                                            <input type="text" name="interest_name" id="interest_name"
                                                placeholder="Enter Interest Name"
                                                class="w-full placeholder:text-13 text-13 py-1.5 rounded border-gray-100 focus:border focus:border-violet-50 focus:ring focus:ring-violet-500/20 dark:bg-zinc-700/50 dark:border-zinc-600 dark:placeholder:text-zinc-300 placeholder:text-gray-400 dark:text-zinc-100"
                                                value="{{ old('interest_name') }}">
                                            @error('interest_name')
                                                <p class="text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <!-- New flex container for Color and Description Link -->
                                        <div class="flex gap-x-6">

                                            <div class="flex-grow w-4/5">
                                                <label for="description_link"
                                                    class="block mb-2 font-medium text-gray-700 dark:text-gray-100">Description
                                                    Link</label>
                                                <input type="text" name="description_link" id="description_link"
                                                    placeholder="Enter Description Link"
                                                    class="w-full placeholder:text-13 text-13 py-1.5 rounded border-gray-100 focus:border focus:border-violet-50 focus:ring focus:ring-violet-500/20 dark:bg-zinc-700/50 dark:border-zinc-600 dark:placeholder:text-zinc-300 placeholder:text-gray-400 dark:text-zinc-100"
                                                    value="{{ old('description_link') }}">
                                                @error('description_link')
                                                    <p class="text-sm text-red-600">{{ $message }}</p>
                                                @enderror
                                            </div>

                                            <div class="flex-shrink-0 w-1/5 p-2">
                                                <label for="interest_color"
                                                    class="block mb-2 font-medium text-gray-700 dark:text-gray-100">Color<span
                                                        class="text-sm text-red-600">*</span></label>
                                                <input type="color" name="interest_color" id="interest_color"
                                                    class="w-full py-1.5 rounded border-gray-100 focus:ring focus:ring-violet-500/20 dark:bg-zinc-700/50 dark:border-zinc-600"
                                                    value="{{ old('interest_color', '#FF0000') }}">
                                                @error('interest_color')
                                                    <p class="text-sm text-red-600">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-span-12 lg:col-span-6">
                                        <div class="mb-4">
                                            <label for="interest_category_id"
                                                class="block mb-2 font-medium text-gray-700 dark:text-gray-100">Category<span
                                                    class="text-sm text-red-600">*</span></label>
                                            <select name="interest_category_id" id="interest_category_id"
                                                class="w-full rounded placeholder:text-sm py-2 border-gray-100 dark:bg-zinc-700/50 dark:border-zinc-600 dark:text-gray-100 dark:placeholder:text-zinc-100/60">
                                                <option value="">Select Category</option>
                                                @foreach ($categories as $id => $name)
                                                    <option value="{{ $id }}">{{ $name }}</option>
                                                @endforeach
                                            </select>
                                            @error('interest_category_id')
                                                <p class="text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-3 col-span-6 sm:col-span-4 flex items-center justify-center">
                                    <button type="submit"
                                        class="mr-1 font-medium text-white border-transparent btn bg-violet-500 w-28 hover:bg-violet-700 focus:bg-violet-700 focus:ring focus:ring-violet-50">Submit</button>
                                    <a class="font-medium text-white border-transparent btn bg-red-500 w-28 hover:bg-red-700 focus:bg-red-700 focus:ring focus:ring-red-50"
                                        href="{{ route('interest.index') }}">
                                        Back
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
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
    <script src="{{ asset('js/backend/interest.js') }}"></script>
    <script>
        $(function() {
            interest.create();
        });
    </script>
@endsection
