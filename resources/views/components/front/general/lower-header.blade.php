<div class="bg-white border-top border-gray-200 py-1 scrollbar scrollbar-hidden-x">
    <div class="container">
        <ul class="flex flex-nowrap justify-between lg:justify-center items-center gap-2 text-center">
            @foreach ($nav_links as $nav_link)
                <li class="min-w-max">
                    <a href="{{ route('front.homepage') . '/' . $nav_link->url }}"
                        class="opacity-60 text-xs md:text-sm font-bold px-3 py-2 inline-block fw-600 hover:opacity-100 hover:text-gray-900 text-reset">
                        {{ $nav_link->name }}
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</div>
