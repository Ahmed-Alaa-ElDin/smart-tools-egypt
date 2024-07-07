<div>
    {{-- Order Summary :: Start --}}
    <div>
        <x-admin.orders.order-summary :order="$order" />
    </div>
    {{-- Order Summary :: End --}}

    <hr class="my-2">

    <div class="flex items-center justify-around">
        {{-- Add Payment Button --}}
        <button wire:click="addPayment"
            class="btn btn-sm py-2 px-3 flex gap-2 items-center justify-center text-xs font-bold text-white bg-green-500 hover:bg-green-700 rounded">
            <span>
                {{ __('admin/ordersPages.Add Payment') }}
            </span>
            <span class="material-icons">
                payments
            </span>
        </button>

        {{-- Add Refund Button --}}
        <button wire:click="addRefund"
            class="btn btn-sm py-2 px-3 flex gap-2 items-center justify-center text-xs font-bold text-white bg-yellow-500 hover:bg-yellow-700 rounded">
            <span>
                {{ __('admin/ordersPages.Add Refund') }}
            </span>
            <span class="material-icons">
                <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" preserveAspectRatio="xMidYMid meet"
                    viewBox="0 0 24 24">
                    <path fill="currentColor"
                        d="M13.91 2.91L11.83 5H14a8 8 0 0 1 8 8h-2a6 6 0 0 0-6-6h-2.17l2.09 2.09l-1.42 1.41L8 6l1.41-1.41L12.5 1.5l1.41 1.41zM2 12v10h16V12H2zm2 6.56v-3.11A4 4 0 0 0 5.45 14h9.1A4 4 0 0 0 16 15.45v3.11A3.996 3.996 0 0 0 14.57 20H5.45A3.996 3.996 0 0 0 4 18.56zm6 .44c.828 0 1.5-.895 1.5-2s-.672-2-1.5-2s-1.5.895-1.5 2s.672 2 1.5 2z" />
                </svg>
            </span>
        </button>

        {{-- Add Delivery Button --}}
        @if ($order->order_delivery_id == null)
            <button wire:click="createDelivery"
                class="btn btn-sm py-2 px-3 flex gap-2 items-center justify-center text-xs font-bold text-white bg-secondary hover:secondaryDark rounded">
                <span>
                    {{ __('admin/ordersPages.Create Delivery') }}
                </span>
                <span class="material-icons">
                    local_shipping
                </span>
            </button>
        @endif


    </div>

    <hr class="my-2">

    {{-- Payment History :: Start --}}
    <div
        class="overflow-x-auto scrollbar scrollbar-thin scrollbar-thumb-gray-100 border-b border-gray-200 sm:rounded-lg">
        <div class="h4 text-center font-bold my-2 text-gray-800">
            {{ __('admin/ordersPages.Payment Transactions') }}
        </div>
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th
                        class="px-6 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider select-none">
                        {{ __('admin/ordersPages.Method') }}</th>
                    <th
                        class="px-6 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider select-none">
                        {{ __('admin/ordersPages.Amount') }}</th>
                    <th
                        class="px-6 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider select-none">
                        {{ __('admin/ordersPages.Status') }}</th>
                    <th
                        class="px-6 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider select-none">
                        {{ __('admin/ordersPages.By') }}</th>
                    <th
                        class="px-6 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider select-none">
                        {{ __('admin/ordersPages.Transaction') }}</th>
                    <th
                        class="px-6 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider select-none">
                        {{ __('admin/ordersPages.Last Update') }}</th>
                    <th
                        class="px-6 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider select-none">
                        {{ __('admin/ordersPages.Manage') }}</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($order->transactions as $transaction)
                    <tr>
                        <td class="px-6 py-2 text-center whitespace-nowrap">
                            <span class="text-sm">
                                {{ __('admin/ordersPages.' . App\Enums\PaymentMethod::getKeyFromValue($transaction->payment_method_id)) }}
                            </span>
                        </td>

                        <td class="px-6 py-2 text-center whitespace-nowrap
                            @if ($transaction->payment_amount > 0) text-green-700 @else text-red-700 @endif
                        "
                            dir="ltr">
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

                        <td class="px-6 py-2 text-center whitespace-nowrap">
                            <span class="text-sm">
                                {{ $transaction->updated_at }}
                            </span>
                        </td>

                        <td class="px-6 py-2 text-center whitespace-nowrap">
                            @if ($transaction->deleted_at == null)
                                {{-- Mark as Paid --}}
                                @if ($transaction->payment_amount >= 0 && $transaction->payment_status_id == App\Enums\PaymentStatus::Pending->value)
                                    <button title="{{ __('admin/ordersPages.Confirm Payment') }}"
                                        class="focus:outline-none"
                                        wire:click.prevent="paymentConfirm({{ $transaction->id }},{{ $transaction->payment_amount }})"
                                        class="m-0">
                                        <span
                                            class="material-icons p-1 text-lg w-9 h-9 text-white bg-green-500 hover:bg-green-700 rounded">
                                            check
                                        </span>
                                    </button>
                                @endif

                                {{-- Mark as Refunded --}}
                                @if ($transaction->payment_amount <= 0 && $transaction->payment_status_id == App\Enums\PaymentStatus::Refundable->value)
                                    <button title="{{ __('admin/ordersPages.Confirm Refund') }}"
                                        class="focus:outline-none"
                                        wire:click.prevent="refundConfirm({{ $transaction->id }})" class="m-0">
                                        <span
                                            class="material-icons flex justify-center items-center text-lg w-9 h-9 text-white bg-yellow-500 hover:bg-yellow-700 rounded">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em"
                                                preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24">
                                                <path fill="currentColor"
                                                    d="M13.91 2.91L11.83 5H14a8 8 0 0 1 8 8h-2a6 6 0 0 0-6-6h-2.17l2.09 2.09l-1.42 1.41L8 6l1.41-1.41L12.5 1.5l1.41 1.41zM2 12v10h16V12H2zm2 6.56v-3.11A4 4 0 0 0 5.45 14h9.1A4 4 0 0 0 16 15.45v3.11A3.996 3.996 0 0 0 14.57 20H5.45A3.996 3.996 0 0 0 4 18.56zm6 .44c.828 0 1.5-.895 1.5-2s-.672-2-1.5-2s-1.5.895-1.5 2s.672 2 1.5 2z" />
                                            </svg>
                                        </span>
                                    </button>
                                @endif

                                {{-- Remove Transaction --}}
                                @if (
                                    !in_array($transaction->payment_status_id, [
                                        App\Enums\PaymentStatus::Paid->value,
                                        App\Enums\PaymentStatus::Refunded->value,
                                    ]))
                                    <button title="{{ __('admin/ordersPages.Remove Transaction') }}"
                                        class="focus:outline-none"
                                        wire:click.prevent="removeTransactionConfirm({{ $transaction->id }})"
                                        class="m-0">
                                        <span
                                            class="material-icons p-1 text-lg w-9 h-9 text-white bg-red-500 hover:bg-red-700 rounded">
                                            delete
                                        </span>
                                    </button>
                                @endif
                            @else
                                <span class="text-primary">
                                    {{ __('admin/ordersPages.Removed') }}
                                </span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    {{-- Payment History :: End --}}
