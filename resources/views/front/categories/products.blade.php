@extends('layouts.front.site', [
    'titlePage' => __('front/homePage.Category Products', ['category' => $category->name]),
    'url' => route('front.category.products', $category->id),
    'title' => __('front/homePage.Category Products', ['category' => $category->name]),
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
                    <a href="{{ route('front.category.index') }}">
                        {{ __('front/homePage.All Categories') }}
                    </a>
                </li>
                <li class="breadcrumb-item text-gray-700 font-bold" aria-current="page">
                    {{ __('front/homePage.Category Products', ['category' => $category->name]) }}
                </li>
            </ol>
        </nav>
        {{-- Breadcrumb :: End --}}

        {{-- Categories :: Start --}}
        <section class="bg-white rounded shadow-lg">
            <div class="border-b border-gray-300">
                <div class="flex justify-start items-center gap-4 p-3 border-b-2 border-primary max-w-max">
                    <div>
                        @if ($category->images->count())
                            {{-- Image : Start --}}
                            <div class="flex justify-center items-center col-span-3">
                                <img class="mx-auto w-16 lazyloaded"
                                    src="{{ asset('storage/images/categories/original/' . $category->images->first()->file_name) }}">
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
                        {{ $category->name }}
                    </div>
                </div>
            </div>

            <div class="p-3">
                {{-- Products :: Start --}}
                <div class="p-3 w-full grid grid-cols-4 gap-3">
                    @forelse ($products as $product)
                        <div class="col-span-2 lg:col-span-1">
                            <x-front.product-box-small :item="$product->toArray()" wire:key="product-{{ rand() }}" />
                        </div>

                        {{-- Pagination :: Start --}}
                        @if ($loop->last)
                            <div class="col-span-4">
                                {{ $products->links() }}
                            </div>
                        @endif
                        {{-- Pagination :: End --}}
                    @empty
                        <div class="col-span-4 text-center font-bold p-2 text-lg">
                            {{ __('front/homePage.No Products belongs to Category', ['category' => $category->name]) }}
                        </div>
                    @endforelse
                </div>
                {{-- Products :: End --}}
            </div>

        </section>
        {{-- Categories :: Start --}}
    </div>
@endsection

{{-- Extra Scripts --}}
@push('js')
    <script>
        $(document).ready(function() {

        });
    </script>
@endpush
