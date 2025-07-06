<nav
    class="fixed top-0 left-0 right-0 z-10 flex items-center bg-white  dark:bg-zinc-800 print:hidden dark:border-zinc-700 ltr:pr-6 rtl:pl-6">
    <div class="flex justify-between w-full">
        <div class="flex items-center topbar-brand">
            <div
                class="hidden lg:flex navbar-brand items-center justify-between shrink px-6 h-[70px]  ltr:border-r rtl:border-l bg-[#fbfaff] border-gray-50 dark:border-zinc-700 dark:bg-zinc-800 shadow-none">
                <a href="#"
                    class="flex items-center text-lg flex-shrink-0 font-bold dark:text-white leading-[69px]">
                    
                        <img src="{{ URL::asset('build/images/logo.png') }}" alt="" height="50px"
                            width="85px" class="inline-block align-middle ltr:xl:mr-2 rtl:xl:ml-2">
                </a>
            </div>
            <button type="button"
                class="group-data-[sidebar-size=sm]:border-b border-b border-[#e9e9ef] dark:border-zinc-600 dark:lg:border-transparent lg:border-transparent  group-data-[sidebar-size=sm]:border-[#e9e9ef] group-data-[sidebar-size=sm]:dark:border-zinc-600 text-gray-800 dark:text-white h-[70px] px-4 ltr:-ml-[52px] rtl:-mr-14 py-1 vertical-menu-btn text-16"
                id="vertical-menu-btn">
                <i class="fa fa-fw fa-bars"></i>
            </button>
        </div>
        <div
            class="flex justify-between w-full items-center border-b border-[#e9e9ef] dark:border-zinc-600 ltr:pl-6 rtl:pr-6">
            <div>
                <!-- @if  (getCurrentUserRoleName() === 'Super Admin')
                    <div class="grid grid-cols-1 lg:gap-x-6 lg:grid-cols-12 mt-4">
                        <div class="col-span-12 xl:col-span-6">
                                <form class="hidden app-search xl:block">
                                    <div class="relative inline-block">
                                        <select name="topbar_select" id="topbar_select"
                                            class="rounded placeholder:text-sm py-2 border-gray-100 dark:bg-zinc-700/50 dark:border-zinc-600 dark:text-gray-100 dark:placeholder:text-zinc-100/60"
                                            value="{{ old('topbar_select') }}">
                                        </select>
                                        <input id="autocomplete-input" class="border rounded px-4 py-2" type="text"
                                            placeholder="Search...">
                                        <ul id="autocomplete-results"
                                            class="absolute z-10 hidden bg-white rounded border border-gray-300 mt-1 w-full">
                                        </ul>
                                        <button
                                            class="py-1.5 px-2.5 w-9 h-[34px] text-white bg-violet-500 inline-block absolute ltr:right-1 top-1 rounded shadow shadow-violet-100 dark:shadow-gray-900 rtl:left-1 rtl:right-auto"
                                            type="button"><i class="align-middle bx bx-search-alt"></i></button>
                                    </div>
                                </form>
                        </div>
                    </div>
                @endif -->
            </div>
            <div class="flex">
                @if (isset($not))
                    <div>
                        <div class="relative block dropdown sm:hidden">
                            <button type="button"
                                class="text-xl px-4 h-[70px] text-gray-600 dark:text-gray-100 dropdown-toggle"
                                data-dropdown-toggle="navNotifications">
                                <i data-feather="search" class="w-5 h-5"></i>
                            </button>

                            <div class="absolute top-0 z-50 hidden px-4 mx-4 list-none bg-white border rounded shadow  dropdown-menu -left-36 w-72 border-gray-50 dark:bg-zinc-800 dark:border-zinc-600 dark:text-gray-300"
                                id="navNotifications">
                                <form class="py-3 dropdown-item" aria-labelledby="navNotifications">
                                    <div class="m-0 form-group">
                                        <div class="flex w-full">
                                            <input type="text"
                                                class="border-gray-100 dark:border-zinc-600 dark:text-zinc-100 w-fit"
                                                placeholder="Search ..." aria-label="Search Result">
                                            <button
                                                class="text-white border-l-0 border-transparent rounded-l-none btn btn-primary bg-violet-500"
                                                type="submit"><i class="mdi mdi-magnify"></i></button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="relative hidden dropdown language sm:block">
                        <button class="btn border-0 py-0 dropdown-toggle px-3 h-[70px]" type="button"
                            aria-expanded="false" data-dropdown-toggle="navNotifications">
                            <img src="{{ URL::asset('build/images/flags/us.jpg') }}" alt="" class="h-4"
                                id="header-lang-img">
                        </button>
                        <div class="absolute z-50 hidden w-40 list-none bg-white rounded shadow dropdown-menu -left-24 dark:bg-zinc-800"
                            id="navNotifications">
                            <ul class="border border-gray-50 dark:border-gray-700" aria-labelledby="navNotifications">
                                <li>
                                    <a href="#"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50/50 dark:text-gray-200 dark:hover:bg-zinc-600/50 dark:hover:text-white"><img
                                            src="{{ URL::asset('build/images/flags/us.jpg') }}" alt="user-image"
                                            class="inline-block h-3 mr-1"> <span class="align-middle">English</span></a>
                                </li>
                                <li>
                                    <a href="#"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50/50 dark:text-gray-200 dark:hover:bg-zinc-600/50 dark:hover:text-white"><img
                                            src="{{ URL::asset('build/images/flags/spain.jpg') }}" alt="user-image"
                                            class="inline-block h-3 mr-1"> <span class="align-middle">Spanish</span></a>
                                </li>
                                <li>
                                    <a href="#"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50/50 dark:text-gray-200 dark:hover:bg-zinc-600/50 dark:hover:text-white"><img
                                            src="{{ URL::asset('build/images/flags/germany.jpg') }}" alt="user-image"
                                            class="inline-block h-3 mr-1"> <span class="align-middle">German</span></a>
                                </li>
                                <li>
                                    <a href="#"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50/50 dark:text-gray-200 dark:hover:bg-zinc-600/50 dark:hover:text-white"><img
                                            src="{{ URL::asset('build/images/flags/italy.jpg') }}" alt="user-image"
                                            class="inline-block h-3 mr-1"> <span class="align-middle">Italian</span></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div>
                        <button type="button"
                            class="light-dark-mode text-xl px-3 h-[70px] text-gray-600 dark:text-gray-100 hidden sm:block ">
                            <i data-feather="moon" class="block w-5 h-5 dark:hidden"></i>
                            <i data-feather="sun" class="hidden w-5 h-5 dark:block"></i>
                        </button>
                    </div>


                    <div>
                        <div class="relative hidden text-gray-600 dropdown sm:block">
                            <button type="button"
                                class="btn border-0 h-[70px] text-xl px-3 dropdown-toggle dark:text-gray-100"
                                data-bs-toggle="dropdown" id="dropdownMenuButton1">
                                <i data-feather="grid" class="w-5 h-5"></i>
                            </button>
                            <div class="absolute z-50 hidden list-none bg-white border rounded shadow dropdown-menu ltr:!right-0 ltr:!left-auto rtl:!left-0 rtl:!right-auto w-72 border-gray-50 dark:bg-zinc-700 dark:border-zinc-600 dark:text-gray-300"
                                aria-labelledby="dropdownMenuButton1">
                                <div class="p-2">
                                    <div class="grid grid-cols-3">
                                        <a class="py-4 text-center dropdown-item hover:bg-gray-50/50 dark:hover:bg-zinc-700/50 dark:hover:text-gray-50"
                                            href="#">
                                            <img src="{{ URL::asset('build/images/brands/github.png') }}"
                                                class="h-6 mx-auto mb-2" alt="Github">
                                            <span>GitHub</span>
                                        </a>
                                        <a class="py-4 text-center dropdown-item hover:bg-gray-50/50 dark:hover:bg-zinc-700/50 dark:hover:text-gray-50"
                                            href="#">
                                            <img src="{{ URL::asset('build/images/brands/bitbucket.png') }}"
                                                class="h-6 mx-auto mb-2" alt="Github">
                                            <span>Bitbucket</span>
                                        </a>
                                        <a class="py-4 text-center dropdown-item hover:bg-gray-50/50 dark:hover:bg-zinc-700/50 dark:hover:text-gray-50"
                                            href="#">
                                            <img src="{{ URL::asset('build/images/brands/dribbble.png') }}"
                                                class="h-6 mx-auto mb-2" alt="Github">
                                            <span>Dribbble</span>
                                        </a>
                                    </div>
                                    <div class="grid grid-cols-3">
                                        <a class="py-4 text-center dropdown-item hover:bg-gray-50/50 dark:hover:bg-zinc-700/50 dark:hover:text-gray-50"
                                            href="#">
                                            <img src="{{ URL::asset('build/images/brands/dropbox.png') }}"
                                                class="h-6 mx-auto mb-2" alt="Github">
                                            <span>Dropbox</span>
                                        </a>
                                        <a class="py-4 text-center dropdown-item hover:bg-gray-50/50 dark:hover:bg-zinc-700/50 dark:hover:text-gray-50"
                                            href="#">
                                            <img src="{{ URL::asset('build/images/brands/mail_chimp.png') }}"
                                                class="h-6 mx-auto mb-2" alt="Github">
                                            <span>Mail Chimp</span>
                                        </a>
                                        <a class="py-4 text-center dropdown-item hover:bg-gray-50/50 dark:hover:bg-zinc-700/50 dark:hover:text-gray-50"
                                            href="#">
                                            <img src="{{ URL::asset('build/images/brands/slack.png') }}"
                                                class="h-6 mx-auto mb-2" alt="Github">
                                            <span>Slack</span>
                                        </a>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <div>
                    <div class="relative dropdown">
                        <button type="button"
                            class="flex items-center px-3 py-2 h-[70px] border-x border-gray-50 bg-gray-50/30 dropdown-toggle dark:bg-zinc-700 dark:border-zinc-600 dark:text-gray-100"
                            id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true"
                            aria-expanded="true">
                            @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                                <img class="border-[3px] border-gray-700 dark:border-zinc-400 rounded-full w-9 h-9 ltr:xl:mr-2 rtl:xl:ml-2"
                                    src="@if (Auth::user()->profile_photo_url) {{ Auth::user()->profile_photo_url }} @else https://ui-avatars.com/api/?name={{ Auth::user()->name }}&color=7F9CF5&background=EBF4FF @endif"
                                    alt="{{ Auth::user()->name }}">
                            @endif
                            <span class="hidden font-medium xl:block">{{ Auth::user()->name }}</span>
                            <i class="hidden align-bottom mdi mdi-chevron-down xl:block"></i>
                        </button>
                        <div class="absolute top-0 z-50 hidden w-40 list-none bg-white dropdown-menu dropdown-animation rounded shadow dark:bg-zinc-800"
                            id="profile/log">
                            <div class="border border-gray-50 dark:border-zinc-600"
                                aria-labelledby="navNotifications">
                                <div class="dropdown-item dark:text-gray-100">
                                    @if(getCurrentUserRoleName() === 'Subscriber')
                                    <x-responsive-nav-link href="{{ route('subscriber.profile') }}" :active="request()->routeIs('profile.show')">
                                        <i class="mdi mdi-face-man text-16 align-middle mr-1"></i>{{ __('Profile') }}
                                    </x-responsive-nav-link>
                                    @else
                                        <x-responsive-nav-link href="{{ route('profile.show') }}" :active="request()->routeIs('profile.show')">
                                            <i
                                                class="mdi mdi-face-man text-16 align-middle mr-1"></i>{{ __('Profile') }}
                                        </x-responsive-nav-link>
                                    @endif
                                </div>
                                <div class="dropdown-item dark:text-gray-100">
                                    @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                                        <x-responsive-nav-link href="{{ route('api-tokens.index') }}"
                                            :active="request()->routeIs('api-tokens.index')">
                                            <i
                                                class="mdi mdi-key-variant text-16 align-middle mr-1"></i>{{ __('API Tokens') }}
                                        </x-responsive-nav-link>
                                    @endif
                                </div>
                                <hr class="border-gray-50 dark:border-gray-700">
                                <div class="dropdown-item dark:text-gray-100">
                                    <x-responsive-nav-link href="javascript:void();"
                                        onclick="confirmLogout();">
                                        <i class="mdi mdi-logout text-16 align-middle mr-1"></i>{{ __('Logout') }}
                                    </x-responsive-nav-link>
                                    <form method="POST" action="{{ route('logout') }}" id="logout-form">
                                        @csrf
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>

