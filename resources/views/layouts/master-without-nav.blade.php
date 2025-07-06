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
    <meta content="Tailwind Admin & Dashboard Template" name="description" />
    <meta content="" name="Themesbrand" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ URL::asset('build/images/logo.png') }}" />
    <!-- css -->
    @include('layouts.head-css')
    @livewireStyles
</head>

<body data-mode="light" data-sidebar-size="lg" class="group">
    <!-- content -->
    @yield('content')
    <!-- rtl-ltr -->
    @include('layouts.customizer')
    <!-- script -->
    @include('layouts.vendor-scripts')
    <!-- Scripts -->
    @vite(['resources/js/app.js'])
    @livewireScripts
</body>

</html>
