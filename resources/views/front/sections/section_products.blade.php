@extends('layouts.front.site', [
    'titlePage' => $section->title,
    'url' => route('front.section-products', [$section->id]),
    'title' => $section->title,
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
                    {{ $section->title }}
                </li>
            </ol>
        </nav>
        {{-- Breadcrumb :: End --}}

        {{-- Section :: Start --}}
        <section class="bg-white rounded shadow-lg p-4">
            {{-- Section Header :: Start  --}}
            <div class="border-b border-gray-300">
                <div class="flex justify-start items-center gap-4 p-3 border-b-2 border-primary max-w-max">
                    <span class="inline-bolck text-secondaryDarker text-xl font-bold">
                        {{ $section->title }}
                    </span>
                </div>
            </div>
            {{-- Section Header :: End  --}}

            {{-- Section's Items :: Start --}}
            <div class="grid grid-cols-12 justify-center items-start align-top gap-3">
                @foreach ($items as $item)
                    <div class="mt-2 col-span-12 sm:col-span-6 md:col-span-4 lg:col-span-3">
                        <x-front.product-box-small :item="$item->toArray()" wire:key="product-{{ rand() }}" />
                    </div>
                @endforeach

                <div class="col-span-12">
                    {{ $items->links() }}
                </div>
            </div>
            {{-- Section's Items :: End --}}

        </section>
        {{-- Section :: Start --}}
    </div>
@endsection
