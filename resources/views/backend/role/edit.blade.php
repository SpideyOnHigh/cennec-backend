@extends('layouts.master')
@section('title')
    {{ __('Edit Role') }}
@endsection
@section('css')
    <!-- alertifyjs Css -->
    <link href="{{ URL::asset('build/libs/alertifyjs/build/css/alertify.min.css') }}" rel="stylesheet" type="text/css" />

    <!-- alertifyjs default themes  Css -->
    <link href="{{ URL::asset('build/libs/alertifyjs/build/css/themes/default.min.css') }}" rel="stylesheet" type="text/css" />
    <style>
        .permission-card{
          padding: 0 !important;
          margin-top: 30px !important;
        }
      </style>
@endsection
@section('content')
    <div class="main-content group-data-[sidebar-size=sm]:ml-[70px]">
        <div class="page-content dark:bg-zinc-700">
            <div class="container-fluid px-[0.625rem]">

                <!-- page title -->
                <x-page-title title="Edit Role" pagetitle="Role" />

                <div class="grid grid-cols-1 mt-3">
                    <div class="card dark:bg-zinc-800 dark:border-zinc-600">
                        <form method="POST" action="{{ route('role.update', $role->id) }}" class="role-edit-form"
                            id="role_form_edit">
                            @csrf
                            <div class="bg-white sm:p-6 shadow sm:rounded-md">
                                <div class="grid grid-cols-6 gap-6">
                                    <div class="col-span-6 sm:col-span-4">
                                        <label for="name"
                                            class="block mb-2 font-medium text-gray-700 dark:text-gray-100">Name<span
                                                class="text-sm text-red-600">*</span></label>
                                        <input type="text" name="name" id="name" placeholder="Enter name"
                                            class="w-full placeholder:text-13 text-13 py-1.5 rounded border-gray-100 focus:border focus:border-violet-50 focus:ring focus:ring-violet-500/20  dark:bg-zinc-700/50 dark:border-zinc-600 dark:placeholder:text-zinc-300 placeholder:text-gray-400 dark:text-zinc-100"
                                            value="{{ old('name', $role->name ?? '') }}"">
                                        @error('name')
                                            <p class="text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="col-span-6 sm:col-span-4">
                                        <label for="description"
                                            class="block mb-2 font-medium text-gray-700 dark:text-gray-100">Description</label>
                                        <textarea type="text" name="description" id="description" placeholder="Enter description"
                                            class="w-full placeholder:text-13 text-13 py-1.5 rounded border-gray-100 focus:border focus:border-violet-50 focus:ring focus:ring-violet-500/20  dark:bg-zinc-700/50 dark:border-zinc-600 dark:placeholder:text-zinc-300 placeholder:text-gray-400 dark:text-zinc-100">{{ old('description', $role->description ?? '') }}</textarea>
                                        @error('description')
                                            <p class="text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="hidden sm:block">
                                    <div class="py-4">
                                        <div class="border-t border-gray-200"></div>
                                    </div>
                                </div>

                                <div class="card-body permission-card">
                                    <div class="relative overflow-x-auto">
                                        <div class="col-span-12 xl:col-span-3 border border-dark-800 mb-4">
                                            <div class="col-span-12 flex p-2 bg-gray-200 ">
                                                <div class="col-span-2 mr-5 flex justify-start items-center">
                                                    <i class="fa fa-info-circle text-black" aria-hidden="true"></i><span
                                                        class="text-black ml-1"><b>Rights / Permissions</b></span>
                                                </div>
                                            </div>
                                        </div>
                                        <table
                                            class="w-full text-sm text-left text-gray-500 border border-gray-50 dark:border-zinc-600">
                                            <thead class="text-sm text-gray-700 dark:text-gray-100">
                                                <tr class="bg-white border">
                                                    <th scope="col" class="px-6 py-3 border-l">
                                                        Main Permission
                                                    </th>
                                                    <th scope="col" class="px-6 py-3 border-l">
                                                        Sub Permission
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($permissions->where('parent_id', 0) as $key => $value)
                                                    <tr class="bg-white border">
                                                        <th class="px-3 py-3.5 border-l">
                                                            <input type="checkbox" name="permission[]"
                                                                class="permissions mr-2 ckb-password align-middle rounded focus:ring-0 focus:ring-offset-0 dark:bg-zinc-700 dark:border-zinc-400 checked:bg-violet-500 dark:checked:bg-violet-500"
                                                                value="{{ $value->name }}"
                                                                id="permission_{{ $value->id }}"
                                                                data-id="{{ $value->id }}"
                                                                {{ in_array($value->name, $rolePermission) ? 'checked' : '' }}>
                                                            <span>{{ $value->description }}</span>
                                                        </th>
                                                        <td class="px-3 py-3.5 border-l">
                                                            @foreach ($permissions->where('parent_id', $value->id) as $k => $v)
                                                                <div class="mb-2">
                                                                    <input type="checkbox" name="permission[]"
                                                                        class="permissions mr-2 ckb-password align-middle rounded focus:ring-0 focus:ring-offset-0 dark:bg-zinc-700 dark:border-zinc-400 checked:bg-violet-500 dark:checked:bg-violet-500 sub_permission_{{ $value->id }}"
                                                                        value="{{ $v->name }}"
                                                                        id="sub_permission_{{ $value->id }}"
                                                                        data-id="{{ $value->id }}"
                                                                        {{ in_array($v->name, $rolePermission) ? 'checked' : '' }}>
                                                                    <span>{{ $v->description }}</span>
                                                                </div>
                                                            @endforeach
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                @error('permission')
                                                    <p class="text-sm text-red-600">{{ $message }}</p>
                                                @enderror
                                            </tbody>
                                        </table>

                                    </div>
                                </div>
                                <div class="mt-3 col-span-6 sm:col-span-4 flex items-center justify-center">
                                    <button type="submit"
                                        class="mr-1 font-medium text-white border-transparent btn bg-violet-500 w-28 hover:bg-violet-700 focus:bg-violet-700 focus:ring focus:ring-violet-50">Submit</button>
                                    <a class="font-medium text-white border-transparent btn bg-red-500 w-28 hover:bg-red-700 focus:bg-red-700 focus:ring focus:ring-red-50"
                                        href="{{ route('role.index') }}">
                                        Back
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- footer -->
                @include('layouts.footer')
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script type="text/javascript" src="{{ asset('js/libs/jquery-3.7.1.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/libs/jquery.validate.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/alertifyjs/build/alertify.min.js') }}"></script>

    <script src="{{ asset('js/backend/role.js') }}"></script>
    <script>
        $(function() {
            role.edit();
        });
    </script>
@endsection
