@extends('layouts.master')
@section('title', 'About')
@section('css')

@endsection

@section('content')
    <div class="main-content group-data-[sidebar-size=sm]:ml-[70px]">
        <div class="page-content dark:bg-zinc-700">
            <div class="container-fluid px-[0.625rem]">
                <!-- page title -->
                <x-page-title title="About" pagetitle="About" />
                <div class="grid grid-cols-12 gap-6">
                    <div class="col-span-12 flex flex-col items-center">
                        <div class="card dark:bg-zinc-800 dark:border-zinc-600">
                            <div class="relative overflow-x-auto card-body">
                                <img src="{{ asset('build/images/logo.png') }}" alt="Cennec_logo" class="h-14 inline mb-4">
                                <p class="text-center">Version : 1</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- footer -->
                @include('layouts.footer')
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{ URL::asset('js/libs/jquery-3.7.1.min.js') }}"></script>
@endsection
