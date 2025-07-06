@extends('layouts.master')
@section('title')
    {{ __('Calendar') }}
@endsection
@section('content')
    <!-- start grid -->
    <div class="main-content group-data-[sidebar-size=sm]:ml-[70px]">
        <div class="page-content dark:bg-zinc-700">
            <div class="container-fluid px-[0.625rem]">
                <x-page-title title="Calendar" pagetitle="Apps" />
                <!-- start grid -->
                <div class="grid grid-cols-1 gap-x-6 mb-5 xl:grid-cols-12 ">
                    <!-- start col -->
                    <div class="grid-cols-1 lg:col-span-4 2xl:col-span-3">
                        <div class="card dark:bg-zinc-800 dark:border-zinc-600">
                            <div class="card-body">
                                <button
                                    class="w-full mb-3 text-base text-white border-transparent shadow-md btn bg-violet-500 dark:shadow-none shadow-violet-200"
                                    id="btn-new-event" data-tw-toggle="modal" data-tw-target="#event-modal"><i
                                        class="mdi mdi-plus-circle-outline"></i> Create
                                    New Event
                                </button>

                                <div id="external-events">
                                    <br>
                                    <p class="text-gray-500 dark:text-zinc-100/80">Drag and drop your event or click in the
                                        calendar</p>
                                    <div class="mt-4">
                                        <div class="cursor-move bg-green-600/30 px-4 py-2 rounded text-green-600 mx-2 my-[5px] dark:bg-green-100 text-[13px]"
                                            data-class="bg-green-500">
                                            <i
                                                class="align-middle mdi mdi-checkbox-blank-circle text-11 ltr:mr-2 rtl:ml-2"></i>New
                                            Event Planning
                                        </div>
                                        <div class="cursor-move bg-sky-500/30 px-4 py-2 rounded text-sky-500 mx-2 my-[5px] dark:bg-sky-100 text-[13px]"
                                            data-class="bg-sky-500">
                                            <i
                                                class="align-middle mdi mdi-checkbox-blank-circle text-11 ltr:mr-2 rtl:ml-2"></i>Meeting
                                        </div>
                                        <div class="cursor-move bg-yellow-500/30 px-4 py-2 rounded text-yellow-500 mx-2 my-[5px] dark:bg-yellow-100 text-[13px]"
                                            data-class="bg-yellow-500">
                                            <i
                                                class="align-middle mdi mdi-checkbox-blank-circle text-11 ltr:mr-2 rtl:ml-2"></i>Generating
                                            Reports
                                        </div>
                                        <div class="cursor-move bg-red-500/30 px-4 py-2 rounded text-red-500 mx-2 my-[5px] dark:bg-red-100 text-[13px]"
                                            data-class="bg-red-500">
                                            <i
                                                class="align-middle mdi mdi-checkbox-blank-circle text-11 ltr:mr-2 rtl:ml-2"></i>Create
                                            New theme
                                        </div>
                                        <div class="cursor-move bg-gray-500/30 px-4 py-2 rounded text-gray-800 mx-2 my-[5px] dark:bg-gray-100 text-[13px]"
                                            data-class="bg-zinc-800">
                                            <i
                                                class="align-middle mdi mdi-checkbox-blank-circle text-11 ltr:mr-2 rtl:ml-2"></i>Team
                                            Meeting
                                        </div>
                                    </div>
                                </div>

                                <div class="grid grid-cols-12">
                                    <div class="col-span-12 mt-12">
                                        <img src="{{ URL::asset('build/images/undraw-calendar.svg') }}" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end col -->

                    <!-- start col -->
                    <div class="grid-cols-1 lg:col-span-8 2xl:col-span-9">
                        <div class="card dark:bg-zinc-800 dark:border-zinc-600">
                            <div class="space-y-5 card-body">
                                <div id="calendar"></div>
                            </div>
                        </div>
                    </div>
                    <!-- end col -->
                </div>
                <!-- end grid -->
                <!-- footer -->
                @include('layouts.footer')
            </div>
        </div>
    </div>

    <div class="relative z-10 hidden modal" id="event-modal" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="absolute inset-0 transition-opacity bg-black bg-opacity-50 modal-overlay"></div>
            <div class="flex items-end justify-center min-h-full p-4 text-center sm:items-center sm:p-0">
                <div
                    class="relative overflow-hidden text-left transition-all transform bg-white rounded-lg shadow-xl dark:bg-zinc-600 sm:my-8 sm:w-full sm:max-w-lg">
                    <div class="bg-white dark:bg-zinc-600">
                        <div class="flex items-center p-4 border-b rounded-t border-gray-50 dark:border-zinc-700">
                            <h5 class="text-lg font-semibold text-gray-900 dark:text-gray-100 " id="modal-title">Add Event
                            </h5>
                            <button
                                class="inline-flex items-center px-2 py-1 ml-auto text-sm text-gray-400 border-transparent rounded-lg btn hover:bg-gray-50/50 hover:text-gray-900 dark:text-gray-100 dark:hover:bg-zinc-600"
                                type="button" id="eventModal-close" data-tw-dismiss="modal">
                                <i class="text-xl text-gray-500 mdi mdi-close dark:text-zinc-100/60"></i>
                            </button>
                        </div>

                        <form class="needs-validation" name="event-form" id="form-event" autocomplete="off" novalidate>
                            <div class="p-6 space-y-6">
                                <div>
                                    <label for="event-title"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-100 ">Event
                                        Name</label>
                                    <input type="text" id="event-title"
                                        class="border border-gray-100 text-gray-900 dark:text-gray-100 text-sm rounded focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-zinc-700 dark:border-zinc-600 dark:placeholder-gray-400 dark:placeholder:text-zinc-100/60 dark:text-"
                                        placeholder="Insert Event Name" required>
                                </div>

                                <div>
                                    <label for="event-category"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-100 ">Category</label>
                                    <select type="text" id="event-category"
                                        class="border border-gray-100 text-gray-900 dark:text-gray-100 text-sm rounded focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-zinc-700 dark:border-zinc-600 dark:placeholder-gray-400 dark:placeholder:text-zinc-100/60 dark:text-"
                                        required>
                                        <option selected> --Select-- </option>
                                        <option value="bg-red-500">Danger</option>
                                        <option value="bg-green-500">Success</option>
                                        <option value="bg-violet-500">Primary</option>
                                        <option value="bg-sky-500">Info</option>
                                        <option value="bg-zinc-800">Dark</option>
                                        <option value="bg-yellow-500">Warning</option>
                                    </select>
                                </div>
                            </div>

                            <div class="px-4 py-3 bg-gray-50 sm:flex sm:px-6 dark:bg-zinc-700">
                                <button type="button"
                                    class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-white bg-red-600 border border-transparent rounded-md shadow-sm btn hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm dark:focus:ring-red-500/30"
                                    id="btn-delete-event">Delete</button>
                                <button type="button"
                                    class="inline-flex justify-center w-full px-4 py-2 mt-3 ml-auto text-base font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm btn dark:text-gray-100 hover:bg-gray-50/50 focus:outline-none focus:ring-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm dark:bg-zinc-700 dark:border-zinc-600 dark:hover:bg-zinc-600 dark:focus:bg-zinc-600 dark:focus:ring-zinc-700 dark:focus:ring-gray-500/20"
                                    data-tw-dismiss="modal">Cancel</button>
                                <button type="submit"
                                    class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-white bg-green-600 border border-transparent rounded-md shadow-sm btn hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm dark:focus:ring-green-500/30"
                                    id="btn-save-event">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end event modal -->
@endsection
@section('scripts')
    <!-- plugin js -->
    <script src="{{ URL::asset('build/libs/fullcalendar/index.global.min.js') }}"></script>

    <!-- calendar init -->
    <script src="{{ URL::asset('build/js/pages/calendar.init.js') }}"></script>
@endsection
