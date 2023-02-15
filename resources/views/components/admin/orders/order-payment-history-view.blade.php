<div class="overflow-x-auto scrollbar scrollbar-thin scrollbar-thumb-gray-100 border-b border-gray-200 sm:rounded-lg">
    <div class="h4 text-center font-bold my-2 text-gray-800">
        {{ __('admin/ordersPages.Payment Transactions') }}
    </div>
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider select-none">
                    {{ __('admin/ordersPages.Method') }}
                </th>
                <th class="px-6 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider select-none">
                    {{ __('admin/ordersPages.Amount') }}
                </th>
                <th class="px-6 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider select-none">
                    {{ __('admin/ordersPages.Status') }}
                </th>
                <th class="px-6 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider select-none">
                    {{ __('admin/ordersPages.By') }}
                </th>
                <th class="px-6 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider select-none">
                    {{ __('admin/ordersPages.Transaction') }}
                </th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse ($payments as $payment)
                <tr>
                    <td class="px-6 py-2 text-center whitespace-nowrap">
                        <span class="text-sm">
                            {{ $payment->payment_method == 1
                                ? __('admin/ordersPages.Cash on delivery (COD)')
                                : ($payment->payment_method == 2
                                    ? __('admin/ordersPages.Credit / Debit Card')
                                    : ($payment->payment_method == 3
                                        ? __('admin/ordersPages.Installment')
                                        : ($payment->payment_method == 4
                                            ? __('admin/ordersPages.Vodafone Cash')
                                            : ($payment->payment_method == 10
                                                ? __('admin/ordersPages.Wallet')
                                                : '')))) }}
                        </span>
                    </td>

                    <td class="px-6 py-2 text-center whitespace-nowrap" dir="ltr">
                        <span>
                            {{ number_format($payment->payment_amount, 2, '.', '\'') }}
                        </span>
                    </td>

                    <td class="px-6 py-2 text-center whitespace-nowrap">
                        <span class="text-sm">
                            {{ $payment->payment_status == 1
                                ? __('admin/ordersPages.Pended')
                                : ($payment->payment_status == 2
                                    ? __('admin/ordersPages.Paid')
                                    : ($payment->payment_status == 3
                                        ? __('admin/ordersPages.Failed')
                                        : ($payment->payment_status == 4
                                            ? __('admin/ordersPages.Refunded')
                                            : ($payment->payment_status == 5
                                                ? __('admin/ordersPages.Refund Requested')
                                                : '')))) }}
                        </span>
                    </td>
                    <td class="px-6 py-2 text-center whitespace-nowrap">
                        {{ $payment->payment_details && json_decode($payment->payment_details)->source_data_sub_type ? json_decode($payment->payment_details)->source_data_sub_type : __('N/A') }}
                    </td>
                    <td class="px-6 py-2 text-center whitespace-nowrap">
                        {{ $payment->payment_details && json_decode($payment->payment_details)->transaction_id ? json_decode($payment->payment_details)->transaction_id : __('N/A') }}
                    </td>
                </tr>
            @empty
            @endforelse
        </tbody>
    </table>
</div>
