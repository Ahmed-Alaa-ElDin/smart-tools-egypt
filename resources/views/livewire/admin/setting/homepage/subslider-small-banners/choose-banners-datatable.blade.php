<div>

    {{-- Loader : Start --}}
    <x-admin.waiting />
    {{-- Loader : End --}}

    <div class="flex flex-col">

        {{-- Search and Pagination Control --}}
        <div class="py-3 bg-white space-y-3">

            <div class="flex justify-between gap-6 items-center">
                {{-- Search Box --}}
                <div class="col-span-1">
                    <div class="mt-1 flex rounded-md shadow-sm">
                        <span
                            class="inline-flex items-center px-3 ltr:rounded-l-md rtl:rounded-r-md border border-r-0 border-gray-300 bg-gray-50 text-center text-gray-500 text-sm">
                            <span class="material-icons">
                                search
                            </span> </span>
                        <input type="text" wire:model.live='search'
                            class="focus:ring-primary focus:border-primary flex-1 block w-full rounded-none ltr:rounded-r-md rtl:rounded-l-md sm:text-sm border-gray-300"
                            placeholder="{{ __('admin/sitePages.Search ...') }}">
                    </div>
                </div>

                {{-- Pagination Number --}}
                <div class="form-inline col-span-1 justify-end my-2">
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
        {{-- Search and Pagination Control --}}


        <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">

                <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        {{-- Datatable Header --}}
                        <thead class="bg-gray-50">
                            <tr>
                                {{-- Selectbox Header --}}
                                <th scope="col"></th>

                                {{-- Name Header --}}
                                <th wire:click="sortBy('banner_name')" scope="col"
                                    class="px-6 py-3 text-center max-w-36 text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer select-none">
                                    <div class="min-w-max">
                                        {{ __('admin/sitePages.Name') }} &nbsp;
                                        @include('partials._sort_icon', [
                                            'field' => 'banner_name',
                                        ])
                                    </div>
                                </th>

                                {{-- Description Header --}}
                                <th wire:click="sortBy('description')" scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer select-none">
                                    <div class="min-w-max">
                                        {{ __('admin/sitePages.Description') }}&nbsp;
                                        @include('partials._sort_icon', [
                                            'field' => 'description',
                                        ])
                                    </div>
                                </th>

                                {{-- Link Header --}}
                                <th wire:click="sortBy('link')" scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer select-none">
                                    <div class="min-w-max">
                                        {{ __('admin/sitePages.Link') }}&nbsp;
                                        @include('partials._sort_icon', [
                                            'field' => 'link',
                                        ])
                                    </div>
                                </th>

                                {{-- Usage Header --}}
                                <th scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider select-none">
                                    <div class="min-w-max">
                                        {{ __('admin/sitePages.Usage') }}&nbsp;
                                    </div>
                                </th>
                            </tr>
                        </thead>

                        {{-- Datatable Body --}}
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($banners as $banner)
                                <tr>
                                    {{-- Selectbox Body --}}
                                    <td class="px-6 py-2 whitespace-nowrap text-center">
                                        <div class="min-w-max">
                                            <input type="checkbox"
                                                class="appearance-none border-gray-600 rounded-full checked:bg-secondary outline-none ring-0 cursor-pointer"
                                                wire:model.live="selected" value="{{ $banner->id }}">
                                        </div>
                                    </td>

                                    {{-- Photo & Name Body --}}
                                    <td class="px-6 py-2 whitespace-nowrap overflow-hidden max-w-36">
                                        <div class="flex items-center content-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                @if ($banner->banner_name != null)
                                                    <img class="h-10 w-10 rounded-full"
                                                        src="{{ asset('storage/images/banners/cropped100/' . $banner->banner_name) }}"
                                                        alt="{{ $banner->banner_name }}">
                                                @else
                                                    <div
                                                        class="h-10 w-10 rounded-full text-white bg-secondary flex justify-center items-center">
                                                        <span class="material-icons">
                                                            <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true"
                                                                role="img" width="1em" height="1em"
                                                                preserveAspectRatio="xMidYMid meet" viewBox="0 0 64 64">
                                                                <path fill="currentColor"
                                                                    d="M36.604 23.043c-.623-.342-1.559-.512-2.805-.512h-6.693v7.795h6.525c1.295 0 2.268-.156 2.916-.473c1.146-.551 1.721-1.639 1.721-3.268c0-1.757-.555-2.939-1.664-3.542" />
                                                                <path fill="currentColor"
                                                                    d="M32.002 2C15.434 2 2 15.432 2 32s13.434 30 30.002 30s30-13.432 30-30s-13.432-30-30-30m12.82 44.508h-6.693a20.582 20.582 0 0 1-.393-1.555a14.126 14.126 0 0 1-.256-2.5l-.041-2.697c-.023-1.85-.344-3.084-.959-3.701c-.613-.615-1.766-.924-3.453-.924h-5.922v11.377H21.18V17.492h13.879c1.984.039 3.51.289 4.578.748s1.975 1.135 2.717 2.027a9.07 9.07 0 0 1 1.459 2.441c.357.893.537 1.908.537 3.051c0 1.379-.348 2.732-1.043 4.064s-1.844 2.273-3.445 2.826c1.338.537 2.287 1.303 2.844 2.293c.559.99.838 2.504.838 4.537v1.949c0 1.324.053 2.225.16 2.697c.16.748.533 1.299 1.119 1.652v.731z" />
                                                            </svg>
                                                        </span>
                                                    </div>
                                                @endif
                                            </div>
                                            <div
                                                class="ltr:ml-4 rtl:mr-4 text-sm w-32 truncate font-medium text-gray-900">
                                                {{ $banner->banner_name }}
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Description Body --}}
                                    <td class="px-6 py-2 max-w-min whitespace-nowrap overflow-hidden">
                                        <div class="text-sm w-32 truncate font-medium text-gray-900 text-center">
                                            {{ $banner->description }}
                                        </div>
                                    </td>

                                    {{-- Link Body --}}
                                    <td class="px-6 py-2 max-w-min whitespace-nowrap overflow-hidden">
                                        <div class="flex items-center content-center justify-center">
                                            {{ $banner->link }}
                                        </div>
                                    </td>

                                    {{-- Usage Body --}}
                                    <td class="px-6 py-2 max-w-min whitespace-nowrap overflow-hidden">
                                        <div class="flex items-center content-center justify-center gap-1">
                                            @if ($banner->main_slider_banner_count)
                                                <span
                                                    class="text-xs bg-secondaryLight text-white px-2 py-1 rounded-full">
                                                    {{ __('admin/sitePages.Slider') }}
                                                </span>
                                            @endif

                                            @if ($banner->subslider_banner_count)
                                                <span
                                                    class="text-xs bg-secondaryLight text-white px-2 py-1 rounded-full">
                                                    {{ __('admin/sitePages.Subslider') }}
                                                </span>
                                            @endif

                                            @if ($banner->subslider_small_banner_count)
                                                <span
                                                    class="text-xs bg-secondaryLight text-white px-2 py-1 rounded-full">
                                                    {{ __('admin/sitePages.Small Subslider') }}
                                                </span>
                                            @endif

                                            @if ($banner->top_banner)
                                                <span
                                                    class="text-xs bg-secondaryLight text-white px-2 py-1 rounded-full">
                                                    {{ __('admin/sitePages.Top Banner') }}
                                                </span>
                                            @endif

                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-center py-2 font-bold" colspan="5">
                                        {{ $search == '' ? __('admin/sitePages.No data in this table') : __('admin/sitePages.No data available according to your search') }}
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

        {{-- Buttons --}}
        <div class="mt-2 flex items-center justify-content-around">
            {{-- Save --}}
            <button type="button" wire:click.prevent="save"
                class="bg-success hover:bg-successDark text-white font-bold py-2 px-4 rounded-xl shadow btn btn-sm">
                {{ __('admin/sitePages.Save') }}
            </button>

            {{-- Back --}}
            <a href="{{ route('admin.setting.homepage.subslider-small-banners.index') }}"
                class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-xl shadow btn btn-sm">
                {{ __('admin/sitePages.Back') }}
            </a>
        </div>
    </div>
</div>
