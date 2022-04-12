<div>
    <div class="flex flex-col">
        <div class="py-3 bg-white space-y-6">
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
        </div>
        <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">

                <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        {{-- Datatable Header --}}
                        <thead class="bg-gray-50">
                            <tr>

                                {{-- Code --}}
                                <th wire:click="sortBy('code')" scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer select-none">
                                    {{ __('admin/offersPages.Code') }} &nbsp;
                                    @include('partials._sort_icon', [
                                        'field' => 'code',
                                    ])
                                </th>

                                {{-- Value --}}
                                <th wire:click="sortBy('value')" scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer select-none">
                                    {{ __('admin/offersPages.Value') }}
                                    @include('partials._sort_icon', [
                                        'field' => 'value',
                                    ])
                                </th>

                                {{-- Expiration Date --}}
                                <th wire:click="sortBy('expire_at')" scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer select-none">
                                    <div class="min-w-max">
                                        {{ __('admin/offersPages.Expiration Date') }}
                                        @include('partials._sort_icon', [
                                            'field' => 'expire_at',
                                        ])
                                    </div>
                                </th>

                                {{-- Expiration Date --}}
                                <th wire:click="sortBy('number')" scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer select-none">
                                    <div class="min-w-max">
                                        {{ __('admin/offersPages.Number') }}
                                        @include('partials._sort_icon', [
                                            'field' => 'number',
                                        ])
                                    </div>
                                </th>

                                {{-- Manage --}}
                                <th scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider select-none">
                                    {{ __('admin/offersPages.Manage') }}
                                    <span class="sr-only">{{ __('admin/offersPages.Manage') }}</span>
                                </th>
                            </tr>
                        </thead>

                        {{-- Datatable Body --}}
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($coupons as $coupon)
                                {{-- Code --}}
                                <tr>
                                    <td class="px-6 py-2 whitespace-nowrap">
                                        <div class="flex items-center content-center justify-center">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $coupon->code }}
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Value --}}
                                    <td class="px-6 py-2 whitespace-nowrap">
                                        <div class="flex items-center content-center justify-center">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $coupon->value }}
                                                {{ $coupon->type == 0 ? '%' : __('admin/offersPages.EPG') }}
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Expiration Date --}}
                                    <td class="px-6 py-2 text-center whitespace-nowrap">
                                        <div class="flex items-center content-center justify-center">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $coupon->expire_at }}
                                                <br>
                                                <span class="text-gray-500">
                                                    {{ trans_choice('admin/offersPages.Days Remaining',intval(Carbon\Carbon::now()->diffInDays($coupon->expire_at, false)),['days' => Carbon\Carbon::now()->diffInDays($coupon->expire_at, false)]) }}
                                                </span>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Number --}}
                                    <td class="px-6 py-2 whitespace-nowrap">
                                        <div class="flex items-center content-center justify-center">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $coupon->number ?? __('admin/offersPages.Unlimited') }}
                                            </div>
                                        </div>
                                    </td>

                                    <td class="px-6 py-2 whitespace-nowrap text-center text-sm font-medium">

                                        {{-- Edit Button --}}
                                        @can('Edit Country')
                                            <a href="{{ route('admin.coupons.edit', [$coupon->id]) }}"
                                                title="{{ __('admin/offersPages.Edit') }}" class="m-0">
                                                <span
                                                    class="material-icons p-1 text-lg w-9 h-9 text-white bg-edit hover:bg-editHover rounded">
                                                    edit
                                                </span>
                                            </a>
                                        @endcan

                                        {{-- Delete Button --}}
                                        <a href="#" title="{{ __('admin/offersPages.Delete') }}"
                                            wire:click.prevent="deleteConfirm({{ $coupon->id }})"
                                            class="m-0">
                                            <span
                                                class="material-icons p-1 text-lg w-9 h-9 text-white bg-delete hover:bg-deleteHover rounded">
                                                delete
                                            </span>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-center py-2 font-bold" colspan="6">
                                        {{ $search == ''? __('admin/offersPages.No data in this table'): __('admin/offersPages.No data available according to your search') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="mt-4">
            {{ $coupons->links() }}
        </div>
    </div>
</div>
