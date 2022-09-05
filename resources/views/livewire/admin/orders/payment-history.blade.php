<div>
    {{-- Order Summary :: Start --}}
    {{-- @dd($order) --}}
    <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th
                        class="px-6 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider select-none">
                        #</th>
                    <th
                        class="px-6 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider select-none">
                        {{ __('admin/ordersPages.Customer Info.') }}</th>
                    <th
                        class="px-6 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider select-none">
                        {{ __('admin/ordersPages.Num of Items') }}</th>
                    <th
                        class="px-6 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider select-none">
                        {{ __('admin/ordersPages.Total') }}</th>
                    <th
                        class="px-6 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider select-none">
                        {{ __('admin/ordersPages.Paid') }}</th>
                    <th
                        class="px-6 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider select-none">
                        {{ __('admin/ordersPages.Unpaid') }}</th>
                    <th
                        class="px-6 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider select-none">
                        {{ __('admin/ordersPages.Refund') }}</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <tr>
                    <td class="px-6 py-2 text-center whitespace-nowrap font-bold">
                        {{ $order->id }}
                    </td>
                    <td class="px-6 py-2 text-center whitespace-nowrap">
                        <div class="flex flex-col justify-center center">
                            <span class="font-bold">
                                {{ $order->user->f_name . ' ' . $order->user->l_name }}
                            </span>
                            <span>
                                {{ $order->user->phones()->first()->phone }}
                            </span>
                        </div>
                    </td>
                    <td class="px-6 py-2 text-center whitespace-nowrap">
                        {{ $order->num_of_items }}
                    </td>
                    <td class="px-6 py-2 text-center whitespace-nowrap">
                        {{ $order->total }}
                    </td>
                    <td class="px-6 py-2 text-center whitespace-nowrap">
                        {{ $order->paid }}
                    </td>
                    <td class="px-6 py-2 text-center whitespace-nowrap">
                        {{ $order->unpaid }}
                    </td>
                    <td class="px-6 py-2 text-center whitespace-nowrap">
                        {{ $order->refund }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    {{-- Order Summary :: End --}}
</div>
