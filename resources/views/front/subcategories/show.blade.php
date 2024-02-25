@extends('layouts.front.site', [
    'titlePage' => $subcategory->name,
    'url' => route('front.subcategories.show', $subcategory->id),
    'title' => $subcategory->name,
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
                <li class="breadcrumb-item hover:text-primary">
                    <a href="{{ route('front.subcategories.index') }}">
                        {{ __('front/homePage.All Subcategories') }}
                    </a>
                </li>
                <li class="breadcrumb-item text-gray-700 font-bold" aria-current="page">
                    {{ $subcategory->name }}
                </li>
            </ol>
        </nav>
        {{-- Breadcrumb :: End --}}

        {{-- Subcategory :: Start --}}
        <section class="bg-white rounded shadow-lg">
            <div class="border-b border-gray-300">
                <div class="flex justify-start items-center gap-4 p-3 border-b-2 border-primary max-w-max">
                    <div>
                        @if ($subcategory->image_name)
                            {{-- Image : Start --}}
                            <div class="flex justify-center items-center col-span-3">
                                <img class="mx-auto w-16 lazyloaded"
                                    src="{{ asset('storage/images/categories/original/' . $subcategory->image_name) }}">
                            </div>
                            {{-- Image : End --}}
                        @else
                            {{-- Image : Start --}}
                            <div class="col-span-3 w-100 flex justify-center items-center">
                                <span class="material-icons text-center text-6xl">
                                    construction
                                </span>
                            </div>
                            {{-- Image : End --}}
                        @endif
                    </div>
                    <div class="text-xl font-bold">
                        {{ $subcategory->name }}
                    </div>
                </div>
            </div>

            <div class="p-3">
                {{-- Products :: Start --}}
                @if (count($productsIds))
                    @livewire('front.products.general-products-list', ['productsIds' => $productsIds, 'collectionsIds' => []])
                @else
                    <div class="mt-5 mb-3 text-center text-lg font-bold text-gray-600">
                        {{ __('front/homePage.No Products belongs to Subcategory', ['subcategory' => $subcategory->name]) }}
                    </div>
                @endif
                {{-- Products :: End --}}
            </div>

        </section>
        {{-- Subcategory :: Start --}}
    </div>
@endsection

{{-- Extra Scripts --}}
@push('js')
    <script>
        $(document).ready(function() {

        });
    </script>
@endpush
