@extends('layouts.front.site', [
    'titlePage' => __('front/homePage.Supercategory Subcategories', ['supercategory' => $supercategory->name]),
    'url' => route('front.supercategory.subcategories', $supercategory->id),
    'title' => __('front/homePage.Supercategory Subcategories', ['supercategory' => $supercategory->name]),
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
                    <a href="{{ route('front.supercategory.index') }}">
                        {{ __('front/homePage.All Supercategories') }}
                    </a>
                </li>
                <li class="breadcrumb-item text-gray-700 font-bold" aria-current="page">
                    {{ __('front/homePage.Supercategory Subcategories', ['supercategory' => $supercategory->name]) }}
                </li>
            </ol>
        </nav>
        {{-- Breadcrumb :: End --}}

        {{-- Supercategories :: Start --}}
        <section class="bg-white rounded shadow-lg">
            <div class="border-b border-gray-300">
                <div class="flex justify-start items-center gap-4 p-3 border-b-2 border-primary max-w-max">
                    <div>
                        @if ($supercategory->icon)
                            {{-- Image : Start --}}
                            <div class="flex justify-center items-center col-span-3 w-16 max-w-100 text-6xl">
                                {!! $supercategory->icon !!}
                            </div>
                            {{-- Image : End --}}
                        @else
                            {{-- Image : Start --}}
                            <div class="col-span-3 w-16 flex justify-center items-center">
                                <span class="material-icons text-center text-6xl">
                                    construction
                                </span>
                            </div>
                            {{-- Image : End --}}
                        @endif
                    </div>
                    <div class="text-xl font-bold">
                        {{ $supercategory->name }}
                    </div>
                </div>
            </div>

            <div class="p-3">
                {{-- Products :: Start --}}
                <div class="p-3 w-full grid grid-cols-12 gap-3">
                    @forelse ($subcategories as $subcategory)
                        <div
                            class="col-span-6 sm:col-span-4 md:col-span-3 p-2 group shadow border border-light rounded-lg hover:shadow-md hover:scale-105 transition overflow-hidden relative">
                            <a href="{{ route('front.subcategory.show', $subcategory->id) }}">
                            @if ($subcategory->images_name)
                                {{-- Image : Start --}}
                                <div class="flex justify-center items-center col-span-3 w-100 max-w-100 text-9xl">
                                    <img class="mx-auto max-h-52 h-full md:h-52 lazyloaded"
                                        src="{{ asset('storage/images/categories/original/' . $subcategory->images_name) }}">
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
                                {{-- Subercategory Name :: Start --}}
                                <a href="{{ route('front.subcategory.show', $subcategory->id) }}" class="text-center font-bold select-none text-xl max-w-max">
                                    {{ $subcategory->name }}
                                </a>
                                {{-- Subercategory Name :: End --}}

                                {{-- Products No :: Start --}}
                                <a href="{{ route('front.subcategory.show',$subcategory->id) }}"
                                    class="text-center rounded-full bg-primary text-white px-2 py-1 shadow text-sm font-bold">
                                    {{ trans_choice('front/homePage.No of products subcategory', $subcategory->products_count, ['products' => $subcategory->products_count]) }}
                                </a>
                                {{-- Products No :: End --}}
                            </div>
                        </div>

                        {{-- Pagination :: Start --}}
                        @if ($loop->last)
                            <div class="col-span-12">
                                {{ $subcategories->links() }}
                            </div>
                        @endif
                        {{-- Pagination :: End --}}
                    @empty
                        <div class="col-span-12 text-center font-bold p-2 text-lg">
                            {{ __('front/homePage.No Subcategories belongs to Supercategory', ['supercategory' => $supercategory->name]) }}
                        </div>
                    @endforelse
                </div>
                {{-- Products :: End --}}
            </div>

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
