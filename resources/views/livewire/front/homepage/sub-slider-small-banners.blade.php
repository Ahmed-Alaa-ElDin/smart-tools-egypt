<div class="flex flex-wrap gap-2 justify-between items-center overflow-hidden">
    @foreach ($subsliderSmallBanners as $subsliderSmallBanner)
        <div
            class="group relative w-[100px] h-[100px] lg:w-[150px] lg:h-[150px] flex justify-center items-center shadow rounded overflow-hidden bg-white text-center">
            <a class="after:content-[''] after:bg-gray-100 after:opacity-10 after:absolute after:-rotate-45 after:w-full after:h-full after:top-0 after:right-full after:transition after:ease-in-out after:delay-150 group-hover:after:left-s group-hover:after:translate-x-full"
                href="{{ str_starts_with($subsliderSmallBanner->banner->link, 'http') ? $subsliderSmallBanner->banner->link : env('APP_URL') . $subsliderSmallBanner->banner->link }}">
                <img loading="lazy"
                    src="{{ asset('storage/images/banners/cropped150/' . $subsliderSmallBanner->banner->banner_name) }}"
                    srcset="{{ asset('storage/images/banners/cropped75/' . $subsliderSmallBanner->banner->banner_name) }} 75w,
                                {{ asset('storage/images/banners/cropped150/' . $subsliderSmallBanner->banner->banner_name) }} 150w"
                    sizes="(max-width: 768px) 75px, 150px" class="m-auto w-[100px] h-[100px] lg:w-[150px] lg:h-[150px]"
                    alt="{{ $subsliderSmallBanner->banner->description }}">
            </a>
        </div>
    @endforeach
</div>
