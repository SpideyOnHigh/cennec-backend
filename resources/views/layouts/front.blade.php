<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="canonical" href="https://https://demo.themesberg.com/landwind/" />
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        @hasSection('title')
            @yield('title') |
        @endif {{ env('APP_NAME', 'Cennec') }}
    </title>

    <!-- Meta SEO -->
    <meta name="title" content="{{ env('APP_NAME', 'Cennec') }}">
    <meta name="description" content="{{ env('APP_NAME', 'Cennec') }},We Serve You Our Best">
    <meta name="robots" content="index, follow">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="language" content="English">
    <meta name="author" content="{{ env('APP_NAME', 'Cennec') }}">

    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ URL::asset('build/images/logo.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ URL::asset('build/images/logo.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ URL::asset('build/images/logo.png') }}">
    <link rel="shortcut icon" href="{{ URL::asset('build/images/logo.png') }}" />

    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">
    <link href="{{ asset('frontend/output.css') }}" rel="stylesheet">
</head>

<body>
    <header class="fixed w-full">
        <nav class="bg-white border-gray-200 py-2.5 dark:bg-gray-900">
            <div class="flex flex-wrap items-center justify-between max-w-screen-xl px-4 mx-auto">
                <a href="{{ route('login') }}" class="flex items-center">
                    <img src="{{ URL::asset('build/images/logo.png') }}" class="h-6 mr-3 sm:h-9" alt="Landwind Logo" />
                    <span class="self-center text-xl font-semibold whitespace-nowrap dark:text-white"></span>
                </a>
                <div class="flex items-center lg:order-2">
                    <a href="{{ route('login') }}"
                        class="text-white bg-purple-700 hover:bg-purple-800 focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-4 lg:px-5 py-2 lg:py-2.5 sm:mr-2 lg:mr-0 dark:bg-purple-600 dark:hover:bg-purple-700 focus:outline-none dark:focus:ring-purple-800">Login</a>
                    <button data-collapse-toggle="mobile-menu-2" type="button"
                        class="inline-flex items-center p-2 ml-1 text-sm text-gray-500 rounded-lg lg:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600"
                        aria-controls="mobile-menu-2" aria-expanded="false">
                        <span class="sr-only">Open main menu</span>
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <svg class="hidden w-6 h-6" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </div>
                <div class="items-center justify-between hidden w-full lg:flex lg:w-auto lg:order-1" id="mobile-menu-2">
                    <ul class="flex flex-col mt-4 font-medium lg:flex-row lg:space-x-8 lg:mt-0">
                        <li>
                            <a href="{{ route('page.home') }}"
                                class="{{ request()->is('/') ? 'lg:text-purple-700 ' : 'text-gray-700' }} block py-2 pl-3 pr-4 text-white bg-purple-700 rounded lg:bg-transparent lg:p-0 dark:text-white"
                                aria-current="page">Home</a>
                        </li>
                        <li>
                            <a href="{{ route('page.term-condition') }}"
                                class="{{ request()->is('term-condition') ? 'lg:text-purple-700 ' : 'text-gray-700' }} block py-2 pl-3 pr-4 text-gray-700 border-b border-gray-100 hover:bg-gray-50 lg:hover:bg-transparent lg:border-0 lg:hover:text-purple-700 lg:p-0 dark:text-gray-400 lg:dark:hover:text-white dark:hover:bg-gray-700 dark:hover:text-white lg:dark:hover:bg-transparent dark:border-gray-700">Term
                                & Condition</a>
                        </li>
                        <li>
                            <a href="{{ route('page.privacy-policy') }}"
                                class="{{ request()->is('privacy-policy') ? 'lg:text-purple-700 ' : 'text-gray-700' }} block py-2 pl-3 pr-4 text-gray-700 border-b border-gray-100 hover:bg-gray-50 lg:hover:bg-transparent lg:border-0 lg:hover:text-purple-700 lg:p-0 dark:text-gray-400 lg:dark:hover:text-white dark:hover:bg-gray-700 dark:hover:text-white lg:dark:hover:bg-transparent dark:border-gray-700">Privacy
                                Policy</a>
                        </li>
                        <li>
                            <a href="{{ route('page.refund-policy') }}"
                                class="{{ request()->is('refund-policy') ? 'lg:text-purple-700 ' : 'text-gray-700' }} block py-2 pl-3 pr-4 text-gray-700 border-b border-gray-100 hover:bg-gray-50 lg:hover:bg-transparent lg:border-0 lg:hover:text-purple-700 lg:p-0 dark:text-gray-400 lg:dark:hover:text-white dark:hover:bg-gray-700 dark:hover:text-white lg:dark:hover:bg-transparent dark:border-gray-700">Refund
                                Policy</a>
                        </li>
                        <li>
                            <a href="{{ route('page.pricing') }}"
                                class="{{ request()->is('pricing') ? 'lg:text-purple-700 ' : 'text-gray-700' }} block py-2 pl-3 pr-4 text-gray-700 border-b border-gray-100 hover:bg-gray-50 lg:hover:bg-transparent lg:border-0 lg:hover:text-purple-700 lg:p-0 dark:text-gray-400 lg:dark:hover:text-white dark:hover:bg-gray-700 dark:hover:text-white lg:dark:hover:bg-transparent dark:border-gray-700">Pricing</a>
                        </li>
                        <li>
                            <a href="#"
                                class="block py-2 pl-3 pr-4 text-gray-700 border-b border-gray-100 hover:bg-gray-50 lg:hover:bg-transparent lg:border-0 lg:hover:text-purple-700 lg:p-0 dark:text-gray-400 lg:dark:hover:text-white dark:hover:bg-gray-700 dark:hover:text-white lg:dark:hover:bg-transparent dark:border-gray-700">Contact</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    @yield('content')

    <footer class="bg-white dark:bg-gray-800">
        <div class="max-w-screen-xl p-4 py-6 mx-auto lg:py-16 md:p-8 lg:p-10">
            <div class="grid grid-cols-3 gap-8 md:grid-cols-3 lg:grid-cols-3">
                <div>
                    <h3 class="mb-6 text-sm font-semibold text-gray-900 uppercase dark:text-white">Address</h3>
                    <ul class="text-gray-500 dark:text-gray-400">
                        <li class="mb-6">
                            <a href="javascript:;" class=" hover:underline">Lorem ipsum dolor sit amet.</a>
                        </li>
                      
                    </ul>
                </div>
                <div>
                    <h3 class="mb-6 text-sm font-semibold text-gray-900 uppercase dark:text-white">Contact</h3>
                    <ul class="text-gray-500 dark:text-gray-400">
                        <li class="mb-6">
                            <a href="javascript:;" class="hover:underline">+91 1111111111</a>
                        </li>
                    </ul>
                </div>
                <div>
                    <h3 class="mb-6 text-sm font-semibold text-gray-900 uppercase dark:text-white">Email</h3>
                    <ul class="text-gray-500 dark:text-gray-400">
                        <li class="mb-6">
                            <a href="javascript:;" class="hover:underline">test@yopmail.com</a>
                        </li>
                    </ul>
                </div>
               
            </div>
            <hr class="my-6 border-gray-200 sm:mx-auto dark:border-gray-700 lg:my-8">
            <div class="text-center">
                <a href="{{ route('login') }}"
                    class="flex items-center justify-center mb-5 text-2xl font-semibold text-gray-900 dark:text-white">
                    <img src="{{ URL::asset('build/images/logo.png') }}" class="h-6 mr-3 sm:h-9"
                        alt="Landwind Logo" />
                </a>
                <span class="block text-sm text-center text-gray-500 dark:text-gray-400">© {{ date('Y') }}
                    {{ env('APP_NAME') }} All Rights Reserved.
                </span>

            </div>
        </div>
    </footer>
</body>

</html>
