@extends('layouts.front.site', ['titlePage' => $titlePage])

@section('content')
    <div class="container px-4 py-2 ">
        <nav aria-label="breadcrumb" role="navigation" class="mb-2 flex justify-between items-center">
            {{-- Breadcrumb :: Start --}}
            <ol class="breadcrumb text-sm">
                <li class="breadcrumb-item hover:text-primary">
                    <a href="{{ route('front.homepage') }}">
                        {{ __('front/homePage.Homepage') }}
                    </a>
                </li>
                <li class="breadcrumb-item text-gray-700 font-bold" aria-current="page">
                    {{ __("front/homePage.User's Profile") }}
                </li>
            </ol>
            {{-- Breadcrumb :: End --}}
        </nav>

        <div class="grid grid-cols-12 items-start justify-center gap-4">
            {{-- Mobile Only Nav :: Start --}}
            <nav class="col-span-12 flex flex-wrap items-center justify-center lg:hidden gap-x-3 gap-y-2">
                {{-- Dashboard --}}
                <a href="{{ route('front.profile.index') }}"
                    class="btn btn-sm m-0 flex justify-center items-center py-2 px-3 text-sm font-bold gap-1 rounded-xl @if ($page == 'dashbaord') bg-primary text-white @else bg-white text-gray-700  @endif">
                    <span class="material-icons text-sm">
                        home
                    </span>
                    <span class="text-xs">
                        {{ __('front/homePage.Dashboard') }}
                    </span>
                </a>

                {{-- Edit Profile --}}
                <a href="{{ route('front.profile.edit', $user->id) }}"
                    class="btn btn-sm m-0 flex justify-center items-center py-2 px-3 text-sm font-bold gap-1 rounded-xl @if ($page == 'edit') bg-primary text-white @else bg-white text-gray-700  @endif">
                    <span class="material-icons text-sm">
                        edit
                    </span>
                    <span class="text-xs">
                        {{ __('front/homePage.Edit Profile') }}
                    </span>
                </a>

                {{-- todo : Purchase History --}}
                <a href="{{ route('front.profile.index') }}"
                    class="btn btn-sm m-0 flex justify-center items-center py-2 px-3 text-sm font-bold gap-1 rounded-xl @if ($page == 'history') bg-primary text-white @else bg-white text-gray-700  @endif">
                    <span class="material-icons text-sm">
                        history
                    </span>
                    <span class="text-xs">
                        {{ __('front/homePage.Purchase History') }}
                    </span>
                </a>

                {{-- todo : My Cart --}}
                <a href="{{ route('front.cart') }}"
                    class="btn btn-sm m-0 flex justify-center items-center py-2 px-3 text-sm font-bold gap-1 rounded-xl @if ($page == 'cart') bg-primary text-white @else bg-white text-gray-700  @endif">
                    <span class="material-icons text-sm">
                        shopping_cart
                    </span>
                    <span class="text-xs">
                        {{ __('front/homePage.My Cart') }}
                    </span>
                </a>

                {{-- todo : My Wishlist --}}
                <a href="{{ route('front.profile.index') }}"
                    class="btn btn-sm m-0 flex justify-center items-center py-2 px-3 text-sm font-bold gap-1 rounded-xl @if ($page == 'wishlist') bg-primary text-white @else bg-white text-gray-700  @endif">
                    <span class="material-icons text-sm">
                        favorite
                    </span>
                    <span class="text-xs">
                        {{ __('front/homePage.My Wishlist') }}
                    </span>
                </a>

                {{-- todo : My Compare --}}
                <a href="{{ route('front.profile.index') }}"
                    class="btn btn-sm m-0 flex justify-center items-center py-2 px-3 text-sm font-bold gap-1 rounded-xl @if ($page == 'compare') bg-primary text-white @else bg-white text-gray-700  @endif">
                    <span class="material-icons text-sm">
                        compare
                    </span>
                    <span class="text-xs">
                        {{ __('front/homePage.My Comparison') }}
                    </span>
                </a>

                {{-- todo :: Ordered Products --}}
                <a href="{{ route('front.profile.index') }}"
                    class="btn btn-sm m-0 flex justify-center items-center py-2 px-3 text-sm font-bold gap-1 rounded-xl @if ($page == 'ordered') bg-primary text-white @else bg-white text-gray-700  @endif">
                    <span class="material-icons text-sm">
                        shopping_basket
                    </span>
                    <span class="text-xs">
                        {{ __('front/homePage.Ordered Products') }}
                    </span>
                </a>

            </nav>
            {{-- Mobile Only Nav :: End --}}

            {{-- Large Screen Sidebar :: Start --}}
            <aside
                class="col-span-2 bg-white border-gray-200 rounded-xl hidden lg:flex flex-col justify-center items-start gap-2 p-2">
                {{-- Dashboard --}}
                <a href="{{ route('front.profile.index') }}"
                    class="flex justify-center items-center p-3 font-bold gap-3 @if ($page == 'dashbaord') border-b-2 border-primary text-primary hover:text-primary @else text-gray-700  @endif">
                    <span class="material-icons">
                        home
                    </span>
                    <span class="">
                        {{ __('front/homePage.Dashboard') }}
                    </span>
                </a>

                {{-- Edit Profile --}}
                <a href="{{ route('front.profile.edit', $user->id) }}"
                    class="flex justify-center items-center p-3 font-bold gap-3 @if ($page == 'edit') border-b-2 border-primary text-primary hover:text-primary @else text-gray-700  @endif">
                    <span class="material-icons">
                        edit
                    </span>
                    <span class="">
                        {{ __('front/homePage.Edit Profile') }}
                    </span>
                </a>

                {{-- todo : Purchase History --}}
                <a href="{{ route('front.profile.index') }}"
                    class="flex justify-center items-center p-3 font-bold gap-3 @if ($page == 'history') border-b-2 border-primary text-primary hover:text-primary @else text-gray-700  @endif">
                    <span class="material-icons">
                        history
                    </span>
                    <span class="">
                        {{ __('front/homePage.Purchase History') }}
                    </span>
                </a>

                {{-- todo : My Cart --}}
                <a href="{{ route('front.cart') }}"
                    class="flex justify-center items-center p-3 font-bold gap-3 @if ($page == 'cart') border-b-2 border-primary text-primary hover:text-primary @else text-gray-700  @endif">
                    <span class="material-icons">
                        shopping_cart
                    </span>
                    <span class="">
                        {{ __('front/homePage.My Cart') }}
                    </span>
                </a>

                {{-- todo : My Wishlist --}}
                <a href="{{ route('front.profile.index') }}"
                    class="flex justify-center items-center p-3 font-bold gap-3 @if ($page == 'wishlist') border-b-2 border-primary text-primary hover:text-primary @else text-gray-700  @endif">
                    <span class="material-icons">
                        favorite
                    </span>
                    <span class="">
                        {{ __('front/homePage.My Wishlist') }}
                    </span>
                </a>

                {{-- todo : My Compare --}}
                <a href="{{ route('front.profile.index') }}"
                    class="flex justify-center items-center p-3 font-bold gap-3 @if ($page == 'compare') border-b-2 border-primary text-primary hover:text-primary @else text-gray-700  @endif">
                    <span class="material-icons">
                        compare
                    </span>
                    <span class="">
                        {{ __('front/homePage.My Comparison') }}
                    </span>
                </a>

                {{-- todo :: Ordered Products --}}
                <a href="{{ route('front.profile.index') }}"
                    class="flex justify-center items-center p-3 font-bold gap-3 @if ($page == 'ordered') border-b-2 border-primary text-primary hover:text-primary @else text-gray-700  @endif">
                    <span class="material-icons">
                        shopping_basket
                    </span>
                    <span class="">
                        {{ __('front/homePage.Ordered Products') }}
                    </span>
                </a>
            </aside>
            {{-- Large Screen Sidebar :: End --}}

            {{-- User :: Start --}}
            <section class="col-span-12 lg:col-span-10 grid grid-cols-12 justify-between items-start gap-4">

                @yield('sub-content')

            </section>
            {{-- User :: End --}}
        </div>

    </div>
@endsection
