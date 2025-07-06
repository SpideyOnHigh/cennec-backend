@extends('layouts.master')
@section('title')
    {{ __('User Detail') }}
@endsection
@section('css')
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        .user-details {
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 20px;
            width: 100%;
            margin-top: 10px;
        }

        .user-details h2 {
            margin-top: 0;
        }

        .user-details div {
            margin-bottom: 10px;
        }

        .label {
            font-weight: bold;
        }

        .username-picture-container {
            display: flex;
            align-items: center;
        }

        .username-picture {
            display: flex;
            align-items: center;
        }

        .profile-picture {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            margin-right: 10px;
        }

        .username {
            font-size: 16px;
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
                <x-page-title title="User Detail" pagetitle="User" />

                <div class="grid grid-cols-1 mt-3">
                    <div class="card dark:bg-zinc-800 dark:border-zinc-600">
                        <div class="card-body">
                            <div class="col-md-12">
                                <div class="text-right">
                                    <a href="{{ route('users.index') }}" id="backButton"
                                        class="inline-flex items-center text-blue-500 hover:text-blue-700">
                                        <i class="fas fa-angle-left mr-2"></i>Go Back
                                    </a>
                                </div>
                                <div class="user-details">
                                    <div class="username-picture-container">
                                        <span class="username-picture">
                                            {{-- @if ($user->firstProfileImage && $user->firstProfileImage->image_name) --}}
                                                <img src="{{ asset($userDefaultImage) }}"
                                                    alt="Profile Picture" class="profile-picture" />
                                            {{-- @endif --}}
                                            <span class="username">{{ $user->username }}</span>
                                        </span>
                                    </div>
                                    <div><span class="label">Id:</span> <span>{{ $user->id }}</span></div>
                                    {{-- <div><span class="label">Name:</span> <span>{{ $user->name }}</span></div> --}}
                                    <div><span class="label">Username:</span> <span>{{ $user->username }}</span></div>
                                    <div><span class="label">Email:</span> <span>{{ $user->email }}</span></div>
                                    <div><span class="label">Contact:</span> <span>{{ $user->contact ?? 'N/A' }}</span>
                                    </div>
                                    <div><span class="label">Invitation Code:</span>
                                        <span>{{ $user->invitationCode->code ?? 'N/A' }}</span>
                                    </div>
                                    <div><span class="label">Date of Birth:</span>
                                        <span>{{ $user->details->dob ?? 'N/A' }}</span>
                                    </div>
                                    <div><span class="label">Role:</span>
                                        <span>{{ $user->userRole->first()->description ?? 'N/A' }}</span>
                                    </div>
                                    <div><span class="label">Gender:</span>
                                        <span>{{ getGender($user->details->gender ?? 0) }}</span>
                                    </div>
                                    <div><span class="label">Bio:</span> <span>{{ $user->details->bio ?? 'N/A' }}</span>
                                    </div>
                                    <div><span class="label">Location:</span>
                                        <span>{{ $user->details->location ?? 'N/A' }}</span>
                                    </div>
                                    <div><span class="label">Latitude:</span>
                                        <span>{{ $user->details->location_latitude ?? 'N/A' }}</span>
                                    </div>
                                    <div><span class="label">Longitude:</span>
                                        <span>{{ $user->details->location_longitude ?? 'N/A' }}</span>
                                    </div>
                                    <div><span class="label">Zip Code:</span>
                                        <span>{{ $user->details->location_longitude ?? 'N/A' }}</span>
                                    </div>
                                    <div><span class="label">Created:</span> <span>{{ $user->created_at }}</span></div>
                                    <div><span class="label">Updated:</span> <span>{{ $user->updated_at }}</span></div>
                                    <div><span class="label">Deleted?:</span>
                                        <span>{{ $user->deleted_at ? 'Yes' : 'No' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @php
                            function getGender($gender)
                            {
                                switch ($gender) {
                                    case 1:
                                        return 'Male';
                                    case 2:
                                        return 'Female';
                                    case 3:
                                        return 'Others';
                                    default:
                                        return 'N/A';
                                }
                            }
                        @endphp
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
