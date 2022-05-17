<div class="flex flex-col gap-3">

    {{-- Loader : Start --}}
    <x-admin.waiting />
    {{-- Loader : End --}}

    {{-- Add Banner Button : Start --}}
    @if (count($banners) < 3)
        <div class="flex justify-center gap-3 items-center">
            {{-- Add Banner to list :: Start --}}
            <button wire:click.stop.prevent="$set('addBanner',1)"
                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-xl shadow btn btn-sm">
                <span class="material-icons rtl:ml-1 ltr:mr-1">
                    add
                </span>
                {{ __('admin/sitePages.Add Banners to Section') }}
            </button>
            {{-- Add Banner to list :: End --}}
        </div>
    @endif
    {{-- Add Banner Button : End --}}

    {{-- List :: Start --}}
    @foreach ($banners as $key => $banner)
        <div class="flex flex-wrap gap-2 w-full justify-between p-3 items-center overflow-auto scrollbar scrollbar-hidden-x @if ($key % 2 == 0) bg-red-100 @else bg-gray-100 @endif rounded-xl"
            wire:key='banner-{{ $key }}-{{ $banner['id'] }}'>

            {{-- Rank :: Start --}}
            <div class="p-2 text-center">
                <div class="text-sm text-gray-900">
                    @if ($banner['rank'] && $banner['rank'] != 0 && $banner['rank'] <= 11)
                        <div class="flex gap-2 items-center min-w-max">

                            <div>
                                {{-- down :: Start --}}
                                <span
                                    class="material-icons rounded text-white text-lg @if ($banner['rank'] < 3) @if ($key % 2 == 0) bg-primary @else bg-secondary @endif cursor-pointer
@else
bg-gray-200 @endif select-none"
                                    wire:click="rankDown({{ $banner['id'] }})">
                                    expand_more
                                </span>
                                {{-- down :: End --}}

                                {{-- up :: Start --}}
                                <span
                                    class="material-icons rounded text-white text-lg @if ($banner['rank'] > 1) @if ($key % 2 == 0) bg-primary @else bg-secondary @endif cursor-pointer
@else
bg-gray-200 @endif select-none"
                                    wire:click="rankUp({{ $banner['id'] }})">
                                    expand_less
                                </span>
                                {{-- up :: Start --}}
                            </div>

                            <span class="font-bold">
                                {{ $banner['rank'] }}
                            </span>
                        </div>
                    @else
                        <div class="flex gap-2 items-center min-w-max">

                            <div>
                                {{-- down :: Start --}}
                                <span
                                    class="material-icons rounded text-white text-lg @if ($banner['rank'] < 3) @if ($key % 2 == 0) bg-primary @else bg-secondary @endif cursor-pointer
@else
bg-gray-200 @endif select-none"
                                    wire:click="rankDown({{ $banner['id'] }})">
                                    expand_more
                                </span>
                                {{-- down :: End --}}

                                {{-- up :: Start --}}
                                <span
                                    class="material-icons rounded text-white text-lg @if ($banner['rank'] > 1) @if ($key % 2 == 0) bg-primary @else bg-secondary @endif cursor-pointer
