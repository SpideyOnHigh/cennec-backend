@extends('layouts.master-without-nav')
@section('title')
    {{ __('404 error') }}
@endsection
@section('content')
    <div class="container-fluid">
        <div class="bg-gray-50/20 h-screen dark:bg-zinc-800">
            <div>
                <div class="container mx-auto pt-12">
                    <div class="grid grid-cols-12 justify-center pt-12">
                        <div class="col-span-12">
                            <div class="text-center">
                                <h1 class="text-8xl text-gray-600 mb-3 dark:text-gray-100">4<span
                                        class="text-violet-500 mx-2">0</span>4</h1>
                                <h4 class="uppercase mb-2 text-gray-600 text-[21px] dark:text-gray-100">Sorry, page not found
                                </h4>
                            </div>
                        </div>
                        <div class="col-span-8 col-start-3">
                            <div class="pt-12">
                                <img src="{{ URL::asset('build/images/error-img.png') }}" alt="" class="img-fluid">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
