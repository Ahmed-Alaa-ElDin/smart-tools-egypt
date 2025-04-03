@extends('layouts.front.site', [
    'titlePage' => __('front/homePage.Our Branches'),
    'url' => route('front.about-us.branches'),
    'title' => __('front/homePage.Our Branches'),
    'description' => '',
])

@section('content')
<div class="container px-4 py-2 ">
    {{-- Breadcrumb :: Start --}}
    <nav aria-label="breadcrumb" role="navigation" class="mb-2">
        <ol class="breadcrumb text-sm">
            <li class="breadcrumb-item hover:text-primary">
                <a href="{{ route('front.homepage') }}">
                    {{ __('front/homePage.Homepage') }}
                </a>
            </li>
            <li class="breadcrumb-item text-gray-700 font-bold" aria-current="page">
            {{ __('front/homePage.Our Branches') }}
            </li>
        </ol>
    </nav>
    {{-- Breadcrumb :: End --}}

    <div class="grid grid-cols-12 justify-center items-start align-top gap-3 bg-white rounded shadow-lg p-10">
        <div class="col-span-12">
            <h1 class="text-2xl text-center font-bold mb-2">
                {{ __('front/homePage.Our Branches') }}
            </h1>
        </div>

        <div class="col-span-12">
            <p class="text-gray-700 text-center">
                {{ __('front/homePage.Our Branches Content') }}
            </p>
        </div>
    </div>
</div>
@endsection
