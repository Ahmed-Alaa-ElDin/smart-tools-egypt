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
        <button wire:click="createEditDelivery"
            class="btn btn-sm py-2 px-3 flex gap-2 items-center justify-center text-xs font-bold text-white bg-secondary hover:secondaryDark rounded">
            <span>
                {{ __('admin/ordersPages.Create/Update Delivery') }}
            </span>
            <span class="material-icons">
                local_shipping
            </span>
        </button>
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
                        {{ __('admin/ordersPages.Manage') }}</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($order->payments as $payment)
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
                        <td class="px-6 py-2 text-center whitespace-nowrap">
                            {{-- Mark as Paid --}}
                            @if ($payment->payment_amount >= 0 && $payment->payment_status == 1)
                                <button title="{{ __('admin/ordersPages.Paid') }}" class="focus:outline-none"
                                    wire:click.prevent="paymentConfirm({{ $payment->id }},{{ $payment->payment_amount }})"
                                    class="m-0">
                                    <span
                                        class="material-icons p-1 text-lg w-9 h-9 text-white bg-green-500 hover:bg-green-700 rounded">
                                        check
                                    </span>
                                </button>
                            @endif

                            {{-- Mark as Refunded --}}
                            @if ($payment->payment_amount <= 0 && $payment->payment_status == 5)
                                <button title="{{ __('admin/ordersPages.Refund') }}" class="focus:outline-none"
                                    wire:click.prevent="refundDestination({{ $payment->id }})" class="m-0">
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
                // console.log(result);
                if (result.isConfirmed) {
                    Livewire.dispatch(e.detail.method, e.detail.id, result.value);
                }
            });
        });
        // #### Get Payment Data ####

        // #### Get Refund Data ####
        window.addEventListener('swalGetRefundData', function(e) {
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
                // console.log(result);
                if (result.isConfirmed) {
                    Livewire.dispatch(e.detail.method, e.detail.id, result.value);
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
                // console.log(result);
                if (result.isConfirmed) {
                    Livewire.dispatch(e.detail.method, e.detail.id, result.value);
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
                    Livewire.dispatch(e.detail.method, result.value);
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
                    Livewire.dispatch(e.detail.method, result.value);
                }
            });
        });
        // #### Get New Payment Data ####
    </script>
@endpush
