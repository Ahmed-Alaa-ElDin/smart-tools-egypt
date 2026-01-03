<div class="block lg:hidden">
    <!-- Hamburger Button -->
    <button
        class="relative inline-flex items-center justify-center p-2 text-gray-600 transition-all duration-200 rounded-xl hover:bg-red-50 hover:text-red-600 focus:outline-none group"
        type="button" data-drawer-target="drawer-navigation" data-drawer-show="drawer-navigation"
        aria-controls="drawer-navigation"
        data-drawer-placement="{{ LaravelLocalization::getCurrentLocaleDirection() == 'rtl' ? 'left' : 'right' }}">
        <span class="material-icons text-4xl group-hover:scale-110 transition-transform">menu</span>
    </button>

    <!-- Drawer Component -->
    <div id="drawer-navigation"
        class="fixed top-0 z-50 h-screen p-0 overflow-y-auto transition-transform bg-white w-80 {{ LaravelLocalization::getCurrentLocaleDirection() == 'rtl' ? '-translate-x-full left-0' : 'translate-x-full right-0' }} shadow-2xl"
        tabindex="-1" aria-labelledby="drawer-navigation-label">

        <!-- Drawer Header -->
        <div class="flex items-center justify-between p-5 bg-red-100/50 sticky top-0 z-10 backdrop-blur-lg border-b">
            <h5 id="drawer-navigation-label" class="text-lg font-bold text-gray-800 flex items-center gap-2">
                <span class="material-icons text-red-600">category</span>
                {{ __('front/homePage.Main Categories') }}
            </h5>
            <button type="button" data-drawer-hide="drawer-navigation" aria-controls="drawer-navigation"
                class="text-gray-400 bg-white shadow-sm hover:bg-red-50 hover:text-red-600 rounded-full text-sm p-1.5 inline-flex items-center transition-colors">
                <span class="material-icons">close</span>
                <span class="sr-only">Close menu</span>
            </button>
        </div>

        <div class="p-4">
            <ul class="space-y-3 font-medium">
                @foreach ($topSupercategories as $topSupercategory)
                    <li x-data="{ open: false }"
                        class="bg-gray-50/50 rounded-2xl overflow-hidden border border-gray-100 shadow-sm transition-all hover:shadow-md">
                        <div class="flex items-center justify-between w-full p-1 group">
                            <a href="{{ route('front.supercategory.products', $topSupercategory->id) }}"
                                class="flex items-center gap-3 p-3 flex-grow text-gray-700 hover:text-red-600 transition-colors">
                                <span
                                    class="material-icons p-2 bg-white rounded-xl shadow-sm group-hover:bg-red-50 transition-colors">
                                    {!! $topSupercategory->icon ?? 'construction' !!}
                                </span>
                                <span class="font-bold text-sm">{{ $topSupercategory->name }}</span>
                            </a>
                            @if ($topSupercategory->categories->count() > 0)
                                <button type="button" @click="open = !open"
                                    class="p-4 text-gray-400 hover:text-red-600 transition-colors">
                                    <span class="material-icons transition-transform duration-300"
                                        :class="open ? 'rotate-180' : ''">expand_more</span>
                                </button>
                            @endif
                        </div>

                        @if ($topSupercategory->categories->count() > 0)
                            <div x-show="open" x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 -translate-y-2"
                                x-transition:enter-end="opacity-100 translate-y-0"
                                class="bg-white/80 border-t border-gray-100">
                                <ul class="py-2 divide-y divide-gray-50">
                                    @foreach ($topSupercategory->categories as $category)
                                        <li x-data="{ openSub: false }">
                                            <div class="flex items-center justify-between w-full px-4 py-3 group/sub">
                                                <a href="{{ route('front.category.products', $category->id) }}"
                                                    class="text-sm text-gray-600 hover:text-red-500 font-semibold transition-colors">
                                                    {{ $category->name }}
                                                </a>
                                                @if ($category->subcategories->count() > 0)
                                                    <button type="button" @click="openSub = !openSub"
                                                        class="p-1 text-gray-400 hover:text-red-500 transition-colors">
                                                        <span
                                                            class="material-icons text-xl transition-transform duration-300"
                                                            :class="openSub ? 'rotate-180' : ''">add</span>
                                                    </button>
                                                @endif
                                            </div>

                                            @if ($category->subcategories->count() > 0)
                                                <div x-show="openSub" x-transition class="bg-gray-50/30">
                                                    <ul class="py-1 pb-3">
                                                        @foreach ($category->subcategories as $subcategory)
                                                            <li>
                                                                <a href="{{ route('front.subcategories.show', $subcategory->id) }}"
                                                                    class="flex items-center w-full px-10 py-2 text-xs text-gray-500 hover:text-red-400 transition-colors relative before:content-[''] before:absolute before:w-1 before:h-1 before:bg-gray-300 before:rounded-full ltr:before:left-7 rtl:before:right-7">
                                                                    {{ $subcategory->name }}
                                                                </a>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </li>
                @endforeach
            </ul>

            <div class="mt-8 mb-6">
                <a href="{{ route('front.supercategories.index') }}"
                    class="flex items-center justify-center gap-2 w-full p-4 text-sm font-bold text-white bg-gradient-to-r from-red-600 to-red-500 rounded-2xl shadow-lg shadow-red-200 hover:scale-[1.02] active:scale-[0.98] transition-all">
                    <span class="material-icons text-lg">apps</span>
                    {{ __('front/homePage.Show All') }}
                </a>
            </div>
        </div>
    </div>
</div>
