<div>
    {{-- Loader : Start --}}
    <x-admin.waiting />
    {{-- Loader : End --}}

    <div class="flex flex-col">
        <div class="py-3 bg-white space-y-6">
            <div class="flex justify-between gap-6 items-center">

                {{-- Search Box --}}
                <div class="mt-1 flex rounded-md shadow-sm">
                    <span
                        class="inline-flex items-center px-3 ltr:rounded-l-md rtl:rounded-r-md border border-r-0 border-gray-300 bg-gray-50 text-center text-gray-500 text-sm">
                        <span class="material-icons">
                            search
                        </span>
                    </span>
                    <input type="text"   wire:model='search'
                        class="focus:ring-primary focus:border-primary flex-1 block w-full rounded-none ltr:rounded-r-md rtl:rounded-l-md sm:text-sm border-gray-300"
                        placeholder="{{ __('admin/sitePages.Search ...') }}">
                </div>

                {{-- Pagination Number --}}
                <div class="form-inline justify-end my-2">
                    {{ __('pagination.Show') }} &nbsp;
                    <select wire:model='perPage' class="form-control w-auto px-3 cursor-pointer">
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
        <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">

                <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">

                    <table class="min-w-full divide-y divide-gray-200">
                        {{-- Datatable Header --}}
                        <thead class="bg-gray-50">
                            <tr>

                                <th>

                                </th>

                                {{-- Rank --}}
                                <th wire:click="sortBy('rank')" scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer select-none">
                                    <div class="min-w-max">
                                        {{ __('admin/sitePages.Rank') }}&nbsp;
                                        @include('partials._sort_icon', ['field' => 'rank'])
                                    </div>
                                </th>

                                {{-- Descrition --}}
                                <th wire:click="sortBy('description->{{ session('locale') }}')" scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer select-none">
                                    {{ __('admin/sitePages.Description') }} &nbsp;
                                    @include('partials._sort_icon', [
                                        'field' => 'description->' . session('locale'),
                                    ])
                                </th>

                                {{-- Link --}}
                                <th wire:click="sortBy('link')" scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer select-none">
                                    {{ __('admin/sitePages.Link') }}
                                    @include('partials._sort_icon', [
                                        'field' => 'link',
                                    ])
                                </th>

                                {{-- Manage --}}
                                <th scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider select-none">
                                    {{ __('admin/sitePages.Manage') }}
                                    <span class="sr-only">{{ __('admin/sitePages.Manage') }}</span>
                                </th>
                            </tr>
                        </thead>

                        {{-- Datatable Body --}}
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($banners as $key => $banner)
                                <tr class="@if ($key % 2 == 0) bg-red-100 @else bg-gray-100 @endif">
                                    {{-- Image Preview --}}
                                    <td class="px-6 py-2 text-center whitespace-nowrap">
                                        <span wire:click="togglePreview({{ $banner->id }})"
                                            class="material-icons rounded-circle w-7 h-7 text-center text-white text-lg bg-secondary select-none cursor-pointer">
                                            @if (in_array($banner->id, $preview_ids))
                                                remove
                                            @else
                                                add
                                            @endif
                                        </span>
                                    </td>

                                    {{-- Rank --}}
                                    <td class="px-6 py-2 text-center whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            @if ($banner->rank && $banner->rank != 0 && $banner->rank <= 10)
                                                <div class="flex gap-2 items-center">

                                                    <div>
                                                        {{-- down : Start --}}
                                                        <span
                                                            class="material-icons rounded text-white text-lg @if ($banner->rank < 11) bg-primary cursor-pointer @else bg-gray-200 @endif select-none"
                                                            wire:click="rankDown({{ $banner->id }})">
                                                            expand_more
                                                        </span>
                                                        {{-- down : End --}}

                                                        {{-- up : Start --}}
                                                        <span
                                                            class="material-icons rounded text-white text-lg @if ($banner->rank > 1) bg-primary cursor-pointer @else bg-gray-200 @endif select-none"
                                                            wire:click="rankUp({{ $banner->id }})">
                                                            expand_less
                                                        </span>
                                                        {{-- up : Start --}}
                                                    </div>

                                                    <span class="font-bold">
                                                        {{ $banner->rank }}
                                                    </span>
                                                </div>
                                            @else
                                                <div class="flex gap-2 items-center">

                                                    <div>
                                                        {{-- down : Start --}}
                                                        <span
                                                            class="material-icons rounded text-white text-lg @if ($banner->rank < 10) bg-primary cursor-pointer @else bg-gray-200 @endif select-none"
                                                            wire:click="rankDown({{ $banner->id }})">
                                                            expand_more
                                                        </span>
                                                        {{-- down : End --}}

                                                        {{-- up : Start --}}
                                                        <span
                                                            class="material-icons rounded text-white text-lg @if ($banner->rank > 1) bg-primary cursor-pointer @else bg-gray-200 @endif select-none"
                                                            wire:click="rankUp({{ $banner->id }})">
                                                            expand_less
                                                        </span>
                                                        {{-- up : Start --}}
                                                    </div>

                                                    <span class="font-bold">
                                                        0
                                                    </span>
                                                </div>
                                            @endif
                                        </div>
                                    </td>

                                    {{-- Description --}}
                                    <td class="px-6 py-2 whitespace-nowrap">
                                        <div class="flex items-center content-center justify-center">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $banner->description }}
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Link --}}
                                    <td class="px-6 py-2 text-center whitespace-nowrap">
                                        <div class="flex items-center content-center justify-center">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $banner->link }}
                                            </div>
                                        </div>
                                    </td>

                                    <td class="px-6 py-2 whitespace-nowrap text-center text-sm font-medium">

                                        {{-- Edit Button --}}
                                        <a href="{{ route('admin.setting.homepage.banners.edit', [$banner->id]) }}"
                                            title="{{ __('admin/sitePages.Edit') }}" class="m-0">
                                            <span
                                                class="material-icons p-1 text-lg w-9 h-9 text-white bg-edit hover:bg-editHover rounded">
                                                edit
                                            </span>
                                        </a>

                                        {{-- Delete Button --}}
                                        <a href="#" title="{{ __('admin/sitePages.Delete') }}"
                                            wire:click.prevent="deleteConfirm({{ $banner->id }})"
                                            class="m-0">
                                            <span
                                                class="material-icons p-1 text-lg w-9 h-9 text-white bg-delete hover:bg-deleteHover rounded">
                                                delete
                                            </span>
                                        </a>
                                    </td>
                                </tr>
                                @if (in_array($banner->id, $preview_ids))
                                    <tr class="@if ($key % 2 == 0) bg-red-100 @else bg-gray-100 @endif">
                                        <td colspan="5" class="text-center px-6 py-2">
                                            <img src="{{ asset('storage/images/banners/original/' . $banner->banner_name) }}"
                                                class="rounded-xl h-24 m-auto">
                                        </td>
                                    </tr>
                                @endif
                            @empty
                                <tr>
                                    <td class="text-center py-2 font-bold" colspan="5">
                                        {{ $search == ''? __('admin/sitePages.No data in this table'): __('admin/sitePages.No data available according to your search') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="mt-4">
            {{ $banners->links() }}
        </div>
    </div>
</div>