<div class="hidden">
    <div class="fixed inset-0 z-40 transition-opacity bg-black/40"></div>
    <div class="fixed right-0 z-50 h-screen bg-white w-80" data-simplebar>
        <div class="flex items-center p-4 border-b border-gray-100">
            <h5 class="m-0 mr-2">Theme Customizer</h5>
            <a href="javascript:void(0);" class="w-6 h-6 ml-auto text-center bg-gray-700 rounded-full">
                <i class="leading-relaxed text-white align-middle mdi mdi-close text-15"></i>
            </a>
        </div>
        <div class="p-4">
            <h6 class="mb-3">Layout</h6>
            <div class="flex gap-4">
                <div>
                    <input class="focus:ring-0" checked type="radio" name="layout" id="layout-vertical"
                        value="vertical">
                    <label class="align-middle" for="layout-vertical">Vertical</label>
                </div>
                <div>
                    <input class="focus:ring-0" type="radio" name="layout" id="layout-horizontal"
                        value="horizontal">
                    <label class="align-middle" for="layout-horizontal">Horizontal</label>
                </div>
            </div>

            <h6 class="pt-2 mt-4 mb-3">Layout Mode</h6>
            <div class="flex gap-4">
                <div>
                    <input class="focus:ring-0" checked type="radio" name="layout-mode" id="layout-mode-light"
                        value="light">
                    <label class="form-check-label" for="layout-mode-light">Light</label>
                </div>
                <div>
                    <input class="focus:ring-0" type="radio" name="layout-mode" id="layout-mode-dark"
                        value="dark">
                    <label class="form-check-label" for="layout-mode-dark">Dark</label>
                </div>
            </div>

            <h6 class="pt-2 mt-4 mb-3">Layout Width</h6>

            <div class="flex gap-4">
                <div>
                    <input class="focus:ring-0" checked type="radio" name="layout-width" id="layout-width-fuild"
                        value="fuild" onchange="document.body.setAttribute('data-layout-size', 'fluid')">
                    <label class="form-check-label" for="layout-width-fuild">Fluid</label>
                </div>
                <div>
                    <input class="focus:ring-0" type="radio" name="layout-width" id="layout-width-boxed"
                        value="boxed" onchange="document.body.setAttribute('data-layout-size', 'boxed')">
                    <label class="form-check-label" for="layout-width-boxed">Boxed</label>
                </div>
            </div>

            <h6 class="pt-2 mt-4 mb-3">Layout Position</h6>
            <div class="flex gap-4">
                <div>
                    <input class="focus:ring-0" checked type="radio" name="layout-position"
                        id="layout-position-fixed" value="fixed"
                        onchange="document.body.setAttribute('data-layout-scrollable', 'false')">
                    <label class="form-check-label" for="layout-position-fixed">Fixed</label>
                </div>
                <div>
                    <input class="focus:ring-0" checked type="radio" name="layout-position"
                        id="layout-position-scrollable" value="scrollable"
                        onchange="document.body.setAttribute('data-layout-scrollable', 'true')">
                    <label class="form-check-label" for="layout-position-scrollable">Scrollable</label>
                </div>
            </div>

            <h6 class="pt-2 mt-4 mb-3">Topbar Color</h6>
            <div class="flex gap-4">
                <div>
                    <input class="focus:ring-0" checked type="radio" name="topbar-color" id="topbar-color-light"
                        value="light" onchange="document.body.setAttribute('data-topbar', 'light')">
                    <label class="form-check-label" for="topbar-color-light">Light</label>
                </div>
                <div>
                    <input class="focus:ring-0" type="radio" name="topbar-color" id="topbar-color-dark"
                        value="dark" onchange="document.body.setAttribute('data-topbar', 'dark')">
                    <label class="form-check-label" for="topbar-color-dark">Dark</label>
                </div>
            </div>

            <h6 class="pt-2 mt-4 mb-3 sidebar-setting">Sidebar Size</h6>

            <div class="space-y-1">
                <div class="form-check sidebar-setting">
                    <input class="focus:ring-0" checked type="radio" name="sidebar-size" id="sidebar-size-default"
                        value="default" onchange="document.body.setAttribute('data-sidebar-size', 'lg')">
                    <label class="form-check-label" for="sidebar-size-default">Default</label>
                </div>
                <div class="form-check sidebar-setting">
                    <input class="focus:ring-0" type="radio" name="sidebar-size" id="sidebar-size-compact"
                        value="compact" onchange="document.body.setAttribute('data-sidebar-size', 'md')">
                    <label class="form-check-label" for="sidebar-size-compact">Compact</label>
                </div>
                <div class="form-check sidebar-setting">
                    <input class="focus:ring-0" type="radio" name="sidebar-size" id="sidebar-size-small"
                        value="small" onchange="document.body.setAttribute('data-sidebar-size', 'sm')">
                    <label class="form-check-label" for="sidebar-size-small">Small (Icon View)</label>
                </div>
            </div>

            <h6 class="pt-2 mt-4 mb-3 sidebar-setting">Sidebar Color</h6>
            <div class="space-y-1">
                <div class="form-check sidebar-setting">
                    <input class="focus:ring-0" checked type="radio" name="sidebar-color" id="sidebar-color-light"
                        value="light" onchange="document.body.setAttribute('data-sidebar', 'light')">
                    <label class="form-check-label" for="sidebar-color-light">Light</label>
                </div>
                <div class="form-check sidebar-setting">
                    <input class="focus:ring-0" type="radio" name="sidebar-color" id="sidebar-color-dark"
                        value="dark" onchange="document.body.setAttribute('data-sidebar', 'dark')">
                    <label class="form-check-label" for="sidebar-color-dark">Dark</label>
                </div>
                <div class="form-check sidebar-setting">
                    <input class="focus:ring-0" type="radio" name="sidebar-color" id="sidebar-color-brand"
                        value="brand" onchange="document.body.setAttribute('data-sidebar', 'brand')">
                    <label class="form-check-label" for="sidebar-color-brand">Brand</label>
                </div>
            </div>

            <h6 class="pt-2 mt-4 mb-3">Direction</h6>
            <div class="space-y-1">
                <div>
                    <input class="focus:ring-0" checked type="radio" name="layout-direction"
                        id="layout-direction-ltr" value="ltr">
                    <label class="form-check-label" for="layout-direction-ltr">LTR</label>
                </div>
                <div>
                    <input class="focus:ring-0" type="radio" name="layout-direction" id="layout-direction-rtl"
                        value="rtl">
                    <label class="form-check-label" for="layout-direction-rtl">RTL</label>
                </div>
            </div>

        </div>
    </div>
</div>

