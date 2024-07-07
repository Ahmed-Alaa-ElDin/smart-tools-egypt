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
            @forelse ($transactions as $transaction)
                <tr>
                    <td class="px-6 py-2 text-center whitespace-nowrap">
                        <span class="text-sm">
                            {{ __('admin/ordersPages.' . App\Enums\PaymentMethod::getKeyFromValue($transaction->payment_method_id)) }}
                        </span>
                    </td>

                    <td class="px-6 py-2 text-center whitespace-nowrap" dir="ltr">
                        <span>
                            {{ number_format($transaction->payment_amount, 2, '.', '\'') }}
                        </span>
                    </td>

                    <td class="px-6 py-2 text-center whitespace-nowrap">
                        <span class="text-sm">
                            {{ __('admin/ordersPages.' . App\Enums\PaymentStatus::getKeyFromValue($transaction->payment_status_id)) }}
                        </span>
                    </td>
                    <td class="px-6 py-2 text-center whitespace-nowrap">
                        {{ $transaction->payment_details && json_decode($transaction->payment_details)->source_data_sub_type ? json_decode($transaction->payment_details)->source_data_sub_type : __('N/A') }}
                    </td>
                    <td class="px-6 py-2 text-center whitespace-nowrap">
                        {{ $transaction->service_provider_transaction_id ?? __('N/A') }}
                    </td>
                </tr>
            @empty
            @endforelse
        </tbody>
    </table>
</div>
