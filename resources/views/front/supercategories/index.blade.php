@extends('layouts.front.site', [
    'titlePage' => __('front/homePage.All Supercategories'),
    'url' => route('front.supercategories.index'),
    'title' => __('front/homePage.All Supercategories'),
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
                    {{ __('front/homePage.All Supercategories') }}
                </li>
            </ol>
        </nav>
        {{-- Breadcrumb :: End --}}

        {{-- Supercategories :: Start --}}
        <section class="grid grid-cols-12 justify-center items-start align-top gap-3 bg-white rounded shadow-lg p-4">
            @forelse ($supercategories as $supercategory)
                {{-- Supercategory :: Start --}}
                <div
                    class="col-span-6 sm:col-span-4 md:col-span-3 p-2 group shadow border border-light rounded-lg hover:shadow-md hover:scale-105 transition overflow-hidden relative">
                    <a href="{{ route('front.supercategories.show', $supercategory->id) }}">
                        @if ($supercategory->icon)
                            {{-- Image : Start --}}
                            <div class="flex justify-center items-center col-span-3 w-100 max-w-100 text-9xl">
                                {!! $supercategory->icon !!}
                            </div>
                            {{-- Image : End --}}
                        @else
                            {{-- Image : Start --}}
                            <div class="col-span-3 w-100 flex justify-center items-center">
                                <span class="material-icons text-center text-9xl">
                                    construction
                                </span>
                            </div>
                            {{-- Image : End --}}
                        @endif
                    </a>

                    <div class="flex flex-col gap-2 my-2 items-center justify-center">
                        {{-- Supercategory Name :: Start --}}
                        <a
                            href="{{ route('front.supercategories.show', $supercategory->id) }}"class="text-center font-bold select-none text-xl max-w-max">
                            {{ $supercategory->name }}
                        </a>
                        {{-- Supercategory Name :: End --}}

                        {{-- Categories No :: Start --}}
                        <a href="{{ route('front.supercategories.show', $supercategory->id) }}"
                            class="text-center rounded-full bg-primaryDarker text-white px-2 py-1 shadow text-sm font-bold">
                            {{ trans_choice('front/homePage.No of categories supercategory', $supercategory->categories_count, ['categories' => $supercategory->categories_count]) }}
                        </a>
                        {{-- Categories No :: End --}}

                        {{-- Subcategories No :: Start --}}
                        <a href="{{ route('front.supercategory.subcategories', ['supercategory_id' => $supercategory->id]) }}"
                            class="text-center rounded-full bg-primaryDark text-white px-2 py-1 shadow text-sm font-bold">
                            {{ trans_choice('front/homePage.No of subcategories supercategory', $supercategory->subcategories_count, ['subcategories' => $supercategory->subcategories_count]) }}
                        </a>
                        {{-- Subcategories No :: End --}}

                        {{-- Products No :: Start --}}
                        <a href="{{ route('front.supercategory.products', ['supercategory_id' => $supercategory->id]) }}"
                            class="text-center rounded-full bg-primary text-white px-2 py-1 shadow text-sm font-bold">
                            {{ trans_choice('front/homePage.No of products supercategory', $supercategory->products_count, ['products' => $supercategory->products_count]) }}
                        </a>
                        {{-- Products No :: End --}}
                    </div>
                </div>
                {{-- Supercategory :: End --}}

                {{-- Pagination :: Start --}}
                @if ($loop->last)
                    <div class="col-span-12">
                        {{ $supercategories->links() }}
                    </div>
                @endif
                {{-- Pagination :: End --}}
            @empty
                <div class="col-span-12 text-center font-bold p-3">
                    {{ __('front/homePage.No Supercategories have been Found') }}
                </div>
            @endforelse
        </section>
        {{-- Supercategories :: Start --}}
    </div>
@endsection

{{-- Extra Scripts --}}
@push('js')
    <script>
        $(document).ready(function() {

        });
    </script>
@endpush
