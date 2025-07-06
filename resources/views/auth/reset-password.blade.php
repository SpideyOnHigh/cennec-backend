@extends('layouts.master-without-nav')
@section('title')
    Reset Password
@endsection
@section('css')
    <link rel="stylesheet" href="{{ URL::asset('build/libs/swiper/swiper-bundle.min.css') }}">
    <link href="{{ URL::asset('build/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ URL::asset('build/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}" rel="stylesheet"
        type="text/css" />

    <!-- Responsive datatable examples -->
    <link href="{{ URL::asset('build/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}"
        rel="stylesheet" type="text/css" />
    <style>
        body {
            margin: 0;
            padding: 0;
            background-image: url('{{ env('APP_URL') }}/storage/web-bg-image/bg.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }
    </style>
@endsection
@section('content')
    <div class="container-fluid">
        <div class="h-screen md:overflow-hidden">
            <div class="flex justify-center items-center h-full">
                <div
                    class="col-span-12 md:col-span-5 lg:col-span-4 xl:col-span-3 relative z-50 flex justify-center items-center min-h-screen">
                    <div class="bg-white xl:p-12 p-10 dark:bg-zinc-800 max-w-sm w-full rounded-lg shadow-lg">
                        <div class="flex justify-center items-center">
                            <div class="my-auto">
                                @if ($error)
                                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4"
                                        role="alert">
                                        <strong class="font-bold text-xl">Oops! Link Expired</strong>
                                        <span class="block sm:inline mt-2">{{ $error }}</span>
                                        <div class="mt-4 flex justify-end">
                                            <a href="{{ route('password.request') }}"
                                                class="inline-flex items-center px-4 py-2 bg-black text-white font-semibold rounded-md shadow-sm hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-black focus:ring-offset-2">
                                                {{ __('Request a New Link?') }}
                                            </a>
                                        </div>
                                    </div>
                                @else
                                    <div class="flex-grow">
                                        <div class="text-center">
                                            <div class="flex-shrink-0 mb-4">
                                                <a href="{{ url('dashboard') }}" class="">
                                                    <img src="{{ URL::asset('build/images/logo.png') }}" alt=""
                                                        height="40px" width="80px"
                                                        class="inline-block align-middle ltr:xl:mr-2 rtl:xl:ml-2">
                                                </a>
                                            </div>
                                            <h5 class="text-gray-600 dark:text-gray-100">Welcome Back !</h5>
                                            <p class="text-gray-500 dark:text-gray-100/60 mt-1">Reset Password to continue
                                                to
                                                {{ env('APP_NAME', 'Cennec') }}.
                                            </p>
                                        </div>

                                        <form method="POST" action="{{ route('password.update') }}" class="mt-4 pt-2">
                                            @csrf
                                            <input type="hidden" name="token" value="{{ $request->route('token') }}">
                                            <div class="mb-4 hidden">
                                                <label for="email"
                                                    class="text-gray-600 dark:text-gray-100 font-medium mb-2 block">Email
                                                    <span class="text-red-600">*</span></label>
                                                <input type="email" name="email"
                                                    value="{{ old('email', $request->email) }}"
                                                    class="w-full rounded placeholder:text-sm py-2 border-gray-100 dark:bg-zinc-700/50 dark:border-zinc-600 dark:text-gray-100 dark:placeholder:text-zinc-100/60"
                                                    id="email" placeholder="Enter email" required>
                                                @error('email')
                                                    <span class="text-sm text-red-600">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="mb-3">
                                                <div>
                                                    <div class="flex-grow-1">
                                                        <label for="password"
                                                            class="text-gray-600 font-medium mb-2 block dark:text-gray-100">Password
                                                            <span class="text-red-600">*</span></label>
                                                    </div>
                                                </div>

                                                <div class="flex">
                                                    <input type="password" id="password" name="password" required
                                                        class="w-full border-gray-100 rounded ltr:rounded-r-none rtl:rounded-l-none placeholder:text-sm py-2 dark:bg-zinc-700/50 dark:border-zinc-600 dark:text-gray-100 dark:placeholder:text-zinc-100/60"
                                                        placeholder="Enter New password" aria-label="Password"
                                                        aria-describedby="password-addon">
                                                    <button
                                                        class="bg-gray-50 px-4 rounded ltr:rounded-l-none rtl:rounded-r-none border border-gray-100 ltr:border-l-0 rtl:border-r-0 dark:bg-zinc-700 dark:border-zinc-600 dark:text-gray-100"
                                                        type="button" id="password-addon"><i
                                                            class="mdi mdi-eye-outline"></i></button>
                                                </div>
                                                @error('password')
                                                    <span class="text-sm text-red-600">{{ $message }}</span>
                                                @enderror
                                                @error('email')
                                                    <span class="text-sm text-red-600">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="mb-3">
                                                <div>
                                                    <div class="flex-grow-1">
                                                        <label for="password_confirmation"
                                                            class="text-gray-600 font-medium mb-2 block dark:text-gray-100">Confirm
                                                            Password <span class="text-red-600">*</span></label>
                                                    </div>
                                                </div>

                                                <div class="flex">
                                                    <input type="password" id="password_confirmation"
                                                        name="password_confirmation" required
                                                        class="w-full border-gray-100 rounded ltr:rounded-r-none rtl:rounded-l-none placeholder:text-sm py-2 dark:bg-zinc-700/50 dark:border-zinc-600 dark:text-gray-100 dark:placeholder:text-zinc-100/60"
                                                        placeholder="Confirm New password" aria-label="Password"
                                                        aria-describedby="cnf-password-addon">
                                                    <button
                                                        class="bg-gray-50 px-4 rounded ltr:rounded-l-none rtl:rounded-r-none border border-gray-100 ltr:border-l-0 rtl:border-r-0 dark:bg-zinc-700 dark:border-zinc-600 dark:text-gray-100"
                                                        type="button" id="cnf-password-addon"><i
                                                            class="mdi mdi-eye-outline"></i></button>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <button
                                                    class="btn border-transparent bg-violet-500 w-full py-2.5 text-white w-100 waves-effect waves-light shadow-md shadow-violet-200 dark:shadow-zinc-600"
                                                    type="submit">Reset & Continue</button>
                                            </div>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class=" text-center">
                            <p class="text-gray-500 dark:text-gray-100 relative mb-5">©
                                <script>
                                    document.write(new Date().getFullYear())
                                </script> {{ env('APP_NAME', 'Cennec') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{ URL::asset('build/libs/swiper/swiper-bundle.min.js') }}"></script>

    <script src="{{ URL::asset('build/js/pages/login.init.js') }}"></script>

    <script src="{{ URL::asset('build/js/app.js') }}"></script>

    <script>
        $(document).ready(function() {
            function togglePasswordVisibility(inputSelector, iconSelector, iconShowClass, iconHideClass) {
                var passwordInput = $(inputSelector);
                var passwordIcon = $(iconSelector);

                if (passwordInput.attr('type') === 'password') {
                    passwordInput.attr('type', 'text');
                    passwordIcon.removeClass(iconShowClass).addClass(iconHideClass);
                } else {
                    passwordInput.attr('type', 'password');
                    passwordIcon.removeClass(iconHideClass).addClass(iconShowClass);
                }
            }

            $('#password-addon').on('click', function() {
                togglePasswordVisibility('#password', '#password-icon', 'mdi-eye-outline',
                    'mdi-eye-off-outline');
            });

            $('#cnf-password-addon').on('click', function() {
                togglePasswordVisibility('#password_confirmation', '#password-confirmation-icon',
                    'mdi-eye-outline', 'mdi-eye-off-outline');
            });
        });
    </script>
@endsection
