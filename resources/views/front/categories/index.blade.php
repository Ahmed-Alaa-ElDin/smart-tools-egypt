@extends('layouts.front.site', [
    'titlePage' => __('front/homePage.All Categories'),
    'url' => route('front.category.index'),
    'title' => __('front/homePage.All Categories'),
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
                    {{ __('front/homePage.All Categories') }}
                </li>
            </ol>
        </nav>
        {{-- Breadcrumb :: End --}}

        {{-- Categories :: Start --}}
        <section class="grid grid-cols-12 justify-center items-start align-top gap-3 bg-white rounded shadow-lg p-4">
            @forelse ($categories as $category)
                {{-- Category :: Start --}}
                <div
                    class="col-span-6 sm:col-span-4 md:col-span-3 p-2 group shadow border border-light rounded-lg hover:shadow-md hover:scale-105 transition overflow-hidden relative">
                    <a href="{{ route('front.category.show', $category->id) }}">
                        @if ($category->images->count())
                            {{-- Image : Start --}}
                            <div class="flex justify-center items-center col-span-3 w-100 max-w-100 text-9xl">
                                <img class="mx-auto max-h-52 h-full md:h-52 lazyloaded"
                                    src="{{ asset('storage/images/categories/original/' . $category->images->first()->file_name) }}">
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
                        {{-- Category Name :: Start --}}
                        <a
                            href="{{ route('front.category.show', $category->id) }}"class="text-center font-bold select-none text-xl max-w-max">
                            {{ $category->name }}
                        </a>
                        {{-- Category Name :: End --}}

                        {{-- Subcategories No :: Start --}}
                        <a href="{{ route('front.category.show', $category->id) }}"
                            class="text-center rounded-full bg-primaryDark text-white px-2 py-1 shadow text-sm font-bold">
                            {{ trans_choice('front/homePage.No of subcategories category', $category->subcategories_count, ['subcategories' => $category->subcategories_count]) }}
                        </a>
                        {{-- Subcategories No :: End --}}

                        {{-- Products No :: Start --}}
                        <a href="{{ route('front.category.products', ['category_id' => $category->id]) }}"
                            class="text-center rounded-full bg-primary text-white px-2 py-1 shadow text-sm font-bold">
                            {{ trans_choice('front/homePage.No of products category', $category->products_count, ['products' => $category->products_count]) }}
                        </a>
                        {{-- Products No :: End --}}
                    </div>
                </div>
                {{-- Category :: End --}}

                {{-- Pagination :: Start --}}
                @if ($loop->last)
                    <div class="col-span-12">
                        {{ $categories->links() }}
                    </div>
                @endif
                {{-- Pagination :: End --}}

            @empty
                <div class="col-span-12 text-center font-bold p-3">
                    {{ __('front/homePage.No Categories have been Found') }}
                </div>
            @endforelse
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
