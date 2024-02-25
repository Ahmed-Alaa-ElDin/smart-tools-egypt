<div class="flex flex-col gap-3">

    {{-- Loader : Start --}}
    <x-admin.waiting />
    {{-- Loader : End --}}

    <div class="bg-white">
        <div class="flex justify-between gap-6 items-center">

            {{-- Search Box --}}
            <div class="mt-1 flex rounded-md shadow-sm">
                <span
                    class="inline-flex items-center px-3 ltr:rounded-l-md rtl:rounded-r-md border border-r-0 border-gray-300 bg-gray-50 text-center text-gray-500 text-sm">
                    <span class="material-icons">
                        search
                    </span> </span>
                <input type="text"   wire:model.live='search'
                    class="focus:ring-primary focus:border-primary flex-1 block w-full rounded-none ltr:rounded-r-md rtl:rounded-l-md sm:text-sm border-gray-300"
                    placeholder="{{ __('admin/sitePages.Search ...') }}">
            </div>

            {{-- Pagination Number --}}
            <div class="form-inline justify-end my-2">
                {{ __('pagination.Show') }} &nbsp;
                <select wire:model.live='perPage' class="form-control w-auto px-3 cursor-pointer">
                    <option>5</option>
                    <option>10</option>
                    <option>25</option>
                    <option>50</option>
                    <option>100</option>
                </select>
                &nbsp; {{ __('pagination.results') }}
            </div>
        </div>
    </div>

    {{-- List :: Start --}}
    @foreach ($sections as $key => $section)
        <div class="flex flex-wrap gap-2 w-full justify-between p-2 items-center @if ($key % 2 == 0) bg-red-100 @else bg-gray-100 @endif rounded-xl"
            wire:key='section-{{ $key }}-{{ $section->id }}'>

            {{-- Rank :: Start --}}
            <div class="p-2 text-center">
                <div class="text-sm text-gray-900">
                    @if ($section->rank && $section->rank != 0 && $section->rank <= 11)
                        <div class="flex gap-2 items-center min-w-max">

                            <div>
                                {{-- down :: Start --}}
                                <span
                                    class="material-icons rounded text-white text-lg @if ($section->rank < 10) @if ($key % 2 == 0) bg-primary @else bg-secondary @endif cursor-pointer
@else
bg-gray-200 @endif select-none"
                                    wire:click="rankDown({{ $section->id }})">
                                    expand_more
                                </span>
                                {{-- down :: End --}}

                                {{-- up :: Start --}}
                                <span
                                    class="material-icons rounded text-white text-lg @if ($section->rank > 1) @if ($key % 2 == 0) bg-primary @else bg-secondary @endif cursor-pointer
@else
bg-gray-200 @endif select-none"
                                    wire:click="rankUp({{ $section->id }})">
                                    expand_less
                                </span>
                                {{-- up :: Start --}}
                            </div>

                            <span class="font-bold">
                                {{ $section->rank }}
                            </span>
                        </div>
                    @else
                        <div class="flex gap-2 items-center min-w-max">

                            <div>
                                {{-- down :: Start --}}
                                <span
                                    class="material-icons rounded text-white text-lg @if ($section->rank < 11) @if ($key % 2 == 0) bg-primary @else bg-secondary @endif cursor-pointer
@else
bg-gray-200 @endif select-none"
                                    wire:click="rankDown({{ $section->id }})">
                                    expand_more
                                </span>
                                {{-- down :: End --}}

                                {{-- up :: Start --}}
                                <span
                                    class="material-icons rounded text-white text-lg @if ($section->rank > 1) @if ($key % 2 == 0) bg-primary @else bg-secondary @endif cursor-pointer
@else
bg-gray-200 @endif select-none"
                                    wire:click="rankUp({{ $section->id }})">
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

            {{-- Title :: Start --}}
            <div class="p-2 grow text-center text-black truncate w-50">
                {{ $section->title }}
            </div>
            {{-- Title :: End --}}

            {{-- Type :: Start --}}
            <div class="p-2 text-center text-black truncate">
                <div
                    class="flex flex-col items-center content-center justify-center @if ($key % 2 == 0) bg-primary @else bg-secondary @endif p-1 rounded shadow">
                    <span class="font-bold text-xs mb-1 text-white">
                        {{ __("admin/sitePages.Section'sType") }}
                    </span>
                    <div class="text-sm font-medium text-gray-900 bg-white p-1 w-100 rounded shadow">
                        {{ $section->type == 0 ? __('admin/sitePages.Products List') : ($section->type == 1 ? __('admin/sitePages.Offer') : ($section->type == 2 ? __('admin/sitePages.Flash Sale') : ($section->type == 3 ? __('admin/sitePages.Banners List') : __('admin/sitePages.N/A')))) }}
                    </div>
                </div>
            </div>
            {{-- Type :: End --}}

            {{-- Active :: Start --}}
            <div class="px-6 py-2 text-center">
                <div class="text-sm text-gray-900">{!! $section->active ? '<span class="text-success">' . __('admin/deliveriesPages.Active') . '</span>' : '<span class="text-red-600">' . __('admin/deliveriesPages.Inactive') . '</span>' !!}
                    {!! $section->active ? '<span class="block cursor-pointer material-icons text-success" wire:click="activate(' . $section->id . ')">toggle_on</span>' : '<span class="block cursor-pointer material-icons text-red-600" wire:click="activate(' . $section->id . ')">toggle_off</span>' !!}
                </div>
            </div>
            {{-- Active :: End --}}


            {{-- Buttons :: Start --}}
            <div class="p-2 text-center text-sm font-medium flex gap-2 justify-center">

                {{-- Edit Button --}}
                <a href="{{ route('admin.setting.homepage.edit', [$section->id]) }}"
                    data-title="{{ __('admin/sitePages.Edit') }}" data-toggle="tooltip" data-placement="top"
                    class="m-0">
                    <span class="material-icons p-1 text-lg w-9 h-9 text-white bg-edit hover:bg-editHover rounded">
                        edit
                    </span>
                </a>

                {{-- Delete Button --}}
                <a href="#" data-title="{{ __('admin/sitePages.Remove from list') }}" data-toggle="tooltip"
                    data-placement="top" wire:click.prevent="deleteConfirm({{ $section->id }})"
                    class="m-0">
                    <span class="material-icons p-1 text-lg w-9 h-9 text-white bg-delete hover:bg-deleteHover rounded">
                        delete
                    </span>
                </a>
            </div>
            {{-- Buttons :: End --}}

        </div>
    @endforeach
    {{-- List :: End --}}

    <div class="mt-3">
        {{ $sections->links() }}
    </div>

</div>
