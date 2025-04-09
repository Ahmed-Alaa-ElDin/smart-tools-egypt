<section class="offer-bar mb-3">
    <div class="container">
        <div class="grid grid-cols-12 gap-3 items-center justify-between">
            @foreach ($banners as $banner)
                @if (!$loop->last)
                    <div class="group relative col-span-12 md:col-span-6 lg:col-span-4  overflow-hidden ">
                        <a href="{{ str_starts_with($banner->link, 'http') ? $banner->link : env('APP_URL') . $banner->link }}"
                            class="block shadow rounded overflow-hidden after:content-[''] after:bg-gray-100 after:opacity-10 after:absolute after:-rotate-45 after:w-full after:h-full after:top-0 after:right-full after:transition after:ease-in-out after:delay-150 group-hover:after:left-s group-hover:after:translate-x-full">
                            <img src="{{ asset('storage/images/banners/cropped500/' . $banner->banner_name) }}"
                                alt="{{ $banner->banner_name }}" class="img-fluid w-[500px] lazyloaded rounded object-cover object-center">
                        </a>
                    </div>
                @else
                    <div class="group relative col-span-12 md:col-span-6 md:col-start-4 lg:col-span-4 lg:col-start-0  overflow-hidden ">
                        <a href="{{ str_starts_with($banner->link, 'http') ? $banner->link : env('APP_URL') . $banner->link }}"
                            class="block shadow rounded overflow-hidden after:content-[''] after:bg-gray-100 after:opacity-10 after:absolute after:-rotate-45 after:w-full after:h-full after:top-0 after:right-full after:transition after:ease-in-out after:delay-150 group-hover:after:left-s group-hover:after:translate-x-full">
                            <img src="{{ asset('storage/images/banners/cropped500/' . $banner->banner_name) }}"
                                alt="{{ $banner->banner_name }}" class="img-fluid w-[500px] lazyloaded rounded object-cover object-center">
                        </a>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
</section>
