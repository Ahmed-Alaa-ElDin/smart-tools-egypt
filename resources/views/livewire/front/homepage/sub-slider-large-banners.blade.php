<div class="grid grid-cols-1 md:grid-cols-2 gap-3 justify-between items-center overflow-hidden">
    @foreach ($subsliderBanners as $subsliderBanner)
        <div class="group relative overflow-hidden text-center">
            <a class="after:content-[''] after:bg-gray-100 after:opacity-10 after:absolute after:-rotate-45 after:w-full after:h-full after:top-0 after:right-full after:transition after:ease-in-out after:delay-150 group-hover:after:left-s group-hover:after:translate-x-full"
                href="{{ str_starts_with($subsliderBanner->banner->link, 'http') ? $subsliderBanner->banner->link : env('APP_URL') . $subsliderBanner->banner->link }}">
                <img loading="lazy"
                    src="{{ asset('storage/images/banners/cropped500/' . $subsliderBanner->banner->banner_name) }}"
                    class="shadow rounded m-auto w-[500px]" alt="{{ $subsliderBanner->banner->description }}">
            </a>
        </div>
    @endforeach
</div>
