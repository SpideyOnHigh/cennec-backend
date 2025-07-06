@extends('layouts.master')
@section('title')
    {{ __('Whitelist User') }}
@endsection
@section('css')
    <!-- DataTables -->
    <link href="{{ URL::asset('build/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ URL::asset('build/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}" rel="stylesheet"
        type="text/css" />

    <!-- Responsive datatable examples -->
    <link href="{{ URL::asset('build/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}"
        rel="stylesheet" type="text/css" />
    <style>
        .dataTables_wrapper .dt-buttons {
            float: right;
            margin-right: 15px;
        }

        .dataTables_filter {
            float: right;
        }

        .dataTables_length {
            float: left;
            margin-right: 10px;
        }

        .dt-buttons {
            display: flex;
            align-items: center;
        }

        .dataTables_wrapper .dataTables_filter input {
            margin-left: 0.5em;
        }

        .dataTables_wrapper .dt-buttons button:last-child {
            border-top-right-radius: 5px;
            border-bottom-right-radius: 5px;
        }
    </style>
@endsection
@section('content')
    <div class="main-content group-data-[sidebar-size=sm]:ml-[70px]">
        <div class="page-content dark:bg-zinc-700">
            <div class="container-fluid px-[0.625rem]">

                <!-- page title -->
                <x-page-title title="Domains" pagetitle="Domains list" route="user-whitelist.create"
                    permission="create-user-whitelist" />

                @can('list-user-whitelist')
                    <div class="grid grid-cols-12 gap-6">
                        <div class="col-span-12">
                            @include('components.alert-message')
                            <div class="card dark:bg-zinc-800 dark:border-zinc-600">
                                <div class="relative overflow-x-auto card-body">
                                    <table id="whitelist-domain-table"
                                        class="table w-full pt-4 text-gray-700 dark:text-zinc-100" aria-label="table">
                                        <thead>
                                            <tr>
                                                <th
                                                    class="p-4 pr-8 border rtl:border-l-0 border-t-2 border-gray-50 dark:border-zinc-600">
                                                    Domain</th>
                                                <th
                                                    class="p-4 pr-8 border rtl:border-l-0 border-t-2 border-gray-50 dark:border-zinc-600">
                                                    Name</th>
                                                <th
                                                    class="p-4 pr-8 border rtl:border-l-0 border-t-2 border-gray-50 dark:border-zinc-600">
                                                    Action</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <h4 class="mb-3">Emails</h4>
                    <div class="grid grid-cols-12 gap-6">
                        <div class="col-span-12">
                            <div class="card dark:bg-zinc-800 dark:border-zinc-600">
                                <div class="relative overflow-x-auto card-body">
                                    <table id="whitelist-email-table" class="table w-full pt-4 text-gray-700 dark:text-zinc-100"
                                        aria-label="table">
                                        <thead>
                                            <tr>
                                                <th
                                                    class="p-4 pr-8 border rtl:border-l-0 border-t-2 border-gray-50 dark:border-zinc-600">
                                                    Email</th>
                                                <th
                                                    class="p-4 pr-8 border rtl:border-l-0 border-t-2 border-gray-50 dark:border-zinc-600">
                                                    First Name</th>
                                                <th
                                                    class="p-4 pr-8 border rtl:border-l-0 border-t-2 border-gray-50 dark:border-zinc-600">
                                                    Last Name</th>
                                                <th
                                                    class="p-4 pr-8 border rtl:border-l-0 border-t-2 border-gray-50 dark:border-zinc-600">
                                                    Joined</th>
                                                <th
                                                    class="p-4 pr-8 border rtl:border-l-0 border-t-2 border-gray-50 dark:border-zinc-600">
                                                    Action</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                @endcan
                <!-- footer -->
                @include('layouts.footer')
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script type="text/javascript" src="{{ asset('js/libs/jquery-3.7.1.min.js') }}"></script>

    <!-- Required datatable js -->
    <script src="{{ URL::asset('build/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <!-- Buttons examples -->
    <script src="{{ URL::asset('build/libs/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/jszip/jszip.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/pdfmake/build/pdfmake.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/pdfmake/build/vfs_fonts.js') }}"></script>
    <script src="{{ URL::asset('build/libs/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/datatables.net-buttons/js/buttons.colVis.min.js') }}"></script>

    <!-- Responsive examples -->
    <script src="{{ URL::asset('build/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}"></script>

    <!-- Datatable init js -->
    <script src="{{ URL::asset('build/js/pages/datatables.init.js') }}"></script>

    <script>
        var getDomainlist = "{{ route('user-whitelist.fetch-domain') }}";
        var getEmaillist = "{{ route('user-whitelist.fetch-email') }}";
    </script>
    <script src="{{ asset('js/backend/user-whitelist.js') }}"></script>
    <script>
        $(function() {
            whitelist.init();
        });
    </script>
@endsection
