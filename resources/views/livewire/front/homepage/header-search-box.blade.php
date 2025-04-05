<div class="grow front-header-search flex items-center bg-white ">
    <div class="relative grow">
        {{-- Search Box :: Start --}}
        <label class="relative block m-0">
            <span class="sr-only">Search</span>
            <span class="absolute inset-y-0 rtl:left-1.5 ltr:right-1.5 flex items-center pl-2">
                <span class="material-icons">
                    search
                </span>
            </span>
            <input
                class="placeholder:italic placeholder:text-slate-400 text-gray-800 block bg-white w-full border border-slate-300 rounded-md py-2 ltr:pr-10 ltr:pl-3 rtl:pl-10 rtl:pr-3 shadow-sm focus:outline-none focus:border-gray-600 focus:ring-gray-600 focus:ring-1 sm:text-xs md:text-sm font-bold"
                placeholder="{{ __("front/homePage.I'm Shopping for ...") }}" type="text" wire:keydown.enter="seeMore"
                wire:model.live.debounce.300ms="search" name="search" autocomplete="off" enterkeyhint="search"/>
        </label>
        {{-- Search Box :: Start --}}


    </div>


</div>
