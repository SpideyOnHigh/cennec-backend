@extends('layouts.master')
@section('title')
    {{ __('Alerts') }}
@endsection
@section('content')
    <div
        class="main-content group-data-[sidebar-size=sm]:ml-[70px] group-data-[sidebar-size=sm]:group-data-[dir=rtl]:ml-0 group-data-[sidebar-size=sm]:group-data-[dir=rtl]:mr-[70px]">
        <div class="page-content dark:bg-zinc-700">

            <div class="container-fluid px-[0.625rem]">

                <x-page-title title="Alerts" pagetitle="Components" />

                <div class="grid grid-cols-1">
                    <div class="card dark:bg-zinc-800 dark:border-zinc-600">
                        <div class="card-body border-b border-gray-100 dark:border-zinc-600">
                            <h6 class="mb-1 text-gray-700 text-15 dark:text-gray-100">Default Alerts</h6>
                        </div>
                        <div class="card-body">
                            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                                <div>
                                    <div
                                        class="relative px-5 py-3 mb-3 border rounded bg-violet-50 text-violet-700 border-violet-100">
                                        <p>A simple primary alert—check it out!</p>
                                    </div>
                                    <div
                                        class="relative px-5 py-3 mb-3 text-gray-700 border border-gray-100 rounded bg-gray-50">
                                        <p>A simple primary alert—check it out!</p>
                                    </div>
                                    <div
                                        class="relative px-5 py-3 mb-3 text-green-700 border border-green-100 rounded bg-green-50">
                                        <p>A simple primary alert—check it out!</p>
                                    </div>
                                    <div class="relative px-5 py-3 text-red-700 border border-red-100 rounded bg-red-50">
                                        <p>A simple primary alert—check it out!</p>
                                    </div>
                                </div>
                                <div>
                                    <div
                                        class="relative px-5 py-3 mb-3 text-yellow-700 border border-yellow-100 rounded bg-yellow-50">
                                        <p>A simple primary alert—check it out!</p>
                                    </div>
                                    <div
                                        class="relative px-5 py-3 mb-3 border rounded bg-sky-50 text-sky-700 border-sky-100">
                                        <p>A simple primary alert—check it out!</p>
                                    </div>
                                    <div
                                        class="relative px-5 py-3 mb-3 text-gray-400 border rounded bg-gray-50 border-gray-50">
                                        <p>A simple primary alert—check it out!</p>
                                    </div>
                                    <div
                                        class="relative px-5 py-3 border rounded bg-neutral-300 text-neutral-800 border-neutral-400">
                                        <p>A simple primary alert—check it out!</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="grid grid-cols-1">
                    <div class="card dark:bg-zinc-800 dark:border-zinc-600">
                        <div class="card-body border-b border-gray-100 dark:border-zinc-600">
                            <h6 class="mb-1 text-gray-700 text-15 dark:text-gray-100">Border Alerts</h6>
                        </div>
                        <div class="card-body">
                            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                                <div class="space-y-4">
                                    <div
                                        class="relative flex items-center px-5 py-2 border rounded text-violet-500 border-violet-200 alert-dismissible">
                                        <p>A simple primary alert—check it out!</p>
                                        <button class="text-lg alert-close ltr:ml-auto rtl:mr-auto text-violet-400"><i
                                                class="mdi mdi-close"></i></button>
                                    </div>
                                    <div
                                        class="relative flex items-center px-5 py-2 text-gray-500 border border-gray-200 rounded dark:text-zinc-100 alert-dismissible">
                                        <p>A simple primary alert—check it out!</p>
                                        <button class="text-lg text-gray-400 alert-close ltr:ml-auto rtl:mr-auto"><i
                                                class="mdi mdi-close"></i></button>
                                    </div>
                                    <div
                                        class="relative flex items-center px-5 py-2 text-green-500 border border-green-200 rounded alert-dismissible">
                                        <p>A simple primary alert—check it out!</p>
                                        <button class="text-lg text-green-400 alert-close ltr:ml-auto rtl:mr-auto"><i
                                                class="mdi mdi-close"></i></button>
                                    </div>
                                    <div
                                        class="relative flex items-center px-5 py-2 text-red-500 border border-red-200 rounded alert-dismissible">
                                        <p>A simple primary alert—check it out!</p>
                                        <button class="text-lg text-red-400 alert-close ltr:ml-auto rtl:mr-auto"><i
                                                class="mdi mdi-close"></i></button>
                                    </div>
                                </div>
                                <div class="space-y-4">
                                    <div
                                        class="relative flex items-center px-5 py-2 text-yellow-500 border border-yellow-200 rounded alert-dismissible">
                                        <p>A simple primary alert—check it out!</p>
                                        <button class="text-lg text-yellow-400 alert-close ltr:ml-auto rtl:mr-auto"><i
                                                class="mdi mdi-close"></i></button>
                                    </div>
                                    <div
                                        class="relative flex items-center px-5 py-2 border rounded text-sky-500 border-sky-200 alert-dismissible">
                                        <p>A simple primary alert—check it out!</p>
                                        <button class="text-lg alert-close ltr:ml-auto rtl:mr-auto text-sky-400"><i
                                                class="mdi mdi-close"></i></button>
                                    </div>
                                    <div
                                        class="relative flex items-center px-5 py-2 text-gray-500 border rounded dark:text-zinc-100 border-gray-50 alert-dismissible">
                                        <p>A simple primary alert—check it out!</p>
                                        <button class="text-lg text-gray-400 alert-close ltr:ml-auto rtl:mr-auto"><i
                                                class="mdi mdi-close"></i></button>
                                    </div>
                                    <div
                                        class="relative flex items-center px-5 py-2 border rounded text-neutral-500 dark:text-zinc-100 border-neutral-200 alert-dismissible">
                                        <p>A simple primary alert—check it out!</p>
                                        <button class="text-lg alert-close ltr:ml-auto rtl:mr-auto text-neutral-400"><i
                                                class="mdi mdi-close"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1">
                    <div class="card dark:bg-zinc-800 dark:border-zinc-600">
                        <div class="card-body border-b border-gray-100 dark:border-zinc-600">
                            <h6 class="mb-1 text-gray-700 text-15 dark:text-gray-100">With description</h6>
                        </div>
                        <div class="card-body">
                            <div class="grid grid-cols-1 gap-4">
                                <div class="flex px-5 py-3 border rounded bg-violet-50 text-violet-700 border-violet-100">
                                    <i class="text-xl bx bx-smile ltr:mr-2 rtl:ml-2"></i>
                                    <div>
                                        <h6 class="text-15">Primary Alert</h6>
                                        <p class="mt-2">More info about this info alert goes here. This example text is
                                            going to run a bit longer so that you can see how spacing within an alert works
                                            with this kind of content.</p>
                                    </div>
                                </div>
                                <div class="flex px-5 py-3 text-green-700 border border-green-100 rounded bg-green-50">
                                    <i class="text-xl bx bx-loader ltr:mr-2 rtl:ml-2"></i>
                                    <div>
                                        <h6 class="text-15">Success Alert</h6>
                                        <p class="mt-2">More info about this info alert goes here. This example text is
                                            going to run a bit longer so that you can see how spacing within an alert works
                                            with this kind of content.</p>
                                    </div>
                                </div>
                                <div class="flex px-5 py-3 text-red-700 border border-red-100 rounded bg-red-50">
                                    <i class="text-xl bx bx-block ltr:mr-2 rtl:ml-2"></i>
                                    <div>
                                        <h6 class="text-15">Danger Alert</h6>
                                        <p class="mt-2">More info about this info alert goes here. This example text is
                                            going to run a bit longer so that you can see how spacing within an alert works
                                            with this kind of content.</p>
                                    </div>
                                </div>
                                <div class="flex px-5 py-3 text-yellow-700 border border-yellow-100 rounded bg-yellow-50">
                                    <i class="text-xl bx bx-error ltr:mr-2 rtl:ml-2"></i>
                                    <div>
                                        <h6 class="text-15">Warning Alert</h6>
                                        <p class="mt-2">More info about this info alert goes here. This example text is
                                            going to run a bit longer so that you can see how spacing within an alert works
                                            with this kind of content.</p>
                                    </div>
                                </div>
                                <div class="flex px-5 py-3 border rounded bg-sky-50 text-sky-700 border-sky-100">
                                    <i class="text-xl bx bx-hourglass ltr:mr-2 rtl:ml-2"></i>
                                    <div>
                                        <h6 class="text-15">Info Alert</h6>
                                        <p class="mt-2">More info about this info alert goes here. This example text is
                                            going to run a bit longer so that you can see how spacing within an alert works
                                            with this kind of content.</p>
                                    </div>
                                </div>
                                <div class="flex px-5 py-3 text-gray-700 border border-gray-100 rounded bg-gray-50">
                                    <i class="text-xl bx bx-check-double ltr:mr-2 rtl:ml-2"></i>
                                    <div>
                                        <h6 class="text-15">Light Alert</h6>
                                        <p class="mt-2">More info about this info alert goes here. This example text is
                                            going to run a bit longer so that you can see how spacing within an alert works
                                            with this kind of content.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1">
                    <div class="card dark:bg-zinc-800 dark:border-zinc-600">
                        <div class="card-body border-b border-gray-100 dark:border-zinc-600">
                            <h6 class="mb-1 text-gray-700 text-15 dark:text-gray-100">With List Alerts</h6>
                        </div>
                        <div class="card-body">
                            <div class="grid grid-cols-1 gap-6">
                                <div class="flex p-4 text-sm rounded text-violet-700 bg-violet-50 " role="alert">
                                    <i class="-mt-1 text-xl bx bx-smile ltr:mr-3 rtl:ml-3"></i>
                                    <span class="sr-only">Info</span>
                                    <div>
                                        <span class="font-medium">There were 2 warnings with your submission</span>
                                        <ul class="mt-1.5 ml-4 list-disc list-inside">
                                            <li>Could not load content for this code..</li>
                                            <li>Failed to load resource..</li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="flex p-4 text-sm text-green-700 rounded bg-green-50 " role="alert">
                                    <i class="-mt-1 text-xl bx bx-loader ltr:mr-3 rtl:ml-3"></i>
                                    <span class="sr-only">Info</span>
                                    <div>
                                        <span class="font-medium">There were 2 warnings with your submission</span>
                                        <ul class="mt-1.5 ml-4 list-disc list-inside">
                                            <li>Could not load content for this code..</li>
                                            <li>Failed to load resource..</li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="flex p-4 text-sm text-red-700 rounded bg-red-50 " role="alert">
                                    <i class="-mt-1 text-xl bx bx-block ltr:mr-3 rtl:ml-3"></i>
                                    <span class="sr-only">Info</span>
                                    <div>
                                        <span class="font-medium">There were 2 errors with your submission</span>
                                        <ul class="mt-1.5 ml-4 list-disc list-inside">
                                            <li>Your password must be at least 8 characters</li>
                                            <li>Your password must include at least one pro wrestlling finishing move</li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="flex p-4 text-sm text-yellow-700 rounded bg-yellow-50" role="alert">
                                    <i class="-mt-1 text-xl bx bx-error ltr:mr-3 rtl:ml-3"></i>
                                    <span class="sr-only">Info</span>
                                    <div>
                                        <span class="font-medium">There were 2 warnings with your submission</span>
                                        <ul class="mt-1.5 ml-4 list-disc list-inside">
                                            <li>Could not load content for this code..</li>
                                            <li>Failed to load resource..</li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="flex p-4 text-sm rounded text-sky-700 bg-sky-50 " role="alert">
                                    <i class="-mt-1 text-xl bx bx-hourglass ltr:mr-3 rtl:ml-3"></i>
                                    <span class="sr-only">Info</span>
                                    <div>
                                        <span class="font-medium">There were 2 warnings with your submission</span>
                                        <ul class="mt-1.5 ml-4 list-disc list-inside">
                                            <li>Could not load content for this code..</li>
                                            <li>Failed to load resource..</li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="flex p-4 text-sm text-gray-700 rounded bg-gray-50 " role="alert">
                                    <i class="-mt-1 text-xl bx bx-check-double ltr:mr-3 rtl:ml-3"></i>
                                    <span class="sr-only">Info</span>
                                    <div>
                                        <span class="font-medium">There were 2 warnings with your submission</span>
                                        <ul class="mt-1.5 ml-4 list-disc list-inside">
                                            <li>Could not load content for this code..</li>
                                            <li>Failed to load resource..</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1">
                    <div class="card dark:bg-zinc-800 dark:border-zinc-600">
                        <div class="card-body border-b border-gray-100 dark:border-zinc-600">
                            <h6 class="mb-1 text-gray-700 text-15 dark:text-gray-100">With actions</h6>
                        </div>
                        <div class="card-body">
                            <div class="grid grid-cols-1 gap-6">
                                <div class="flex p-4 text-sm rounded text-violet-700 bg-violet-50 " role="alert">
                                    <i class="-mt-1 text-xl bx bx-smile ltr:mr-3 rtl:ml-3"></i>
                                    <span class="sr-only">Info</span>
                                    <div>
                                        <span class="font-semibold">Order Completed</span>
                                        <p class="mt-1">More info about this info warning goes here. This example text is
                                            going to run a bit longer so that you can see how spacing within an alert works
                                            with this kind of content.</p>

                                        <div class="flex gap-3 mt-4">
                                            <button type="button"
                                                class="font-semibold btn border-violet-500 hover:bg-violet-500 hover:text-white">
                                                View Status
                                            </button>
                                            <button type="button"
                                                class="font-semibold text-gray-400 border-transparent btn hover:border hover:border-gray-400">
                                                Dismiss
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex p-4 text-sm text-green-700 rounded bg-green-50 " role="alert">
                                    <i class="-mt-1 text-xl bx bx-loader ltr:mr-3 rtl:ml-3"></i>
                                    <span class="sr-only">Info</span>
                                    <div>
                                        <span class="font-semibold">Order Completed</span>
                                        <p class="mt-1">More info about this info warning goes here. This example text is
                                            going to run a bit longer so that you can see how spacing within an alert works
                                            with this kind of content.</p>
                                        <div class="flex gap-3 mt-4">
                                            <button type="button"
                                                class="font-semibold border-green-700 btn hover:bg-green-700 hover:text-white">
                                                View Status
                                            </button>
                                            <button type="button"
                                                class="font-semibold text-gray-400 border-transparent btn hover:border hover:border-gray-400">
                                                Dismiss
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex p-4 text-sm text-red-700 rounded bg-red-50 " role="alert">
                                    <i class="-mt-1 text-xl bx bx-block ltr:mr-3 rtl:ml-3"></i>
                                    <span class="sr-only">Info</span>
                                    <div>
                                        <span class="font-semibold">Order Completed</span>
                                        <p class="mt-1">More info about this info warning goes here. This example text is
                                            going to run a bit longer so that you can see how spacing within an alert works
                                            with this kind of content.</p>
                                        <div class="flex gap-3 mt-4">
                                            <button type="button"
                                                class="font-semibold border-red-700 btn hover:bg-red-700 hover:text-white">
                                                View Status
                                            </button>
                                            <button type="button"
                                                class="font-semibold text-gray-400 border-transparent btn hover:border hover:border-gray-400">
                                                Dismiss
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex p-4 text-sm text-yellow-700 rounded bg-yellow-50" role="alert">
                                    <i class="-mt-1 text-xl bx bx-error ltr:mr-3 rtl:ml-3"></i>
                                    <span class="sr-only">Info</span>
                                    <div>
                                        <span class="font-semibold">Order Completed</span>
                                        <p class="mt-1">More info about this info warning goes here. This example text is
                                            going to run a bit longer so that you can see how spacing within an alert works
                                            with this kind of content.</p>
                                        <div class="flex gap-3 mt-4">
                                            <button type="button"
                                                class="font-semibold border-yellow-700 btn hover:bg-yellow-700 hover:text-white">
                                                View Status
                                            </button>
                                            <button type="button"
                                                class="font-semibold text-gray-400 border-transparent btn hover:border hover:border-gray-400">
                                                Dismiss
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex p-4 text-sm rounded text-sky-700 bg-sky-50" role="alert">
                                    <i class="-mt-1 text-xl bx bx-hourglass ltr:mr-3 rtl:ml-3"></i>
                                    <span class="sr-only">Info</span>
                                    <div>
                                        <span class="font-semibold">Order Completed</span>
                                        <p class="mt-1">More info about this info warning goes here. This example text is
                                            going to run a bit longer so that you can see how spacing within an alert works
                                            with this kind of content.</p>
                                        <div class="flex gap-3 mt-4">
                                            <button type="button"
                                                class="font-semibold btn border-sky-700 hover:bg-sky-700 hover:text-white">
                                                View Status
                                            </button>
                                            <button type="button"
                                                class="font-semibold text-gray-400 border-transparent btn hover:border hover:border-gray-400">
                                                Dismiss
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex p-4 text-sm text-gray-700 rounded bg-gray-50 " role="alert">
                                    <i class="-mt-1 text-xl bx bx-check-double ltr:mr-3 rtl:ml-3"></i>
                                    <span class="sr-only">Info</span>
                                    <div>
                                        <span class="font-semibold">Order Completed</span>
                                        <p class="mt-1">More info about this info warning goes here. This example text is
                                            going to run a bit longer so that you can see how spacing within an alert works
                                            with this kind of content.</p>
                                        <div class="flex gap-3 mt-4">
                                            <button type="button"
                                                class="font-semibold border-gray-700 btn hover:bg-gray-700 hover:text-white">
                                                View Status
                                            </button>
                                            <button type="button"
                                                class="font-semibold text-gray-400 border-transparent btn hover:border hover:border-gray-400">
                                                Dismiss
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1">
                    <div class="card dark:bg-zinc-800 dark:border-zinc-600">
                        <div class="card-body border-b border-gray-100 dark:border-zinc-600">
                            <h6 class="mb-1 text-gray-700 text-15 dark:text-gray-100">With link on right</h6>
                        </div>
                        <div class="card-body">
                            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                                <div class="space-y-4">
                                    <div class="flex items-center px-5 py-2 rounded bg-violet-50 alert-dismissible">
                                        <p class="text-violet-600"><i
                                                class='text-xl align-middle bx bx-check-circle ltr:mr-2 rtl:ml-2'></i><span
                                                class="align-middle">A new software update is available.</span> </p>
                                        <button
                                            class="mt-1 font-semibold align-middle alert-close ltr:ml-auto rtl:mr-auto text-violet-600">Details<i
                                                class="ml-1 text-lg align-middle bx bx-right-arrow-alt"></i></button>
                                    </div>
                                    <div class="flex items-center px-5 py-2 rounded bg-gray-50 alert-dismissible">
                                        <p class="text-gray-600"><i
                                                class='text-xl align-middle bx bx-check-circle ltr:mr-2 rtl:ml-2'></i><span
                                                class="align-middle">A new software update is available.</span> </p>
                                        <button
                                            class="mt-1 font-semibold text-gray-600 align-middle alert-close ltr:ml-auto rtl:mr-auto">Details<i
                                                class="ml-1 text-lg align-middle bx bx-right-arrow-alt"></i></button>
                                    </div>
                                    <div class="flex items-center px-5 py-2 rounded bg-green-50 alert-dismissible">
                                        <p class="text-green-600"><i
                                                class='text-xl align-middle bx bx-check-circle ltr:mr-2 rtl:ml-2'></i><span
                                                class="align-middle">A new software update is available.</span> </p>
                                        <button
                                            class="mt-1 font-semibold text-green-600 align-middle alert-close ltr:ml-auto rtl:mr-auto">Details<i
                                                class="ml-1 text-lg align-middle bx bx-right-arrow-alt"></i></button>
                                    </div>
                                    <div class="flex items-center px-5 py-2 rounded bg-red-50 alert-dismissible">
                                        <p class="text-red-600"><i
                                                class='text-xl align-middle bx bx-check-circle ltr:mr-2 rtl:ml-2'></i><span
                                                class="align-middle">A new software update is available.</span> </p>
                                        <button
                                            class="mt-1 font-semibold text-red-600 align-middle alert-close ltr:ml-auto rtl:mr-auto">Details<i
                                                class="ml-1 text-lg align-middle bx bx-right-arrow-alt"></i></button>
                                    </div>
                                </div>
                                <div class="space-y-4">
                                    <div class="flex items-center px-5 py-2 rounded bg-yellow-50 alert-dismissible">
                                        <p class="text-yellow-600"><i
                                                class='text-xl align-middle bx bx-check-circle ltr:mr-2 rtl:ml-2'></i><span
                                                class="align-middle">A new software update is available.</span> </p>
                                        <button
                                            class="mt-1 font-semibold text-yellow-600 align-middle alert-close ltr:ml-auto rtl:mr-auto">Details<i
                                                class="ml-1 text-lg align-middle bx bx-right-arrow-alt"></i></button>
                                    </div>
                                    <div class="flex items-center px-5 py-2 rounded bg-sky-50 alert-dismissible">
                                        <p class="text-sky-600"><i
                                                class='text-xl align-middle bx bx-check-circle ltr:mr-2 rtl:ml-2'></i><span
                                                class="align-middle">A new software update is available.</span> </p>
                                        <button
                                            class="mt-1 font-semibold align-middle alert-close ltr:ml-auto rtl:mr-auto text-sky-600">Details<i
                                                class="ml-1 text-lg align-middle bx bx-right-arrow-alt"></i></button>
                                    </div>
                                    <div class="flex items-center px-5 py-2 rounded bg-gray-50/50 alert-dismissible">
                                        <p class="text-gray-600"><i
                                                class='text-xl align-middle bx bx-check-circle ltr:mr-2 rtl:ml-2'></i><span
                                                class="align-middle">A new software update is available.</span> </p>
                                        <button
                                            class="mt-1 font-semibold text-gray-600 align-middle alert-close ltr:ml-auto rtl:mr-auto">Details<i
                                                class="ml-1 text-lg align-middle bx bx-right-arrow-alt"></i></button>
                                    </div>
                                    <div class="flex items-center px-5 py-2 rounded bg-neutral-300 alert-dismissible">
                                        <p class="text-neutral-700"><i
                                                class='text-xl align-middle bx bx-check-circle ltr:mr-2 rtl:ml-2'></i><span
                                                class="align-middle">A new software update is available.</span> </p>
                                        <button
                                            class="mt-1 font-semibold align-middle alert-close ltr:ml-auto rtl:mr-auto text-neutral-700">Details<i
                                                class="ml-1 text-lg align-middle bx bx-right-arrow-alt"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1">
                    <div class="card dark:bg-zinc-800 dark:border-zinc-600">
                        <div class="card-body border-b border-gray-100 dark:border-zinc-600">
                            <h6 class="mb-1 text-gray-700 text-15 dark:text-gray-100">With dismiss button</h6>
                        </div>
                        <div class="card-body">
                            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                                <div class="space-y-4">
                                    <div
                                        class="px-5 py-[9px] flex items-center bg-violet-50 border border-violet-100 rounded alert-dismissible">
                                        <i
                                            class="text-lg align-middle mdi mdi-check-all ltr:mr-2 rtl:ml-2 text-violet-700"></i>
                                        <p class="text-violet-700">A simple success alert—check it out!</p>
                                        <button class="text-lg alert-close ltr:ml-auto rtl:mr-auto text-violet-400"><i
                                                class="mdi mdi-close"></i></button>
                                    </div>
                                    <div
                                        class="px-5 py-[9px] flex items-center bg-gray-100 border border-gray-200 rounded alert-dismissible">
                                        <i
                                            class="text-lg text-gray-700 align-middle mdi mdi-arrow-all ltr:mr-2 rtl:ml-2"></i>
                                        <p class="text-gray-700">A simple success alert—check it out!</p>
                                        <button class="text-lg text-gray-400 alert-close ltr:ml-auto rtl:mr-auto"><i
                                                class="mdi mdi-close"></i></button>
                                    </div>
                                    <div
                                        class="px-5 py-[9px] flex items-center bg-green-50 border border-green-100 rounded alert-dismissible">
                                        <i
                                            class="text-lg text-green-700 align-middle mdi mdi-baby-face-outline ltr:mr-2 rtl:ml-2"></i>
                                        <p class="text-green-700">A simple success alert—check it out!</p>
                                        <button class="text-lg text-green-400 alert-close ltr:ml-auto rtl:mr-auto"><i
                                                class="mdi mdi-close"></i></button>
                                    </div>
                                    <div
                                        class="px-5 py-[9px] flex items-center bg-red-50 border border-red-100 rounded alert-dismissible">
                                        <i
                                            class="text-lg text-red-700 align-middle mdi mdi-block-helper ltr:mr-2 rtl:ml-2"></i>
                                        <p class="text-red-700">A simple success alert—check it out!</p>
                                        <button class="text-lg text-red-400 alert-close ltr:ml-auto rtl:mr-auto"><i
                                                class="mdi mdi-close"></i></button>
                                    </div>
                                </div>
                                <div class="space-y-4">
                                    <div
                                        class="px-5 py-[9px] flex items-center bg-yellow-50 border border-yellow-100 rounded alert-dismissible">
                                        <i
                                            class="text-lg text-yellow-700 align-middle mdi mdi-alert-outline ltr:mr-2 rtl:ml-2"></i>
                                        <p class="text-yellow-700">A simple success alert—check it out!</p>
                                        <button class="text-lg text-yellow-400 alert-close ltr:ml-auto rtl:mr-auto"><i
                                                class="mdi mdi-close"></i></button>
                                    </div>
                                    <div
                                        class="px-5 py-[9px] flex items-center bg-sky-50 border border-sky-100 rounded alert-dismissible">
                                        <i
                                            class="text-lg align-middle mdi mdi-alert-circle-outline ltr:mr-2 rtl:ml-2 text-sky-700"></i>
                                        <p class="text-sky-700">A simple success alert—check it out!</p>
                                        <button class="text-lg alert-close ltr:ml-auto rtl:mr-auto text-sky-400"><i
                                                class="mdi mdi-close"></i></button>
                                    </div>
                                    <div
                                        class="px-5 py-[9px] flex items-center bg-gray-50/50 dark:bg-zinc-600 dark:border-zinc-600 border border-gray-50 rounded alert-dismissible">
                                        <i
                                            class="text-lg text-gray-400 align-middle mdi mdi-skull-outline ltr:mr-2 rtl:ml-2"></i>
                                        <p class="text-gray-400">A simple success alert—check it out!</p>
                                        <button class="text-lg text-gray-400 alert-close ltr:ml-auto rtl:mr-auto"><i
                                                class="mdi mdi-close"></i></button>
                                    </div>
                                    <div
                                        class="px-5 py-[9px] flex items-center bg-neutral-200 border border-neutral-300 rounded alert-dismissible">
                                        <i
                                            class="text-lg align-middle mdi mdi-check-circle-outline ltr:mr-2 rtl:ml-2 text-neutral-700"></i>
                                        <p class="text-neutral-700">A simple success alert—check it out!</p>
                                        <button class="text-lg alert-close ltr:ml-auto rtl:mr-auto text-neutral-700"><i
                                                class="mdi mdi-close"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="grid grid-cols-1">
                    <div class="card dark:bg-zinc-800 dark:border-zinc-600">
                        <div class="card-body border-b border-gray-100 dark:border-zinc-600">
                            <h6 class="mb-1 text-gray-700 text-15 dark:text-gray-100">With accent border</h6>
                        </div>
                        <div class="card-body">
                            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                                <div class="space-y-4">
                                    <div
                                        class="px-5 py-2.5 flex items-center bg-violet-50 border-l-2  border-violet-500 rounded rounded-l-none alert-dismissible">
                                        <i
                                            class="text-lg align-middle mdi mdi-check-all ltr:mr-2 rtl:ml-2 text-violet-700"></i>
                                        <p class="text-violet-700">A simple success alert—check it out!</p>
                                        <button class="text-lg alert-close ltr:ml-auto rtl:mr-auto text-violet-400"><i
                                                class="mdi mdi-close"></i></button>
                                    </div>
                                    <div
                                        class="px-5 py-2.5 flex items-center bg-gray-100 border-l-2  border-gray-500 rounded rounded-l-none alert-dismissible">
                                        <i
                                            class="text-lg text-gray-700 align-middle mdi mdi-arrow-all ltr:mr-2 rtl:ml-2"></i>
                                        <p class="text-gray-700">A simple success alert—check it out!</p>
                                        <button class="text-lg text-gray-400 alert-close ltr:ml-auto rtl:mr-auto"><i
                                                class="mdi mdi-close"></i></button>
                                    </div>
                                    <div
                                        class="px-5 py-2.5 flex items-center bg-green-50 border-l-2  border-green-500 rounded rounded-l-none alert-dismissible">
                                        <i
                                            class="text-lg text-green-700 align-middle mdi mdi-baby-face-outline ltr:mr-2 rtl:ml-2"></i>
                                        <p class="text-green-700">A simple success alert—check it out!</p>
                                        <button class="text-lg text-green-400 alert-close ltr:ml-auto rtl:mr-auto"><i
                                                class="mdi mdi-close"></i></button>
                                    </div>
                                    <div
                                        class="px-5 py-2.5 flex items-center bg-red-50 border-l-2  border-red-500 rounded rounded-l-none alert-dismissible">
                                        <i
                                            class="text-lg text-red-700 align-middle mdi mdi-block-helper ltr:mr-2 rtl:ml-2"></i>
                                        <p class="text-red-700">A simple success alert—check it out!</p>
                                        <button class="text-lg text-red-400 alert-close ltr:ml-auto rtl:mr-auto"><i
                                                class="mdi mdi-close"></i></button>
                                    </div>
                                </div>
                                <div class="space-y-4">
                                    <div
                                        class="px-5 py-2.5 flex items-center bg-yellow-50 border-l-2  border-yellow-500 rounded rounded-l-none alert-dismissible">
                                        <i
                                            class="text-lg text-yellow-700 align-middle mdi mdi-alert-outline ltr:mr-2 rtl:ml-2"></i>
                                        <p class="text-yellow-700">A simple success alert—check it out!</p>
                                        <button class="text-lg text-yellow-400 alert-close ltr:ml-auto rtl:mr-auto"><i
                                                class="mdi mdi-close"></i></button>
                                    </div>
                                    <div
                                        class="px-5 py-2.5 flex items-center bg-sky-50 border-l-2  border-sky-500 rounded rounded-l-none alert-dismissible">
                                        <i
                                            class="text-lg align-middle mdi mdi-alert-circle-outline ltr:mr-2 rtl:ml-2 text-sky-700"></i>
                                        <p class="text-sky-700">A simple success alert—check it out!</p>
                                        <button class="text-lg alert-close ltr:ml-auto rtl:mr-auto text-sky-400"><i
                                                class="mdi mdi-close"></i></button>
                                    </div>
                                    <div
                                        class="px-5 py-2.5 flex items-center bg-gray-50/50 dark:bg-zinc-700 dark:border-zinc-500 border-l-2  border-gray-300 rounded rounded-l-none alert-dismissible">
                                        <i
                                            class="text-lg text-gray-400 align-middle mdi mdi-skull-outline ltr:mr-2 rtl:ml-2"></i>
                                        <p class="text-gray-400">A simple success alert—check it out!</p>
                                        <button class="text-lg text-gray-400 alert-close ltr:ml-auto rtl:mr-auto"><i
                                                class="mdi mdi-close"></i></button>
                                    </div>
                                    <div
                                        class="px-5 py-2.5 flex items-center bg-neutral-200 border-l-2  border-neutral-400 rounded rounded-l-none alert-dismissible">
                                        <i
                                            class="text-lg align-middle mdi mdi-check-circle-outline ltr:mr-2 rtl:ml-2 text-neutral-700"></i>
                                        <p class="text-neutral-700">A simple success alert—check it out!</p>
                                        <button class="text-lg alert-close ltr:ml-auto rtl:mr-auto text-neutral-700"><i
                                                class="mdi mdi-close"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="grid grid-cols-1">
                    <div class="card dark:bg-zinc-800 dark:border-zinc-600">
                        <div class="grid grid-cols-1 lg:grid-cols-2">
                            <div>
                                <div class="card-body border-b border-gray-100 dark:border-zinc-600">
                                    <h6 class="mb-1 text-gray-700 text-15 dark:text-gray-100">Label Icon Arrow Alerts</h6>
                                </div>
                                <div class="card-body">
                                    <div class="space-y-4">
                                        <div class="flex items-center rounded bg-green-50 alert-dismissible">
                                            <div
                                                class="relative w-12 h-12 text-center bg-green-400 ltr:rounded-l rtl:rounded-r">
                                                <div
                                                    class="after:content-[''] after:border-[6px] after:border-transparent ltr:after:border-l-green-400 rtl:after:border-r-green-400 after:absolute ltr:after:-right-3 rtl:after:-left-3 after:top-[1.15rem]">
                                                </div>
                                                <i class="mdi mdi-check-all align-middle text-white leading-[3.5]"></i>
                                            </div>
                                            <p class="text-green-700 ltr:ml-4 rtl:mr-4">A simple success alert—check it
                                                out!</p>
                                            <button
                                                class="text-lg alert-close ltr:ml-auto rtl:mr-auto text-zinc-500 ltr:pr-5 rtl:pl-5"><i
                                                    class="mdi mdi-close"></i></button>
                                        </div>
                                        <div class="flex items-center rounded bg-red-50 alert-dismissible">
                                            <div
                                                class="relative w-12 h-12 text-center bg-red-400 ltr:rounded-l rtl:rounded-r">
                                                <div
                                                    class="after:content-[''] after:border-[6px] after:border-transparent ltr:after:border-l-red-400 rtl:after:border-r-red-400 after:absolute ltr:after:-right-3 rtl:after:-left-3 after:top-[1.15rem]">
                                                </div>
                                                <i class="mdi mdi-block-helper align-middle text-white leading-[3.5]"></i>
                                            </div>
                                            <p class="text-red-700 ltr:ml-4 rtl:mr-4">A simple success alert—check it out!
                                            </p>
                                            <button
                                                class="text-lg alert-close ltr:ml-auto rtl:mr-auto text-zinc-500 ltr:pr-5 rtl:pl-5"><i
                                                    class="mdi mdi-close"></i></button>
                                        </div>
                                        <div class="flex items-center rounded bg-yellow-50 alert-dismissible">
                                            <div
                                                class="relative w-12 h-12 text-center bg-yellow-400 ltr:rounded-l rtl:rounded-r">
                                                <div
                                                    class="after:content-[''] after:border-[6px] after:border-transparent ltr:after:border-l-yellow-400 rtl:after:border-r-yellow-400 after:absolute ltr:after:-right-3 rtl:after:-left-3 after:top-[1.15rem]">
                                                </div>
                                                <i class="mdi mdi-alert-outline align-middle text-white leading-[3.5]"></i>
                                            </div>
                                            <p class="text-yellow-700 ltr:ml-4 rtl:mr-4">A simple success alert—check it
                                                out!</p>
                                            <button
                                                class="text-lg alert-close ltr:ml-auto rtl:mr-auto text-zinc-500 ltr:pr-5 rtl:pl-5"><i
                                                    class="mdi mdi-close"></i></button>
                                        </div>
                                        <div class="flex items-center rounded bg-sky-50 alert-dismissible">
                                            <div
                                                class="relative w-12 h-12 text-center ltr:rounded-l rtl:rounded-r bg-sky-400">
                                                <div
                                                    class="after:content-[''] after:border-[6px] after:border-transparent ltr:after:border-l-sky-400 rtl:after:border-r-sky-400 after:absolute ltr:after:-right-3 rtl:after:-left-3 after:top-[1.15rem]">
                                                </div>
                                                <i
                                                    class="mdi mdi-alert-circle-outline align-middle text-white leading-[3.5]"></i>
                                            </div>
                                            <p class="text-sky-700 ltr:ml-4 rtl:mr-4">A simple success alert—check it out!
                                            </p>
                                            <button
                                                class="text-lg alert-close ltr:ml-auto rtl:mr-auto text-zinc-500 ltr:pr-5 rtl:pl-5"><i
                                                    class="mdi mdi-close"></i></button>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div>
                                <div class="card-body border-b border-gray-100 dark:border-zinc-600">
                                    <h6 class="mb-1 text-gray-700 text-15 dark:text-gray-100">Top Border Alerts</h6>
                                </div>
                                <div class="card-body">
                                    <div class="space-y-4">
                                        <div
                                            class="flex items-center px-5 py-2 border-t-2  rounded rounded-t-none bg-violet-50 border-violet-500 alert-dismissible">
                                            <i
                                                class="text-lg align-middle mdi mdi-check-all ltr:mr-2 rtl:ml-2 text-violet-700"></i>
                                            <p class="text-violet-700">A simple success alert—check it out!</p>
                                            <button class="text-lg alert-close ltr:ml-auto rtl:mr-auto text-violet-400"><i
                                                    class="mdi mdi-close"></i></button>
                                        </div>
                                        <div
                                            class="flex items-center px-5 py-2 border-t-2  border-red-500 rounded rounded-t-none bg-red-50 alert-dismissible">
                                            <i
                                                class="text-lg text-red-700 align-middle mdi mdi-check-all ltr:mr-2 rtl:ml-2"></i>
                                            <p class="text-red-700">A simple success alert—check it out!</p>
                                            <button class="text-lg text-red-400 alert-close ltr:ml-auto rtl:mr-auto"><i
                                                    class="mdi mdi-close"></i></button>
                                        </div>
                                        <div
                                            class="flex items-center px-5 py-2 border-t-2  border-yellow-500 rounded rounded-t-none bg-yellow-50 alert-dismissible">
                                            <i
                                                class="text-lg text-yellow-700 align-middle mdi mdi-check-all ltr:mr-2 rtl:ml-2"></i>
                                            <p class="text-yellow-700">A simple success alert—check it out!</p>
                                            <button class="text-lg text-yellow-400 alert-close ltr:ml-auto rtl:mr-auto"><i
                                                    class="mdi mdi-close"></i></button>
                                        </div>
                                        <div
                                            class="flex items-center px-5 py-2 border-t-2  rounded rounded-t-none bg-sky-50 border-sky-500 alert-dismissible">
                                            <i
                                                class="text-lg align-middle mdi mdi-check-all ltr:mr-2 rtl:ml-2 text-sky-700"></i>
                                            <p class="text-sky-700">A simple success alert—check it out!</p>
                                            <button class="text-lg alert-close ltr:ml-auto rtl:mr-auto text-sky-400"><i
                                                    class="mdi mdi-close"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1">
                    <div class="card dark:bg-zinc-800 dark:border-zinc-600">
                        <div class="grid grid-cols-1">
                            <div class="card-body border-b border-gray-100 dark:border-zinc-600">
                                <h6 class="mb-1 text-gray-700 text-15 dark:text-gray-100">Alert Examples</h6>
                            </div>
                            <div class="card-body">
                                <div class="grid grid-cols-1 gap-6 lg:grid-cols-2 xl:grid-cols-4">
                                    <div class="relative px-6 py-3 text-center bg-green-100 border border-green-200 rounded alert-dismissible"
                                        role="alert">
                                        <button
                                            class="absolute text-lg alert-close ltr:ml-auto rtl:mr-auto text-zinc-500 top-2 right-3"><i
                                                class="mdi mdi-close"></i></button>
                                        <div class="mt-2 mb-4">
                                            <i class="text-6xl text-green-500 mdi mdi-check-all"></i>
                                        </div>
                                        <h5 class="text-green-500">Success</h5>
                                        <p class="mt-1 mb-4 text-green-800">A simple success alert</p>
                                    </div>

                                    <div class="relative px-6 py-3 text-center bg-red-100 border border-red-200 rounded alert-dismissible"
                                        role="alert">
                                        <button
                                            class="absolute text-lg alert-close ltr:ml-auto rtl:mr-auto text-zinc-500 top-2 right-3"><i
                                                class="mdi mdi-close"></i></button>
                                        <div class="mt-2 mb-4">
                                            <i class="text-6xl text-red-500 mdi mdi-block-helper"></i>
                                        </div>
                                        <h5 class="text-red-500">Success</h5>
                                        <p class="mt-1 mb-4 text-red-800">A simple success alert</p>
                                    </div>

                                    <div class="relative px-6 py-3 text-center bg-yellow-100 border border-yellow-200 rounded alert-dismissible"
                                        role="alert">
                                        <button
                                            class="absolute text-lg alert-close ltr:ml-auto rtl:mr-auto text-zinc-500 top-2 right-3"><i
                                                class="mdi mdi-close"></i></button>
                                        <div class="mt-2 mb-4">
                                            <i class="text-6xl text-yellow-500 mdi mdi-alert-outline"></i>
                                        </div>
                                        <h5 class="text-yellow-500">Success</h5>
                                        <p class="mt-1 mb-4 text-yellow-800">A simple success alert</p>
                                    </div>

                                    <div class="relative px-6 py-3 text-center border rounded bg-sky-100 border-sky-200 alert-dismissible"
                                        role="alert">
                                        <button
                                            class="absolute text-lg alert-close ltr:ml-auto rtl:mr-auto text-zinc-500 top-2 right-3"><i
                                                class="mdi mdi-close"></i></button>
                                        <div class="mt-2 mb-4">
                                            <i class="text-6xl mdi mdi-alert-circle-outline text-sky-500"></i>
                                        </div>
                                        <h5 class="text-sky-500">Success</h5>
                                        <p class="mt-1 mb-4 text-sky-800">A simple success alert</p>
                                    </div>
                                </div>
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
