@extends('layouts.master-without-nav')
@section('title')
    Forget Password
@endsection
@section('css')
    <link rel="stylesheet" href="{{ URL::asset('build/libs/swiper/swiper-bundle.min.css') }}">
    <style>
        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        body {
            margin: 0;
            padding: 0;
            background-image: url('storage/web-bg-image/bg.jpg');
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
                    <div class="bg-white xl:p-8 p-8 dark:bg-zinc-800 max-w-sm w-full rounded-lg shadow-lg">
                        <div class="bg-white xl:p-12 p-10 dark:bg-zinc-800 max-w-sm w-full rounded-lg shadow-lg">
                            <div class="flex justify-center items-center">
                                <div class="flex-grow">
                                    <div class="text-center">
                                        <div class="flex-shrink-0 mb-4">
                                            <a href="{{ url('dashboard') }}" class="">
                                                <img src="{{ URL::asset('build/images/logo.png') }}" alt=""
                                                    height="40px" width="80px"
                                                    class="inline-block align-middle ltr:xl:mr-2 rtl:xl:ml-2">
                                            </a>
                                        </div>
                                        <h5 class="text-gray-600 dark:text-gray-100">Forgot Password</h5>
                                        <p class="text-gray-500 dark:text-gray-100/60 mt-1">Enter your Email and
                                            instructions will be sent to you!</p>
                                    </div>

                                    <form method="POST" action="{{ route('password.email') }}" class="mt-4 pt-2"
                                        id="password-reset-form">
                                        @csrf
                                        @if (session('status'))
                                            @include('components.alert-message')
                                        @endif
                                        <div class="mb-6">
                                            <label class="text-gray-600 font-medium mb-2 block dark:text-gray-100">Email
                                                <span class="text-red-600">*</span></label>
                                            <input type="email" name="email" :value="old('email')" required
                                                class="w-full border-gray-100 rounded placeholder:text-sm py-2 placeholder:text-gray-400 dark:bg-zinc-700/50 dark:border-zinc-600 dark:text-gray-100 dark:placeholder:text-zinc-100/60"
                                                id="email" placeholder="Enter email">
                                            @error('email')
                                                <span class="text-sm text-red-600">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="mb-4">
                                            <button id="reset-button"
                                                class="btn border-transparent bg-violet-500 w-full py-2.5 text-white relative flex items-center justify-center w-100 waves-effect waves-light shadow-md shadow-violet-200 dark:shadow-zinc-600"
                                                type="submit">
                                                <span id="button-text">Reset</span>
                                                <div id="loader"
                                                    class="hidden absolute w-5 h-5 border-4 border-t-4 border-white border-solid rounded-full animate-spin">
                                                </div>
                                            </button>
                                        </div>
                                    </form>
                                    <div class="mb-4">
                                        <a href="{{ route('login') }}"><button
                                                class="btn border-transparent bg-red-500 w-full py-2.5 text-white relative flex items-center justify-center w-100 waves-effect waves-light shadow-md shadow-red-200 dark:shadow-zinc-600"
                                                type="button">
                                                <span id="button-text">Back to login</span>
                                                <div id="loader"
                                                    class="hidden absolute w-5 h-5 border-4 border-t-4 border-white border-solid rounded-full animate-spin">
                                                </div>
                                            </button>
                                        </a>
                                    </div>
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
    </div>
@endsection
@section('scripts')
    <script src="{{ URL::asset('build/libs/swiper/swiper-bundle.min.js') }}"></script>
    <script src="{{ URL::asset('build/js/pages/login.init.js') }}"></script>
    <script src="{{ URL::asset('build/js/app.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            function closeAlert(alertId) {
                var alert = document.getElementById(alertId);
                if (alert) {
                    var closeButton = alert.querySelector('.alert-close');
                    if (closeButton) {
                        closeButton.click();
                    }
                }
            }
            setTimeout(function() {
                closeAlert('alert-status');
            }, 5000);
        });

        // $(document).ready(function() {
        //     $('#password-reset-form').on('submit', function(e) {
        //         $('#loader').removeClass('hidden');
        //         $('#loader').css('')
        //         $('#button-text').addClass('opacity-0');
        //         $('#reset-button').prop('disabled', true);
        //     });
        // });
        $(document).ready(function() {
            $('#password-reset-form').on('submit', function(e) {
                $('#loader').removeClass('hidden').css({
                    'border-top-color': '#ffffff',
                    'border-right-color': 'transparent',
                    'border-bottom-color': 'transparent',
                    'border-left-color': 'transparent',
                    'border-width': '4px',
                    'border-style': 'solid',
                    'border-radius': '50%',
                    'width': '24px',
                    'height': '24px',
                    'animation': 'spin 1s linear infinite'
                });

                $('#button-text').addClass('opacity-0');
                $('#reset-button').prop('disabled', true);
            });
        });
    </script>
@endsection