@else
bg-gray-200 @endif select-none"
                                    wire:click="rankUp({{ $banner['id'] }})">
                                    expand_less
                                </span>
                                {{-- up :: Start --}}
                            </div>

                            <span class="font-bold">
                                0
                            </span>
                        </div>
                    @endif
                </div>
            </div>
            {{-- Rank :: End --}}

            <div class="flex flex-col gap-2 w-1/2">
                {{-- Image :: Start --}}
                <div class="text-center flex justify-center w-full">
                    <img src="{{ asset('storage/images/banners/original/' . $banner['banner_name']) }}"
                        alt="{{ $banner['description'][session('locale')] }}" class="rounded-lg w-50 "
                        draggable="false">
                </div>
                {{-- Image :: End --}}

                <div class="flex flex-col md:flex-row max-w-full justify-between">
                    {{-- Name :: Start --}}
                    <div class="p-2 text-center text-black truncate">
                        {{ $banner['description'][session('locale')] }}
                    </div>
                    {{-- Name :: End --}}

                    {{-- Link :: Start --}}
                    <div class="p-2 text-center">
                        <a href="{{ $banner['link'] }}">
                            {{ $banner['link'] }}
                        </a>
                    </div>
                    {{-- Link :: End --}}
                </div>
            </div>

            {{-- Buttons :: Start --}}
            <div class="p-2 text-center text-sm font-medium flex gap-2 justify-center">

                {{-- Edit Button --}}
                <a href="{{ route('admin.site.banners.edit', [$banner['id']]) }}" target="_blank"
                    data-title="{{ __('admin/sitePages.Edit') }}" data-toggle="tooltip" data-placement="top"
                    class="m-0">
                    <span class="material-icons p-1 text-lg w-9 h-9 text-white bg-edit hover:bg-editHover rounded">
                        edit
                    </span>
                </a>

                {{-- Delete Button --}}
                <a href="#" data-title="{{ __('admin/sitePages.Remove from list') }}" data-toggle="tooltip"
                    data-placement="top" wire:click.prevent="removeBanner({{ $banner['id'] }})"
                    class="m-0">
                    <span
                        class="material-icons p-1 text-lg w-9 h-9 text-white bg-delete hover:bg-deleteHover rounded-circle">
                        close
                    </span>
                </a>
            </div>
            {{-- Buttons :: End --}}

        </div>
    @endforeach
    {{-- List :: End --}}


    {{-- Add Banner Modal : Start --}}
    <div wire:click="$set('addBanner',0)"
        class="backdrop-blur-sm cursor-pointer @if ($addBanner) flex
        @else
        hidden @endif fixed top-0 left-0 z-50 flex justify-center items-center gap-4 w-100 h-100 bg-gray-500/[.4]">
        <div wire:click.stop="$set('addBanner',1)"
            class="cursor-default rounded-xl bg-white w-3/4 md:w-1/2 border-4 border-primary p-3 flex flex-col gap-2">

            <h4 class="h5 md:h4 font-bold mb-2 text-center m-0 event-none">
                {{ __('admin/sitePages.Add Banners to Section') }}
            </h4>

            <div class="col-span-12 w-full grid grid-cols-12 gap-x-4 gap-y-2 items-center rounded text-center">
                <label for="banner_name"
                    class="col-span-12 md:col-span-3 font-bold m-0 text-center font-bold text-xs text-gray-700 cursor-pointer">
                    {{ __("admin/sitePages.Banner's Description") }}
                </label>

                <div class="col-span-12 md:col-span-9">
                    <input
                        class="py-1 w-full rounded text-center border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300"
                        type="text" wire:model.debounce.300ms="searchBanner" onfocus="Livewire.emit('showResults',1);"
                        id="banner_name" placeholder="{{ __("admin/sitePages.Enter Banner's Description") }}"
                        maxlength="100" autocomplete="off" required>

                    @if ($searchBanner != '' && $showResult)
                        <div class="relative h-0">
                            <div class="absolute top-0 w-full flex flex-col justify-center items-center">
                                <ul
                                    class="bg-white w-100 z-10 rounded-b-xl overflow-auto border-x border-b border-primary px-1 max-h-48 scrollbar scrollbar-hidden-y">
                                    @forelse ($banners_list as $key => $banner)
                                        {{-- Item :: Start --}}
                                        <li wire:click.stop.prevent="bannerSelected({{ $banner->id }},'{{ $banner->description }}')"
                                            wire:key="add-banner-{{ $key }}-{{ $banner->id }}"
                                            class="btn bg-white border-b p-3 flex flex-wrap justify-center items-center gap-3 rounded-xl overflow-hidden">

                                            {{-- Banner's Description --}}
                                            <div
                                                class="flex flex-col justify-center items-center text-center gap-2 grow max-w-full">
                                                <div class="overflow-hidden">
                                                    <img src="{{ asset('storage/images/banners/original/' . $banner->banner_name) }}"
                                                        alt="{{ $banner->description }}" class="rounded-lg"
                                                        draggable="false">
                                                </div>
                                                <span class="font-bold text-black max-w-full truncate">
                                                    {{ $banner->description }}
                                                </span>
                                                <span class="text-xs text-gray-600">
                                                    {{ $banner->link }}
                                                </span>
                                            </div>
                                        </li>
                                        {{-- Item :: End --}}
                                    @empty
                                        <li
                                            class="border-b py-3 flex flex-wrap justify-center items-center gap-3 rounded-xl font-bold">
                                            {{ __('admin/sitePages.No Results according to your search') }}
                                        </li>
                                    @endforelse

                                </ul>
                            </div>

                        </div>
                    @endif
                </div>

            </div>


            {{-- Buttons Section Start --}}
            <div class="col-span-12 w-full flex mt-2 justify-around">
                {{-- Add --}}
                <button type="button" wire:click.prevent="add"
                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-xl shadow btn btn-sm">{{ __('admin/sitePages.Add') }}</button>
                {{-- Back --}}
                <a href="#" wire:click.stop.prevent="$set('addBanner',0)"
                    class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-xl shadow btn btn-sm">{{ __('admin/sitePages.Cancel') }}</a>

            </div>
            {{-- Buttons Section End --}}
        </div>
    </div>
    {{-- Add Banner Modal : Start --}}

</div>
