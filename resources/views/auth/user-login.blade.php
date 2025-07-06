{{-- @extends('layouts.master-without-nav')
@section('title')
    Login
@endsection
@section('css')
    <link rel="stylesheet" href="{{ URL::asset('build/libs/swiper/swiper-bundle.min.css') }}">
    <style>
        .link-container {
            display: flex;
            justify-content: center;
            gap: 20px;
            /* Adjust the gap as needed */
        }

        .link-container a {
            text-decoration: none;
        }
    </style>
@endsection
@section('content')
    <div class="container-fluid">
        <div class="h-screen md:overflow-hidden">
            <div class="flex justify-center items-center h-full">
                <div class="col-span-12 md:col-span-5 lg:col-span-4 xl:col-span-3 relative z-50">
                    <div class="bg-white xl:p-12 p-10 dark:bg-zinc-800" style="width: 600px;">
                        <div class="flex h-[90vh] flex-col">
                            <div class="mx-auto">
                                <a href="{{ url('dashboard') }}" class="">
                                    <img src="{{ URL::asset('build/images/logo.png') }}" alt="" class="h-14 inline">
                                </a>
                            </div>
                            @include('components.alert-message')
                            <div class="my-auto">
                                <div class="text-center">
                                    <h5 class="text-gray-600 dark:text-gray-100">Welcome Back !</h5>
                                    <p class="text-gray-500 dark:text-gray-100/60 mt-1">Log in to continue to
                                        {{ env('APP_NAME', 'Cennec') }}.
                                    </p>
                                </div>

                                <div class="flex justify-center card-body">
                                    <div class="nav-tabs border-b-tabs">
                                        <ul
                                            class="flex w-full font-medium text-center text-gray-500  border-gray-100 nav dark:border-gray-700 dark:text-gray-400">
                                            <li class="border-b">
                                                <a href="{{ route('login') }}" class="inline-block p-4">Admin</a>
                                            </li>
                                            <li class="ml-1">
                                                <a href="{{ route('user.login.show') }}"
                                                    class="inline-block p-4 {{ request()->path() == 'subscriber/login' ? 'active' : '' }}">Subscriber</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                                <form method="POST" action="{{ route('login') }}" class="mt-4 pt-2">
                                    @csrf
                                    <div class="mb-4">
                                        <input type="hidden" name="type" value="1">
                                        <label for="email"
                                            class="text-gray-600 dark:text-gray-100 font-medium mb-2 block">Email <span
                                                class="text-red-600">*</span></label>
                                        <input type="email" name="email" value="{{ old('email') }}"
                                            class="w-full rounded placeholder:text-sm py-2 border-gray-100 dark:bg-zinc-700/50 dark:border-zinc-600 dark:text-gray-100 dark:placeholder:text-zinc-100/60"
                                            id="email" placeholder="Enter email" required>
                                        @error('email')
                                            <span class="text-sm text-red-600">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <div class="flex">
                                            <div class="flex-grow-1">
                                                <label for="password"
                                                    class="text-gray-600 dark:text-gray-100 font-medium mb-2 block">Password
                                                    <span class="text-red-600">*</span></label>
                                            </div>
                                            @if (Route::has('password.request'))
                                                <div class="ltr:ml-auto rtl:mr-auto">
                                                    <a href="{{ route('password.request') }}"
                                                        class="text-gray-500 dark:text-gray-100">Forgot
                                                        password?</a>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="flex">
                                            <input type="password" name="password" id="password" value=""
                                                class="w-full rounded ltr:rounded-r-none rtl:rounded-l-none placeholder:text-sm py-2 border-gray-100 dark:bg-zinc-700/50 dark:border-zinc-600 dark:text-gray-100 dark:placeholder:text-zinc-100/60"
                                                placeholder="Enter password" aria-label="Password"
                                                aria-describedby="password-addon" required>
                                            @error('password')
                                                <span class="text-sm text-red-600">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row mb-6">
                                        <div class="col">
                                            <div>
                                                <input type="checkbox" name="remember" id="remember"
                                                    class="h-4 w-4 border border-gray-300 rounded bg-white checked:bg-blue-600 checked:border-blue-600 focus:outline-none transition duration-200 mt-1 align-top bg-no-repeat bg-center bg-contain ltr:float-left rtl:float-right ltr:mr-2 rtl:ml-2 cursor-pointer focus:ring-offset-0"
                                                    checked id="exampleCheck1">
                                                <label class="align-middle text-gray-600 dark:text-gray-100 font-medium"
                                                    for="remember">
                                                    Remember me
                                                </label>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="mb-3">
                                        <button
                                            class="btn border-transparent bg-violet-500 w-full py-2.5 text-white w-100 waves-effect waves-light shadow-md shadow-violet-200 dark:shadow-zinc-600"
                                            type="submit">Log In</button>
                                    </div>
                                </form>

                                <div class="mt-4 pt-2 text-center">
                                    <div class="link-container">
                                        <a href="{{ route('contact-us.create') }}">
                                            <h6 class="text-14 mb-3 text-gray-500 dark:text-gray-100 font-medium">Contact Us
                                            </h6>
                                        </a>
                                        <a href="{{ route('page.term-condition') }}">
                                            <h6 class="text-14 mb-3 text-gray-500 dark:text-gray-100 font-medium">Terms &
                                                Condition</h6>
                                        </a>
                                        <a href="{{ route('page.privacy-policy') }}">
                                            <h6 class="text-14 mb-3 text-gray-500 dark:text-gray-100 font-medium">Privacy
                                                Policy</h6>
                                        </a>
                                        <a href="{{ route('page.refund-policy') }}">
                                            <h6 class="text-14 mb-3 text-gray-500 dark:text-gray-100 font-medium">Refund
                                                Policy</h6>
                                        </a>
                                        <a href="{{ route('page.pricing') }}">
                                            <h6 class="text-14 mb-3 text-gray-500 dark:text-gray-100 font-medium">Pricing
                                            </h6>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class=" text-center">
                                <p class="text-gray-500 dark:text-gray-100 relative mb-5">©
                                    <script>
                                        document.write(new Date().getFullYear())
                                    </script> {{ env('APP_NAME', 'Cennec') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{ URL::asset('build/libs/swiper/swiper-bundle.min.js') }}"></script>
    <script src="{{ URL::asset('build/js/pages/login.init.js') }}"></script>
    <script src="{{ URL::asset('build/js/app.js') }}"></script>
@endsection --}}
