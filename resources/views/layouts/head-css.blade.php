@yield('css')
<style>
    .error {
        color: rgba(229, 62, 62, 600);
    }
</style>
@php
    $iconCssUrl = URL::asset('build/css/icons.min.css') . '?v=' . time();
@endphp
<link rel="stylesheet" href="{{ $iconCssUrl }}" />
{{-- <link rel="stylesheet" href="{{ URL::asset('build/css/icons.min.css') }}" /> --}}
<link rel="stylesheet" href="{{ URL::asset('build/css/tailwind.min.css') }}" />
<link rel="stylesheet" href="{{ URL::asset('build/css/sweetalert2.css') }}" />
