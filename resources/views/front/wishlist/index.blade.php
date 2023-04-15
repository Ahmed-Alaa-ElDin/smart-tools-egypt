@extends('layouts.front.site', [
    'titlePage' => __('front/homePage.Wishlist'),
    'url' => route('front.offers.index'),
    'title' => __('front/homePage.Wishlist'),
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
                    {{ __('front/homePage.Wishlist') }}
                </li>
            </ol>
        </nav>
        {{-- Breadcrumb :: End --}}

        {{-- Wishlist Items :: Start --}}
        @livewire('front.wishlist.wishlist-items')
        {{-- Wishlist Items :: Start --}}
    </div>
@endsection

{{-- Extra Scripts --}}
@push('js')
    <script>
        $(document).ready(function() {});
    </script>
@endpush
