<div class="relative bg-white border-b w-full overflow-hidden h-[50px] text-center">
    <a href="{{ $banner->link }}">
        <img class="w-full" src="{{ asset('storage/images/banners/original/' . $banner->banner_name) }}" alt="">
    </a>
    <span
        class="material-icons remove_banner_button text-sm font-bold flex items-center justify-center absolute left-3 rtl:right-3 top-[50%] -translate-y-[50%] cursor-pointer text-black rounded-circle w-6 h-6 bg-white">
        close
    </span>
</div>
