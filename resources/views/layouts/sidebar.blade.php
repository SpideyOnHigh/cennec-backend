@php
    $getCurrentUserRoleName = getCurrentUserRoleName();
@endphp
<div
    class="fixed bottom-0 z-10 h-screen ltr:border-r rtl:border-l vertical-menu rtl:right-0 ltr:left-0 top-[70px] bg-slate-50 border-gray-50 print:hidden dark:bg-zinc-800 dark:border-neutral-700">
    <div data-simplebar class="h-full">
        <div class="metismenu pb-10 pt-2.5" id="sidebar-menu">
            <ul id="side-menu">
                <li class="px-5 py-3 text-xs font-medium text-gray-500 cursor-default leading-[18px] group-data-[sidebar-size=sm]:hidden block"
                    data-key="t-menu">Menu</li>
                @can('dashboard')
                    <li>
                        <a href="{{ route('dashboard') }}"
                            class="block py-2.5 px-6 text-sm font-medium text-gray-950 transition-all duration-150 ease-linear hover:text-violet-500 dark:text-gray-300 dark:active:text-white dark:hover:text-white {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <i data-feather="home" fill="#545a6d33"></i>
                            <span data-key="t-dashboard"> Dashboard</span>
                        </a>
                    </li>
                @endcan

                @can('user')
                    <li class="{{ request()->routeIs('users.*') ? 'mm-active' : '' }}">
                        <a href="{{ route('users.index') }}"
                            class="block py-2.5 px-6 text-sm font-medium text-gray-950 transition-all duration-150 ease-linear hover:text-violet-500 dark:text-gray-300 dark:active:text-white dark:hover:text-white {{ request()->routeIs('users.*') ? 'active' : '' }}">
                            <i data-feather="users" fill="#545a6d33"></i>
                            <span data-key="t-level">Users</span>
                        </a>
                    </li>
                @endcan

                @canany(['roles', 'staff-management'])
                    <li
                        class="{{ request()->routeIs('role.*') ? 'mm-active' : '' }} {{ request()->routeIs('staff.*') ? 'mm-active' : '' }}">
                        <a href="javascript: void(0);" aria-expanded="false"
                            class="block py-2.5 px-6 text-sm font-medium text-gray-950 transition-all duration-150 ease-linear nav-menu hover:text-violet-500 dark:text-gray-300 dark:active:text-white dark:hover:text-white">
                            <i class="fa fa-check-square mr-3" fill="#545a6d33"></i>
                            <span data-key="t-level">Staff Management</span>
                        </a>
                        <ul>
                            @can('roles')
                                <li>
                                    <a href="{{ route('role.index') }}"
                                        class="block py-[6.4px] pr-6 text-sm font-medium text-gray-950 transition-all duration-150 ease-linear pl-[52.8px] hover:text-violet-500 dark:text-gray-300 dark:active:text-white dark:hover:text-white {{ request()->routeIs('role.*') ? 'active' : '' }}">Roles</a>
                                </li>
                            @endcan
                            @canany(['staff-management'])
                                <li>
                                    <a href="{{ route('staff.index') }}"
                                        class="block py-[6.4px] pr-6 text-sm font-medium text-gray-950 transition-all duration-150 ease-linear pl-[52.8px] hover:text-violet-500 dark:text-gray-300 dark:active:text-white dark:hover:text-white {{ request()->routeIs('staff.*') ? 'active' : '' }}">Staffs</a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcan

                @can('reports')
                    <li class="w-full menu__item">
                        <a href="{{ route('reports.index') }}"
                            class="block py-2.5 px-6 text-sm font-medium text-gray-950 transition-all duration-150 ease-linear hover:text-violet-500 dark:text-gray-300 dark:active:text-white dark:hover:text-white {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                            <i class="far fa-comment-alt mr-3" fill="#545a6d33"></i>
                            <span data-key="t-level">Reports</span>
                        </a>
                    </li>
                @endcan

                @can('feedback')
                    <li class="w-full menu__item">
                        <a href="{{ route('feedback.index') }}"
                            class="block py-2.5 px-6 text-sm font-medium text-gray-950 transition-all duration-150 ease-linear hover:text-violet-500 dark:text-gray-300 dark:active:text-white dark:hover:text-white">
                            <i class="far fa-comment-alt mr-3"></i>
                            <span data-key="t-level">Feedback</span>
                        </a>
                    </li>
                @endcan

                @can('rating')
                    <li class="{{ request()->routeIs('feedback.rating-index') ? 'mm-active' : '' }}">
                        <a href="{{ route('feedback.rating-index') }}"
                            class="block py-2.5 px-6 text-sm font-medium text-gray-950 transition-all duration-150 ease-linear hover:text-violet-500 dark:text-gray-300 dark:active:text-white dark:hover:text-white">
                            <i class="far fa-star mr-3"></i>
                            <span data-key="t-level">Rating</span>
                        </a>
                    </li>
                @endcan

                @can('user-whitelist')
                    <li class="{{ request()->routeIs('user-whitelist.*') ? 'mm-active' : '' }}">
                        <a href="{{ route('user-whitelist.index') }}"
                            class="block py-2.5 px-6 text-sm font-medium text-gray-950 transition-all duration-150 ease-linear hover:text-violet-500 dark:text-gray-300 dark:active:text-white dark:hover:text-white">
                            <i class="fa fa-list mr-3"></i>
                            <span data-key="t-level">Users Whitelist</span>
                        </a>
                    </li>
                @endcan

                @can('invitation-code')
                    <li class="{{ request()->routeIs('invitation-codes.*') ? 'mm-active' : '' }}">
                        <a href="{{ route('invitation-codes.index') }}"
                            class="block py-2.5 px-6 text-sm font-medium text-gray-950 transition-all duration-150 ease-linear hover:text-violet-500 dark:text-gray-300 dark:active:text-white dark:hover:text-white">
                            <i class="fa fa-gift mr-3"></i>
                            <span data-key="t-level">Invitation Codes</span>
                        </a>
                    </li>
                @endcan

                @can('interests')
                    <li class="{{ request()->routeIs('interest.*') ? 'mm-active' : '' }}">
                        <a href="{{ route('interest.index') }}"
                            class="block py-2.5 px-6 text-sm font-medium text-gray-950 transition-all duration-150 ease-linear hover:text-violet-500 dark:text-gray-300 dark:active:text-white dark:hover:text-white">
                            <i class="fa-solid fa-bullseye mr-3"></i>
                            <span data-key="t-level">Interest List</span>
                        </a>
                    </li>
                @endcan

                @can('policy-page')
                    <li class="{{ request()->routeIs('policy-page.*') ? 'mm-active' : '' }}">
                        <a href="{{ route('policy-page.index') }}"
                            class="block py-2.5 px-6 text-sm font-medium text-gray-950 transition-all duration-150 ease-linear hover:text-violet-500 dark:text-gray-300 dark:active:text-white dark:hover:text-white">
                            <i class="fa fa-shield-alt mr-3"></i>
                            <span data-key="t-level">CMS Pages</span>
                        </a>
                    </li>
                @endcan

                @can('email-test')
                    <li class="{{ request()->routeIs('email-test.*') ? 'mm-active' : '' }}">
                        <a href="{{ route('email-test.index') }}"
                            class="block py-2.5 px-6 text-sm font-medium text-gray-950 transition-all duration-150 ease-linear hover:text-violet-500 dark:text-gray-300 dark:active:text-white dark:hover:text-white">
                            <i class="fa fa-gear mr-3"></i>
                            <span data-key="t-level">Tests</span>
                        </a>
                    </li>
                @endcan

                {{-- @can('about')
                    <li class="w-full menu__item">
                        <a href="#"
                            class="block py-2.5 px-6 text-sm font-medium text-gray-950 transition-all duration-150 ease-linear hover:text-violet-500 dark:text-gray-300 dark:active:text-white dark:hover:text-white {{ request()->routeIs('about') ? 'active' : '' }}">
                            <i class="fa fa-question-circle mr-3" fill="#545a6d33"></i>
                            <span data-key="t-level">About</span>
                        </a>
                    </li>
                @endcan --}}
            </ul>
        </div>
    </div>
</div>
