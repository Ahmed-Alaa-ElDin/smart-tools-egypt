<div>
    {{-- Hero Header --}}
    <div class="relative overflow-hidden rounded-2xl mb-6 shadow-lg"
        style="background: linear-gradient(135deg, #1e293b 0%, #334155 50%, #475569 100%);">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute -top-10 end-[-2.5rem] w-40 h-40 rounded-full"
                style="background: radial-gradient(circle, rgba(255,255,255,0.3) 0%, transparent 70%);"></div>
            <div class="absolute -bottom-10 start-[-2.5rem] w-56 h-56 rounded-full"
                style="background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, transparent 70%);"></div>
        </div>
        <div class="relative px-6 py-6">
            {{-- Breadcrumb --}}
            <nav aria-label="breadcrumb" role="navigation" class="mb-4">
                <ol class="flex items-center gap-2 text-sm m-0 p-0" style="list-style: none;">
                    <li>
                        <a href="{{ route('admin.dashboard') }}"
                            class="text-gray-300 hover:text-white transition-colors duration-200 flex items-center gap-1">
                            <span class="material-icons text-sm">dashboard</span>
                            {{ __('admin/ordersPages.Dashboard') }}
                        </a>
                    </li>
                    <li class="text-gray-500">
                        <span class="material-icons text-xs rtl:rotate-180">chevron_right</span>
                    </li>
                    <li>
                        <a href="{{ route('admin.orders.index') }}"
                            class="text-gray-300 hover:text-white transition-colors duration-200 flex items-center gap-1">
                            <span class="material-icons text-sm">shopping_cart</span>
                            {{ __('admin/ordersPages.All Orders') }}
                        </a>
                    </li>
                    <li class="text-gray-500">
                        <span class="material-icons text-xs rtl:rotate-180">chevron_right</span>
                    </li>
                    <li class="text-white font-semibold">
                        {{ __("admin/ordersPages.Order's Details") }} #{{ $order->id }}
                    </li>
                </ol>
            </nav>

            <div class="flex flex-wrap justify-between items-center gap-4">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-white/10 rounded-2xl backdrop-blur-md">
                        <span class="material-icons text-4xl text-white">receipt_long</span>
                    </div>
                    <div>
                        <h1 class="text-2xl font-black text-white m-0">
                            {{ __('admin/ordersPages.Order') }} #{{ $order->id }}
                        </h1>
                        <p class="text-gray-300 text-xs font-bold mt-1 uppercase tracking-widest">
                            {{ $order->created_at->translatedFormat('l, d F Y - h:i A') }}
                        </p>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="flex items-center gap-2">
                    @if (!$order->trashed() && $order->isEditable())
                        <a href="{{ route('admin.orders.edit', $order->id) }}"
                            class="flex items-center gap-2 px-4 py-2 bg-yellow-500 hover:bg-yellow-400 text-slate-900 font-black rounded-xl transition-all duration-200 shadow-lg hover:shadow-yellow-500/20">
                            <span class="material-icons text-lg">edit</span>
                            {{ __("admin/ordersPages.Edit Order's Details") }}
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Summary Cards Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6 text-start">
        {{-- Total Amount --}}
        <div
            class="group relative bg-white rounded-2xl p-4 border border-emerald-100 shadow-sm hover:shadow-emerald-500/10 transition-all duration-300 overflow-hidden">
            <div
                class="absolute top-0 end-0 w-24 h-24 bg-emerald-500/5 rounded-full -me-8 -mt-8 transition-transform group-hover:scale-110">
            </div>
            <div class="flex items-center justify-between mb-3">
                <span
                    class="text-[10px] font-black text-emerald-600/60 uppercase tracking-widest">{{ __('admin/ordersPages.Total Amount') }}</span>
                <div class="p-2.5 bg-emerald-500 text-white rounded-xl shadow-lg shadow-emerald-500/20">
                    <span class="material-icons text-lg">payments</span>
                </div>
            </div>
            <div class="text-2xl font-black text-slate-800 text-start flex items-baseline gap-1">
                <span dir="ltr">{{ formatTotal($order->invoice->total ?? 0, 2) }}</span>
                <small class="text-xs font-bold text-slate-400">
                    {{ __('admin/productsPages. EGP') }}
                </small>
            </div>
            <div class="mt-2 text-start">
                @php
                    $unpaid = $order->invoice->unpaid;
                    $paid = $order->invoice->paid;

                    if ($unpaid == 0) {
                        $badgeStyle = 'bg-emerald-50 text-emerald-600 border border-emerald-100';
                        $dotStyle = 'bg-emerald-600';
                        $label = __('admin/ordersPages.Fully Paid');
                    } elseif ($paid > 0) {
                        $badgeStyle = 'bg-amber-50 text-amber-600 border border-amber-100';
                        $dotStyle = 'bg-amber-600';
                        $label = __('admin/ordersPages.Partially Paid');
                    } else {
                        $badgeStyle = 'bg-rose-50 text-rose-600 border border-rose-100';
                        $dotStyle = 'bg-rose-600';
                        $label = __('admin/ordersPages.Pending Payment');
                    }
                @endphp
                <span
                    class="inline-flex items-center gap-1.5 text-[10px] font-black py-1 px-2.5 rounded-full {{ $badgeStyle }}">
                    <span class="w-1.5 h-1.5 rounded-full {{ $dotStyle }} animate-pulse"></span>
                    {{ $label }}
                </span>
            </div>
        </div>

        {{-- Status Card --}}
        @php
            $statusId = $order->status_id;
            $statusConfig = match (true) {
                in_array($statusId, [1, 2, 14, 15, 16]) => [
                    'bg' => 'bg-amber-500',
                    'light' => 'bg-amber-500/5',
                    'border' => 'border-amber-100',
                    'text' => 'text-amber-600/60',
                    'shadow' => 'shadow-amber-500/20',
                    'hover' => 'hover:shadow-amber-500/10',
                    'icon' => 'pending_actions',
                ],
                in_array($statusId, [3, 45, 12]) => [
                    'bg' => 'bg-emerald-500',
                    'light' => 'bg-emerald-500/5',
                    'border' => 'border-emerald-100',
                    'text' => 'text-emerald-600/60',
                    'shadow' => 'shadow-emerald-500/20',
                    'hover' => 'hover:shadow-emerald-500/10',
                    'icon' => 'check_circle',
                ],
                in_array($statusId, [4, 5, 6]) => [
                    'bg' => 'bg-blue-500',
                    'light' => 'bg-blue-500/5',
                    'border' => 'border-blue-100',
                    'text' => 'text-blue-600/60',
                    'shadow' => 'shadow-blue-500/20',
                    'hover' => 'hover:shadow-blue-500/10',
                    'icon' => 'local_shipping',
                ],
                in_array($statusId, [8, 9, 13]) => [
                    'bg' => 'bg-rose-500',
                    'light' => 'bg-rose-500/5',
                    'border' => 'border-rose-100',
                    'text' => 'text-rose-600/60',
                    'shadow' => 'shadow-rose-500/20',
                    'hover' => 'hover:shadow-rose-500/10',
                    'icon' => 'cancel',
                ],
                default => [
                    'bg' => 'bg-slate-500',
                    'light' => 'bg-slate-500/5',
                    'border' => 'border-slate-100',
                    'text' => 'text-slate-600/60',
                    'shadow' => 'shadow-slate-500/20',
                    'hover' => 'hover:shadow-slate-500/10',
                    'icon' => 'help_outline',
                ],
            };
        @endphp
        <div
            class="group relative bg-white rounded-2xl p-4 border {{ $statusConfig['border'] }} shadow-sm {{ $statusConfig['hover'] }} transition-all duration-300 overflow-hidden">
            <div
                class="absolute top-0 end-0 w-24 h-24 {{ $statusConfig['light'] }} rounded-full -me-8 -mt-8 transition-transform group-hover:scale-110">
            </div>
            <div class="flex items-center justify-between mb-3">
                <span
                    class="text-[10px] font-black {{ $statusConfig['text'] }} uppercase tracking-widest">{{ __('admin/ordersPages.Current Status') }}</span>
                <div
                    class="p-2.5 {{ $statusConfig['bg'] }} text-white rounded-xl shadow-lg {{ $statusConfig['shadow'] }}">
                    <span class="material-icons text-lg">{{ $statusConfig['icon'] }}</span>
                </div>
            </div>
            <div class="text-xl font-black text-slate-800">
                {{ $order->status?->name ?? __('admin/ordersPages.N/A') }}
            </div>
            <div class="mt-2 flex items-center gap-1 text-[10px] font-bold text-slate-400">
                <span class="material-icons text-[12px]">update</span>
                {{ __('admin/ordersPages.Last update') }}:
                {{ $order->statuses->last()?->pivot->created_at->diffForHumans() ?? $order->updated_at->diffForHumans() }}
            </div>
        </div>

        {{-- Customer Card --}}
        <div
            class="group relative bg-white rounded-2xl p-4 border border-purple-100 shadow-sm hover:shadow-purple-500/10 transition-all duration-300 overflow-hidden">
            <div
                class="absolute top-0 end-0 w-24 h-24 bg-purple-500/5 rounded-full -me-8 -mt-8 transition-transform group-hover:scale-110">
            </div>
            <div class="flex items-center justify-between mb-3">
                <span
                    class="text-[10px] font-black text-purple-600/60 uppercase tracking-widest">{{ __('admin/ordersPages.Customer') }}</span>
                <div class="p-2.5 bg-purple-500 text-white rounded-xl shadow-lg shadow-purple-500/20">
                    <span class="material-icons text-lg">person</span>
                </div>
            </div>
            <div class="text-xl font-black text-slate-800 truncate">
                {{ $order->user->f_name }} {{ $order->user->l_name }}
            </div>
            <div class="mt-2 flex items-center gap-1 text-[10px] font-bold text-slate-400">
                <span class="material-icons text-[12px]">mail</span>
                {{ $order->user->email }}
            </div>
        </div>

        {{-- Payment Card --}}
        <div
            class="group relative bg-white rounded-2xl p-4 border border-orange-100 shadow-sm hover:shadow-orange-500/10 transition-all duration-300 overflow-hidden">
            <div
                class="absolute top-0 end-0 w-24 h-24 bg-orange-500/5 rounded-full -me-8 -mt-8 transition-transform group-hover:scale-110">
            </div>
            <div class="flex items-center justify-between mb-3">
                <span
                    class="text-[10px] font-black text-orange-600/60 uppercase tracking-widest">{{ __('admin/ordersPages.Payment Method') }}</span>
                <div class="p-2.5 bg-orange-500 text-white rounded-xl shadow-lg shadow-orange-500/20">
                    <span class="material-icons text-lg">credit_card</span>
                </div>
            </div>
            <div class="text-xl font-black text-slate-800">
                @foreach ($order->payment_methods->unique() as $pm)
                    {{ __('admin/ordersPages.' . App\Enums\PaymentMethod::getKeyFromValue($pm)) }}{{ !$loop->last ? ' & ' : '' }}
                @endforeach
            </div>
            <div class="mt-2 flex items-center gap-1 text-[10px] font-bold text-slate-400">
                <span class="material-icons text-[12px]">shopping_basket</span>
                {{ $order->num_of_items }} {{ __('admin/ordersPages.Items') }}
            </div>
        </div>
    </div>

    {{-- Transaction Actions --}}
    <div class="flex flex-wrap items-center justify-center gap-4 mb-6">
        {{-- Add Payment Button --}}
        <button wire:click="addPayment"
            class="group flex items-center gap-3 px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-black rounded-2xl transition-all duration-300 shadow-lg shadow-emerald-600/20 hover:scale-[1.02]">
            <span class="material-icons group-hover:rotate-12 transition-transform">add_card</span>
            {{ __('admin/ordersPages.Add Payment') }}
        </button>

        {{-- Add Refund Button --}}
        <button wire:click="addRefund"
            class="group flex items-center gap-3 px-6 py-3 bg-amber-500 hover:bg-amber-600 text-white font-black rounded-2xl transition-all duration-300 shadow-lg shadow-amber-500/20 hover:scale-[1.02]">
            <span class="material-icons group-hover:-rotate-12 transition-transform">assignment_return</span>
            {{ __('admin/ordersPages.Add Refund') }}
        </button>

        {{-- Add Delivery Button --}}
        @if ($order->order_delivery_id == null)
            <button wire:click="createDelivery"
                class="group flex items-center gap-3 px-6 py-3 bg-slate-800 hover:bg-slate-900 text-white font-black rounded-2xl transition-all duration-300 shadow-lg shadow-slate-800/20 hover:scale-[1.02]">
                <span class="material-icons group-hover:translate-x-1 transition-transform">local_shipping</span>
                {{ __('admin/ordersPages.Create Delivery') }}
            </button>
        @endif
    </div>

    {{-- Content Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main Column --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Products List --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div
                    class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gradient-to-r rtl:bg-gradient-to-l from-red-50 to-white">
                    <div class="flex items-center gap-3 font-black text-slate-800">
                        <div
                            class="w-10 h-10 bg-red-600 text-white rounded-xl flex items-center justify-center shadow-lg shadow-red-600/20">
                            <span class="material-icons">inventory_2</span>
                        </div>
                        {{ __('admin/ordersPages.Order Items') }}
                    </div>
                    <span
                        class="text-[10px] font-black bg-slate-900 text-white px-3 py-1 rounded-full uppercase tracking-widest shadow-sm">
                        {{ $order->num_of_items }} {{ __('admin/ordersPages.Items') }}
                    </span>
                </div>
                <div class="p-6">
                    <x-admin.orders.order-products-list :items="$order->items" />
                </div>
            </div>

            {{-- Transactions History --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div
                    class="px-6 py-4 border-b border-gray-100 flex items-center gap-3 font-black text-slate-800 bg-gradient-to-r rtl:bg-gradient-to-l from-emerald-50 to-white">
                    <div
                        class="w-10 h-10 bg-emerald-600 text-white rounded-xl flex items-center justify-center shadow-lg shadow-emerald-600/20">
                        <span class="material-icons">history</span>
                    </div>
                    {{ __('admin/ordersPages.Payment Transactions') }}
                </div>
                <div class="p-0 overflow-x-auto scrollbar scrollbar-thin scrollbar-thumb-gray-100">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-slate-50">
                            <tr>
                                <th
                                    class="px-6 py-4 text-center text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                    {{ __('admin/ordersPages.Method') }}
                                </th>
                                <th
                                    class="px-6 py-4 text-center text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                    {{ __('admin/ordersPages.Amount') }}
                                </th>
                                <th
                                    class="px-6 py-4 text-center text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                    {{ __('admin/ordersPages.Status') }}
                                </th>
                                <th
                                    class="px-6 py-4 text-center text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                    {{ __('admin/ordersPages.By') }}
                                </th>
                                <th
                                    class="px-6 py-4 text-center text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                    {{ __('admin/ordersPages.Transaction') }}
                                </th>
                                <th
                                    class="px-6 py-4 text-center text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                    {{ __('admin/ordersPages.Last Update') }}
                                </th>
                                <th
                                    class="px-6 py-4 text-center text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                    {{ __('admin/ordersPages.Manage') }}
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-50 text-start">
                            @foreach ($order->transactions as $transaction)
                                <tr class="hover:bg-slate-50/50 transition-colors duration-200">
                                    <td class="px-6 py-4 text-center whitespace-nowrap">
                                        <div
                                            class="inline-flex items-center gap-1.5 px-2 py-1 rounded-lg bg-slate-100 text-slate-700 text-xs font-bold">
                                            <span class="material-icons text-sm text-slate-400">payments</span>
                                            {{ __('admin/ordersPages.' . App\Enums\PaymentMethod::getKeyFromValue($transaction->payment_method_id)) }}
                                        </div>
                                    </td>

                                    <td class="px-6 py-4 text-center whitespace-nowrap">
                                        <span
                                            class="text-sm font-black {{ $transaction->payment_amount > 0 ? 'text-emerald-600' : 'text-rose-600' }} flex items-baseline justify-center gap-1">
                                            <span dir="ltr">{{ formatTotal($transaction->payment_amount, 2) }}</span>
                                            <small class="text-[10px] font-bold text-slate-400">{{ __('admin/productsPages. EGP') }}</small>
                                        </span>
                                    </td>

                                    <td class="px-6 py-4 text-center whitespace-nowrap">
                                        @php
                                            $statusKey = App\Enums\PaymentStatus::getKeyFromValue(
                                                $transaction->payment_status_id,
                                            );
                                            $statusColor = match ($transaction->payment_status_id) {
                                                App\Enums\PaymentStatus::Paid->value
                                                    => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                                App\Enums\PaymentStatus::Pending->value
                                                    => 'bg-amber-50 text-amber-600 border-amber-100',
                                                App\Enums\PaymentStatus::Failed->value
                                                    => 'bg-rose-50 text-rose-600 border-rose-100',
                                                App\Enums\PaymentStatus::Refunded->value
                                                    => 'bg-blue-50 text-blue-600 border-blue-100',
                                                default => 'bg-slate-50 text-slate-600 border-slate-100',
                                            };
                                        @endphp
                                        <span
                                            class="text-[10px] font-black px-2.5 py-1 rounded-full border {{ $statusColor }} uppercase tracking-wider">
                                            {{ __('admin/ordersPages.' . $statusKey) }}
                                        </span>
                                    </td>

                                    <td class="px-6 py-4 text-center whitespace-nowrap">
                                        <div class="flex flex-col items-center">
                                            <span
                                                class="text-xs font-bold text-slate-700">{{ $transaction->payment_details && json_decode($transaction->payment_details)->source_data_sub_type ? json_decode($transaction->payment_details)->source_data_sub_type : __('N/A') }}</span>
                                            <span class="text-[9px] font-medium text-slate-400">ID:
                                                {{ $transaction->id }}</span>
                                        </div>
                                    </td>

                                    <td class="px-6 py-4 text-center whitespace-nowrap">
                                        <span
                                            class="text-[11px] font-mono font-bold text-slate-400 bg-slate-50 px-2 py-1 rounded border border-slate-100">
                                            {{ $transaction->service_provider_transaction_id ?? __('N/A') }}
                                        </span>
                                    </td>

                                    <td class="px-6 py-4 text-center whitespace-nowrap">
                                        <span class="text-[10px] font-bold text-slate-400">
                                            {{ $transaction->updated_at->format('d M Y') }}<br>
                                            {{ $transaction->updated_at->format('H:i A') }}
                                        </span>
                                    </td>

                                    <td class="px-6 py-4 text-center whitespace-nowrap">
                                        <div class="flex items-center justify-center gap-1">
                                            @if ($transaction->deleted_at == null)
                                                {{-- Mark as Paid --}}
                                                @if ($transaction->payment_amount >= 0 && $transaction->payment_status_id == App\Enums\PaymentStatus::Pending->value)
                                                    <button title="{{ __('admin/ordersPages.Confirm Payment') }}"
                                                        wire:click.prevent="paymentConfirm({{ $transaction->id }},{{ $transaction->payment_amount }})"
                                                        class="w-8 h-8 flex items-center justify-center bg-emerald-50 text-emerald-600 hover:bg-emerald-600 hover:text-white rounded-lg transition-all duration-200">
                                                        <span class="material-icons text-lg">check</span>
                                                    </button>
                                                @endif

                                                {{-- Mark as Refunded --}}
                                                @if ($transaction->payment_amount <= 0 && $transaction->payment_status_id == App\Enums\PaymentStatus::Refundable->value)
                                                    <button title="{{ __('admin/ordersPages.Confirm Refund') }}"
                                                        wire:click.prevent="refundConfirm({{ $transaction->id }})"
                                                        class="w-8 h-8 flex items-center justify-center bg-amber-50 text-amber-600 hover:bg-amber-600 hover:text-white rounded-lg transition-all duration-200">
                                                        <span class="material-icons text-lg">assignment_return</span>
                                                    </button>
                                                @endif

                                                {{-- Remove Transaction --}}
                                                @if (
                                                    !in_array($transaction->payment_status_id, [
                                                        App\Enums\PaymentStatus::Paid->value,
                                                        App\Enums\PaymentStatus::Refunded->value,
                                                    ]))
                                                    <button title="{{ __('admin/ordersPages.Remove Transaction') }}"
                                                        wire:click.prevent="removeTransactionConfirm({{ $transaction->id }})"
                                                        class="w-8 h-8 flex items-center justify-center bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white rounded-lg transition-all duration-200">
                                                        <span class="material-icons text-lg">delete</span>
                                                    </button>
                                                @endif
                                            @else
                                                <span
                                                    class="text-[10px] font-black text-rose-500 uppercase tracking-widest">
                                                    {{ __('admin/ordersPages.Removed') }}
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Sidebar Column --}}
        <div class="space-y-6">
            {{-- Tracking Timeline --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div
                    class="px-6 py-4 border-b border-gray-100 flex items-center gap-3 font-black text-slate-800 bg-gradient-to-r rtl:bg-gradient-to-l from-blue-50 to-white">
                    <div
                        class="w-10 h-10 bg-blue-600 text-white rounded-xl flex items-center justify-center shadow-lg shadow-blue-600/20">
                        <span class="material-icons">route</span>
                    </div>
                    {{ __('admin/ordersPages.Track Order') }}
                </div>
                <div class="p-6">
                    <x-admin.orders.order-track-view :statuses="$order->statuses" />
                </div>
            </div>

            {{-- Customer Info Details --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden text-start">
                <div
                    class="px-6 py-4 border-b border-gray-100 flex items-center gap-3 font-black text-slate-800 bg-gradient-to-r rtl:bg-gradient-to-l from-purple-50 to-white">
                    <div
                        class="w-10 h-10 bg-purple-600 text-white rounded-xl flex items-center justify-center shadow-lg shadow-purple-600/20">
                        <span class="material-icons">contact_page</span>
                    </div>
                    {{ __('admin/ordersPages.Delivery Information') }}
                </div>
                <div class="p-6 space-y-4">
                    {{-- Phone --}}
                    <div>
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1">
                            {{ __('admin/ordersPages.Phone Number') }}
                        </label>
                        <div class="flex items-center gap-2 font-bold text-slate-800">
                            <span class="material-icons text-sm text-slate-400">phone</span>
                            {{ $order->user->phones->where('default', 1)->first()->phone ?? 'N/A' }}
                        </div>
                    </div>

                    {{-- Address --}}
                    <div>
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1">
                            {{ __('admin/ordersPages.Shipping Address') }}
                        </label>
                        <div
                            class="p-3 bg-slate-50 rounded-xl border border-slate-100 text-sm font-medium text-slate-600">
                            @php $address = $order->address; @endphp
                            @if ($address)
                                <div class="flex items-center gap-1 font-black text-slate-800 mb-1">
                                    {{ $address->city->name }} - {{ $address->governorate->name }}
                                </div>
                                <p class="m-0 text-xs leading-relaxed">
                                    {{ $address->details }}
                                    @if ($address->landmarks)
                                        <br><span class="text-slate-400">({{ $address->landmarks }})</span>
                                    @endif
                                </p>
                            @else
                                {{ __('admin/ordersPages.No address details') }}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
