<div class="flex flex-col justify-center items-center gap-2 mt-2">
    <div class="h4 text-center font-bold text-red-800">
        {{ __('admin/ordersPages.Order Summary') }}
    </div>
    <div class="overflow-x-auto scrollbar scrollbar-thin scrollbar-thumb-red-100 border-b border-red-200 sm:rounded-lg">
        <table class="min-w-full divide-y divide-red-200">
            <thead class="bg-red-50">
                <tr>
                    <th
                        class="px-6 py-3 text-center text-xs font-bold text-red-700 uppercase tracking-wider select-none">
                        #
                    </th>
                    <th
                        class="px-6 py-3 text-center text-xs font-bold text-red-700 uppercase tracking-wider select-none">
                        {{ __('admin/ordersPages.Customer Info.') }}
                    </th>
                    <th
                        class="px-6 py-3 text-center text-xs font-bold text-red-700 uppercase tracking-wider select-none">
                        {{ __('admin/ordersPages.Num of Items') }}
                    </th>
                    <th
                        class="px-6 py-3 text-center text-xs font-bold text-red-700 uppercase tracking-wider select-none">
                        {{ __('admin/ordersPages.Total') }}
                    </th>
                    <th
                        class="px-6 py-3 text-center text-xs font-bold text-red-700 uppercase tracking-wider select-none">
                        {{ __('admin/ordersPages.Unpaid') }}
                    </th>
                    <th
                        class="px-6 py-3 text-center text-xs font-bold text-red-700 uppercase tracking-wider select-none">
                        {{ __('admin/ordersPages.Paid') }}
                    </th>
                    <th
                        class="px-6 py-3 text-center text-xs font-bold text-red-700 uppercase tracking-wider select-none">
                        {{ __('admin/ordersPages.Refund') }}
                    </th>
                    <th
                        class="px-6 py-3 text-center text-xs font-bold text-red-700 uppercase tracking-wider select-none">
                        {{ __('admin/ordersPages.Refunded') }}
                    </th>
                    <th
                        class="px-6 py-3 text-center text-xs font-bold text-red-700 uppercase tracking-wider select-none">
                        {{ __('admin/ordersPages.Payment Method') }}
                    </th>
                    <th
                        class="px-6 py-3 text-center text-xs font-bold text-red-700 uppercase tracking-wider select-none">
                        {{ __('admin/ordersPages.Allow to open package Short') }}
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-red-200">
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
                                {{ $order->user->phones->first()->phone }}
                            </span>
                        </div>
                    </td>
                    <td class="px-6 py-2 text-center whitespace-nowrap" dir="ltr">
                        {{ $order->num_of_items }}
                    </td>
                    <td class="px-6 py-2 text-center whitespace-nowrap" dir="ltr">
                        {{ formatTotal($order->invoice->total ?? 0,2) }}
                    </td>
                    <td class="px-6 py-2 text-center whitespace-nowrap" dir="ltr">
                        {{ formatTotal($order->invoice->unpaid ?? 0,2) }}
                    </td>
                    <td class="px-6 py-2 text-center whitespace-nowrap" dir="ltr">
                        {{ formatTotal($order->invoice->paid ?? 0,2) }}
                    </td>
                    <td class="px-6 py-2 text-center whitespace-nowrap" dir="ltr">
                        {{ formatTotal(abs($order->invoice->refundable ?? 0),2) }}
                    </td>
                    <td class="px-6 py-2 text-center whitespace-nowrap" dir="ltr">
                        {{ formatTotal(abs($order->invoice->refunded ?? 0),2) }}
                    </td>
                    <td class="px-6 py-2 text-center">
                        <div class="flex flex-wrap justify-center">
                            @foreach ($order->payment_methods->unique() as $payment_method)
                                <span class="text-sm">
                                    {{ __('admin/ordersPages.' . App\Enums\PaymentMethod::getKeyFromValue($payment_method)) }}
                                    @if (!$loop->last)
                                        ,
                                    @endif
                                </span>
                            @endforeach
                        </div>
                    </td>
                    <td class="px-6 py-2 text-center whitespace-nowrap">
                        {{ $order->allow_to_open ? __('admin/ordersPages.Yes') : __('admin/ordersPages.No') }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
