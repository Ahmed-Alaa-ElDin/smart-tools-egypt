<div id="main-slider" class="splide h-full w-full rounded overflow-hidden">
    <div class="splide__track">
        <ul class="splide__list shadow">
            @foreach ($banners as $banner)
                <li class="splide__slide">
                    <a
                        href="{{ str_starts_with($banner->banner->link, 'http') ? $banner->banner->link : env('APP_URL') . $banner->banner->link }}">
                        <img loading="lazy"
                            src="{{ asset('storage/images/banners/cropped1000/' . $banner->banner->banner_name) }}"
                            class="w-[1000px]" alt="{{ $banner->banner->description }}">
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</div>
