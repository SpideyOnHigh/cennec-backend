<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8" />
    <title>
        @hasSection('title')
            @yield('title') |
        @endif {{ env('APP_NAME', 'Cennec') }}
    </title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    {{-- <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate"> --}}
    <meta content="{{ env('APP_NAME', 'Cennec') }}" name="description" />
    <meta content="" name="{{ env('APP_NAME', 'Cennec') }}" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ URL::asset('build/images/logo.png') }}" />
    <!-- css files -->
    @include('layouts.head-css')
    <!-- Styles -->
    @livewireStyles
</head>

<body data-mode="light" data-sidebar-size="lg" class="group">
    <!-- sidebar -->
    @include('layouts.sidebar')
    <!-- topbar -->
    @include('layouts.topbar')
    <!-- content -->
    @yield('content')
    <!-- rtl-ltr -->
    @include('layouts.customizer')
    <!-- script -->
    @include('layouts.vendor-scripts')
    <!-- Scripts -->
    @vite(['resources/js/app.js'])
    <script type="text/javascript" src="{{ asset('js/libs/jquery.validate.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/common.js') }}"></script>
    <script src="https://cdn.ckeditor.com/4.16.1/standard/ckeditor.js"></script>
    {{-- <script type="text/javascript" src="{{ asset('js/libs/ckeditor.js') }}"></script> --}}
    @livewireScripts
</body>

</html>
