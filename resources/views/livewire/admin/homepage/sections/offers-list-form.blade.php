<div>
    {{-- Loader : Start --}}
    <x-admin.waiting />
    {{-- Loader : End --}}
    <div class="flex flex-col gap-3">
        {{-- Search & Pagination : Start --}}
        <div class="flex justify-between gap-6 items-center">
            {{-- Search Box --}}
            <div class="mt-1 flex rounded-md shadow-sm">
                <span
                    class="inline-flex items-center px-3 ltr:rounded-l-md rtl:rounded-r-md border border-r-0 border-gray-300 bg-gray-50 text-center text-gray-500 text-sm">
                    <span class="material-icons">
                        search
                    </span> </span>
                <input type="text" name="company-website" id="company-website" wire:model='search'
                    class="focus:ring-primary focus:border-primary flex-1 block w-full rounded-none ltr:rounded-r-md rtl:rounded-l-md sm:text-sm border-gray-300"
                    placeholder="{{ __('admin/offersPages.Search ...') }}">
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
        {{-- Search & Pagination : End --}}

        {{-- Offers List : Start --}}
        <div>
            <ul class="flex flex-wrap justify-center gap-3">
                @forelse ($offers as $key=>$offer)
                    <li class="flex flex-col gap-2 rounded-xl p-3 @if ($selected_offer == $offer->id) bg-green-100 border-4 border-green-400
                    @else
                    bg-red-100 @endif cursor-pointer shadow"
                        wire:click="selectOffer({{ $offer->id }})">

                        {{-- Banner : Start --}}
                        @if ($offer->banner)
                            <div class="w-full select-none">
                                <img src="{{ asset('storage/images/banners/original/' . $offer->banner) }}"
                                    class="rounded-xl w-72 m-auto" draggable="false">
                            </div>
                        @endif
                        {{-- Banner : Start --}}

                        {{-- Title : Start --}}
                        <span class="block text-black font-bold text-center select-none">{{ $offer->title }}</span>
                        {{-- Title : End --}}

                        {{-- Start Date & End Date : Start --}}
                        <div class="flex flex-wrap gap-2 justify-center items-center select-none">
                            <div
                                class="flex flex-col items-center content-center justify-center bg-green-600 p-1 rounded shadow">
                                <span class="font-bold text-xs mb-1 text-white">
                                    {{ __('admin/sitePages.Start Date') }}
                                </span>
                                <div
                                    class="text-sm font-medium text-gray-900 bg-white p-1 w-100 rounded shadow text-center">
                                    {{ Carbon\Carbon::parse($offer->start_at)->format('d/m/Y') ?? '00/00/0000' }}
                                </div>
                            </div>
                            <div
                                class="flex flex-col items-center content-center justify-center bg-red-600 p-1 rounded shadow">
                                <span class="font-bold text-xs mb-1 text-white">
                                    {{ __('admin/sitePages.End Date') }}
                                </span>
                                <div
                                    class="text-sm font-medium text-gray-900 bg-white p-1 w-100 rounded shadow text-center">
                                    {{ Carbon\Carbon::parse($offer->expire_at)->format('d/m/Y') ?? '00/00/0000' }}
                                </div>
                            </div>
                        </div>
                        {{-- Start Date & End Date : End --}}
                    </li>
                @empty
                    <li class="font-bold">
                        {{ __('admin/sitePages.No data available according to your search') }}
                    </li>
                @endforelse

            </ul>
        </div>
        {{-- Offers List : End --}}

        {{-- Pagination Links : Start --}}
        <div>
            {{ $offers->links() }}
        </div>
        {{-- Pagination Links : End --}}
    </div>

</div>
