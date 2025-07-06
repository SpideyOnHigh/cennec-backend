@extends('layouts.master')

@section('title')
    {{ __('Email Test') }}
@endsection

@section('css')
    <style>
        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .spinner {
            border: 2px solid transparent;
            border-radius: 50%;
            border-top: 2px solid #ffffff;
            width: 1em;
            height: 1em;
            animation: spin 0.75s linear infinite;
        }
    </style>
@endsection

@section('content')
    <div class="main-content group-data-[sidebar-size=sm]:ml-[70px]">
        <div class="page-content dark:bg-zinc-700">
            <div class="container-fluid px-[0.625rem]">

                <x-page-title title="Email Test" pagetitle="Send Emails" route="#" permission="send-emails" />

                <div class="grid grid-cols-12 gap-6">
                    <div class="col-span-12">
                        @include('components.alert-message')

                        <div class="bg-white p-6 rounded-lg shadow-md">
                            <h2 class="text-xl font-bold mb-4">Send Email</h2>

                            <form action="{{ route('email-test.send-email') }}" method="POST" class="space-y-4" id="email-form">
                                @csrf

                                <div>
                                    <label for="first_name" class="block text-gray-700">First Name</label>
                                    <input type="text" id="first_name" name="first_name" value="Sven"
                                        class="mt-1 p-2 w-full border rounded">
                                </div>

                                <div>
                                    <label for="email" class="block text-gray-700">Email</label>
                                    <input type="email" id="email" name="email" value="test@yopmail.com"
                                        class="mt-1 p-2 w-full border rounded">
                                </div>

                                <div class="flex gap-4">
                                    <button type="submit" name="template" value="simple"
                                        class="bg-red-500 text-white px-4 py-2 rounded-md border border-transparent focus:outline-none focus:ring-2 focus:ring-custom-bg focus:ring-opacity-50">
                                        Send Email [Simple Template]
                                    </button>

                                    <button type="submit" name="template" value="rich"
                                        class="bg-green-500 text-white px-4 py-2 rounded-md border border-transparent hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-300 focus:ring-opacity-50">
                                        Send Email [Rich Template]
                                    </button>
                                </div>
                            </form>

                            
                            <div class="mt-4">
                                <h6>Server Response:</h6>
                                <textarea id="response-container" disabled rows="4" cols="50" style="resize: none;"></textarea>
                            </div>
                                                        
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                @include('layouts.footer')
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript" src="{{ asset('js/libs/jquery-3.7.1.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/libs/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('js/backend/email-test.js') }}"></script>
    <script>
        $(function() {
            email.init();
        });
    </script>
@endsection
