<aside class="col-span-2 hidden lg:block z-10 ">
    <div class="flex justify-around items-center px-3 py-5 bg-red-100 rounded-t shadow">
        <span class="font-bold text-sm">
            {{ __('front/homePage.Main Categories') }}
        </span>
    </div>

    {{-- Main Categories : Start --}}
    <ul class="bg-white rounded-b">
        @foreach ($topSupercategories as $topSupercategory)
            <li class="group" data-id="1">
                <div
                    class="relative w-full cursor-pointer group-hover:shadow p-1 group-hover:after:block after:hidden after:content-[''] after:w-7 after:h-7 after:rotate-45 ltr:after:border-t-8 ltr:after:border-r-8 rtl:after:border-b-8 rtl:after:border-l-8 after:border-white after:absolute ltr:after:-right-1 rtl:after:-left-1 after:top-2">
                    <a href="{{ route('front.supercategory.products', $topSupercategory->id) }}"
                        class="text-truncate text-reset py-2 px-3 block text-sm flex gap-3 items-center">
                        <span class="material-icons">
                            {!! $topSupercategory->icon ?? 'construction' !!}
                        </span>
                        <span class="cat-name">{{ $topSupercategory->name }}</span>
                    </a>
                </div>

                <div
                    class="group-hover:block hidden hover:block absolute max-w-75 bg-white h-full top-0 rtl:right-[16.5%] ltr:left-[16.5%] rounded shadow-lg p-2 loaded overflow-y-scroll scrollbar scrollbar-thin scrollbar-thumb-gray-100 scrollbar-track-white">
                    <div class="card-columns">
                        @foreach ($topSupercategory->categories as $category)
                            <div class="card shadow-none border-0 m-0">
                                <ul class="list-unstyled my-2 text-center w-full">
                                    <li class="fw-600 border-b font-bold text-sm py-2 my-2">
                                        <a class="text-reset"
                                            href="{{ route('front.category.products', $category->id) }}">
                                            {{ $category->name }}
                                        </a>
                                    </li>
                                    @foreach ($category->subcategories as $subcategory)
                                        <li class="mb-2 text-sm">
                                            <a class="text-reset"
                                                href="{{ route('front.subcategories.show', $subcategory->id) }}">
                                                {{ $subcategory->name }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endforeach

                    </div>
                </div>
            </li>
        @endforeach

        <li class="group flex justify-center items-center py-2">
            <a href="{{ route('front.supercategories.index') }}"
                class="btn bg-secondary text-white text-sm py-1 px-2 rounded m-1 font-bold">
                {{ __('front/homePage.Show All') }}
            </a>
        </li>
    </ul>
</aside>