</div>

@push('js')
    <script>
        // #### Get Payment Data####
        window.addEventListener('swalGetPaymentData', function(e) {
            Swal.fire({
                title: e.detail.title,
                html: e.detail.html,
                showDenyButton: true,
                confirmButtonText: e.detail.confirmButtonText,
                denyButtonText: e.detail.denyButtonText,
                denyButtonColor: e.detail.denyButtonColor,
                confirmButtonColor: e.detail.confirmButtonColor,
                preConfirm: () => {
                    return [
                        document.getElementById('amount').value,
                        document.getElementById('transaction_id').value
                    ]
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.dispatch(e.detail.method, {
                        id: e.detail.id,
                        payment_amount: result.value[0],
                        transaction_id: result.value[1]
                    })
                }
            });
        });
        // #### Get Payment Data ####

        // #### Get Refund Data ####
        window.addEventListener('swalGetRefundData', function(e) {
            console.log(e);

            Swal.fire({
                title: e.detail.title,
                html: e.detail.html,
                showDenyButton: true,
                confirmButtonText: e.detail.confirmButtonText,
                denyButtonText: e.detail.denyButtonText,
                denyButtonColor: e.detail.denyButtonColor,
                confirmButtonColor: e.detail.confirmButtonColor,
                preConfirm: () => {
                    return [
                        document.getElementById('amount').value,
                        document.getElementById('transaction_id').value,
                        document.querySelector('input[name="type"]:checked').value
                    ];
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    console.log(e);
                    Livewire.dispatch(e.detail.method, {
                        id: e.detail.id,
                        payment_amount: result.value[0],
                        transaction_id: result.value[1],
                        type: result.value[2]
                    });
                }
            });
        });
        // #### Get Refund Data ####

        // #### Get Add Payment Data ####
        window.addEventListener('swalGetAddPaymentData', function(e) {
            Swal.fire({
                title: e.detail.title,
                html: e.detail.html,
                showDenyButton: true,
                confirmButtonText: e.detail.confirmButtonText,
                denyButtonText: e.detail.denyButtonText,
                denyButtonColor: e.detail.denyButtonColor,
                confirmButtonColor: e.detail.confirmButtonColor,
                preConfirm: () => {
                    return [
                        document.getElementById('amount').value,
                        document.getElementById('transaction_id').value
                    ]
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.dispatch(e.detail.method, {
                        id: e.detail.id,
                        payment_amount: result.value[0],
                        transaction_id: result.value[1]
                    });
                }
            });
        });
        // #### Get Add Payment Data ####

        // #### Get New Payment Data####
        window.addEventListener('swalGetNewPaymentData', function(e) {
            Swal.fire({
                title: e.detail.title,
                html: e.detail.html,
                showDenyButton: true,
                confirmButtonText: e.detail.confirmButtonText,
                denyButtonText: e.detail.denyButtonText,
                denyButtonColor: e.detail.denyButtonColor,
                confirmButtonColor: e.detail.confirmButtonColor,
                preConfirm: () => {
                    return [
                        document.getElementById('amount').value,
                        document.querySelector('input[name="type"]:checked').value
                    ]
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.dispatch(e.detail.method, {
                        payment_amount: result.value[0],
                        type: result.value[1]
                    });
                }
            });
        });
        // #### Get New Payment Data ####

        // #### Get New Payment Data####
        window.addEventListener('swalGetNewRefundData', function(e) {
            Swal.fire({
                title: e.detail.title,
                html: e.detail.html,
                showDenyButton: true,
                confirmButtonText: e.detail.confirmButtonText,
                denyButtonText: e.detail.denyButtonText,
                denyButtonColor: e.detail.denyButtonColor,
                confirmButtonColor: e.detail.confirmButtonColor,
                preConfirm: () => {
                    return [
                        document.getElementById('amount').value,
                        document.getElementById('transaction_id').value,
                        document.querySelector('input[name="type"]:checked').value
                    ]
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.dispatch(e.detail.method, {
                        payment_amount: result.value[0],
                        transaction_id: result.value[1],
                        type: result.value[2]
                    });
                }
            });
        });
        // #### Get New Payment Data ####
    </script>
@endpush
