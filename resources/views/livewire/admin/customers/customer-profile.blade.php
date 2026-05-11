<div>
    {{-- Loader : Start --}}
    <x-admin.waiting />
    {{-- Loader : End --}}



    <!-- Main Profile Header -->
    <div class="row">
        <div class="col-md-12">
            <div
                class="card card-profile mb-8 bg-white/30 backdrop-blur-md border-0 shadow-xl overflow-hidden rounded-3xl">
                <div class="card-body p-8 flex flex-col md:flex-row items-center gap-8">
                    <!-- Avatar -->
                    <div class="relative group">
                        <div
                            class="w-32 h-32 md:w-40 md:h-40 rounded-full overflow-hidden border-4 border-primary/20 shadow-2xl transition-transform duration-500 group-hover:scale-105 bg-gray-100 flex items-center justify-center">
                            @if ($customer->profile_photo_path)
                                <img src="{{ asset('storage/images/profiles/cropped400/' . $customer->profile_photo_path) }}"
                                    class="w-full h-full object-cover construction-placeholder"
                                    data-placeholder-size="text-5xl">
                            @else
                                <div
                                    class="w-full h-full bg-gradient-to-br rtl:bg-gradient-to-bl from-primary/20 to-primary/40 flex items-center justify-center text-primary font-black text-5xl ltr:font-serif rtl:font-cairo">
                                    {{ mb_substr($customer->f_name, 0, 1) }}
                                </div>
                            @endif
                        </div>
                        <div
                            class="absolute -bottom-2 ltr:-right-2 rtl:-left-2 bg-success text-white p-2 rounded-full shadow-lg border-2 border-white">
                            <span class="material-icons text-sm">verified</span>
                        </div>
                    </div>

                    <!-- Basic Info Summary -->
                    <div class="text-center md:ltr:text-left md:rtl:text-right flex-grow">
                        <h2 class="text-3xl font-black text-gray-800 mb-1 ltr:font-serif rtl:font-cairo">
                            {{ $customer->f_name }} {{ $customer->l_name }}
                        </h2>
                        <p
                            class="text-primary font-semibold mb-4 flex items-center justify-center md:ltr:justify-start md:rtl:justify-start gap-2">
                            <span class="material-icons text-sm">email</span>
                            {{ $customer->email }}
                        </p>

                        <div class="flex flex-wrap justify-center md:ltr:justify-start md:rtl:justify-start gap-4">
                            <div
                                class="bg-amber-500/10 px-4 py-2 rounded-2xl flex items-center gap-2 border border-amber-500/20">
                                <span class="material-icons text-amber-600 text-xl">account_balance_wallet</span>
                                <div class="flex flex-col">
                                    <span
                                        class="text-xs text-gray-500 font-bold uppercase tracking-tighter">{{ __('admin/usersPages.Balance') }}</span>
                                    <span class="font-black text-amber-600">{{ formatTotal($customer->balance) }}
                                        <span class="text-[10px] font-bold">{{ __('admin/usersPages.LE') }}</span>
                                    </span>
                                </div>
                            </div>
                            <div
                                class="bg-primary/10 px-4 py-2 rounded-2xl flex items-center gap-2 border border-primary/20">
                                <span class="material-icons text-primary text-xl">stars</span>
                                <div class="flex flex-col">
                                    <span
                                        class="text-xs text-gray-500 font-bold uppercase tracking-tighter">{{ __('admin/usersPages.Points') }}</span>
                                    <span class="font-black text-primary">{{ $customer->valid_points }}</span>
                                </div>
                            </div>
                            <div
                                class="bg-success/10 px-4 py-2 rounded-2xl flex items-center gap-2 border border-success/20">
                                <span class="material-icons text-success text-xl">shopping_bag</span>
                                <div class="flex flex-col">
                                    <span
                                        class="text-xs text-gray-500 font-bold uppercase tracking-tighter">{{ __('admin/ordersPages.All Orders') }}</span>
                                    <span class="font-black text-success">{{ $customer->orders->count() }}</span>
                                </div>
                            </div>
                            <div class="bg-info/10 px-4 py-2 rounded-2xl flex items-center gap-2 border border-info/20">
                                <span class="material-icons text-info text-xl">visibility</span>
                                <div class="flex flex-col">
                                    <span
                                        class="text-xs text-gray-500 font-bold uppercase tracking-tighter">{{ __('admin/usersPages.Visits Count') }}</span>
                                    <span class="font-black text-info">{{ $customer->visit_num }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex flex-col gap-3 min-w-[200px]">
                        <a href="{{ route('admin.customers.edit', $customer->id) }}"
                            class="btn btn-primary btn-round flex items-center justify-center gap-2 m-0 shadow-lg hover:shadow-primary/50 transition-all duration-300">
                            <span class="material-icons text-sm">edit</span>
                            {{ __('admin/usersPages.Edit Customer') }}
                        </a>
                        {{-- Add Points Action --}}
                        <button
                            onclick="window.dispatchEvent(new CustomEvent('swalAddPointsForm', { detail: { user_id: {{ $customer->id }}, title: '{{ __('admin/usersPages.Add Points') }}', confirmButtonText: '{{ __('admin/usersPages.Add') }}', denyButtonText: '{{ __('admin/usersPages.Cancel') }}' } }))"
                            class="btn bg-amber-500 hover:bg-amber-600 text-white btn-round flex items-center justify-center gap-2 m-0 shadow-lg hover:shadow-amber-500/50 transition-all duration-300">
                            <span class="material-icons text-sm">add_circle</span>
                            {{ __('admin/usersPages.Add Points') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Detailed Info Card -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Basic Info -->
            <div
                class="card border-0 shadow-xl rounded-3xl overflow-hidden transition-all duration-300 hover:shadow-2xl h-full">
                <div class="card-header bg-gradient-to-r rtl:bg-gradient-to-l from-primary to-primary-dark p-4">
                    <h4 class="card-title text-white font-bold flex items-center gap-2 m-0">
                        <span class="material-icons">info</span>
                        {{ __('admin/usersPages.Other Information') }}
                    </h4>
                </div>
                <div class="card-body p-6 space-y-4">
                    <div class="flex justify-between items-center pb-3 border-b border-gray-100">
                        <span class="text-gray-500 font-semibold">{{ __('admin/usersPages.Gender') }}</span>
                        <span
                            class="font-bold text-gray-800">{{ $customer->gender == 0 ? __('admin/usersPages.Male') : __('admin/usersPages.Female') }}</span>
                    </div>
                    <div class="flex justify-between items-center pb-3 border-b border-gray-100">
                        <span class="text-gray-500 font-semibold">{{ __('admin/usersPages.Birth Date') }}</span>
                        <span
                            class="font-bold text-gray-800">{{ $customer->birth_date ?? __('admin/ordersPages.N/A') }}</span>
                    </div>
                    <div class="flex justify-between items-center pb-3 border-b border-gray-100">
                        <span class="text-gray-500 font-semibold">{{ __('admin/usersPages.Created from') }}</span>
                        <span class="font-bold text-gray-800">{{ $customer->created_at->format('Y-m-d') }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-500 font-semibold">{{ __('admin/usersPages.Last Visit') }}</span>
                        <span
                            class="font-bold text-gray-800">{{ $customer->last_visit_at ? $customer->last_visit_at->diffForHumans() : __('admin/ordersPages.N/A') }}</span>
                    </div>
                </div>
            </div>

        </div>

        <!-- Contact Info -->
        <div class="lg:col-span-2 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 h-full">
                <!-- Phones -->
                <div
                    class="card border-0 shadow-xl rounded-3xl overflow-hidden transition-all duration-300 hover:shadow-2xl">
                    <div
                        class="card-header bg-gradient-to-r rtl:bg-gradient-to-l from-success to-success-dark p-4 flex justify-between items-center">
                        <h4 class="card-title text-white font-bold flex items-center gap-2 m-0">
                            <span class="material-icons">phone</span>
                            {{ __('admin/ordersPages.Phone Numbers') }}
                        </h4>
                    </div>
                    <div class="card-body p-4 space-y-3">
                        @forelse($customer->phones as $phone)
                            <div
                                class="flex items-center justify-between p-3 rounded-2xl bg-gray-50 border border-gray-100 group transition-all duration-300 hover:bg-success/5 hover:border-success/20">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="p-2 rounded-full {{ $phone->default ? 'bg-success text-white shadow-lg' : 'bg-gray-200 text-gray-500' }}">
                                        <span class="material-icons text-sm">phone</span>
                                    </div>
                                    <span class="font-bold text-gray-800 ltr:tracking-wider">{{ $phone->phone }}</span>
                                </div>
                                @if ($phone->default)
                                    <span
                                        class="text-[10px] bg-success/20 text-success px-2 py-1 rounded-lg font-black uppercase tracking-tighter">
                                        {{ __('admin/ordersPages.Primary') }}
                                    </span>
                                @endif
                            </div>
                        @empty
                            <div class="text-center py-6 text-gray-400">
                                <span class="material-icons text-4xl mb-2 opacity-20">phone_disabled</span>
                                <p class="font-semibold">{{ __('admin/ordersPages.No Phones Saved') }}</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Addresses -->
                <div
                    class="card border-0 shadow-xl rounded-3xl overflow-hidden transition-all duration-300 hover:shadow-2xl overflow-y-auto max-h-[500px]">
                    <div
                        class="card-header bg-gradient-to-r rtl:bg-gradient-to-l from-info to-info-dark p-4 flex justify-between items-center">
                        <h4 class="card-title text-white font-bold flex items-center gap-2 m-0">
                            <span class="material-icons">location_on</span>
                            {{ __('admin/ordersPages.Shipping Addresses') }}
                        </h4>
                    </div>
                    <div class="card-body p-4 space-y-4">
                        @forelse($customer->addresses as $address)
                            <div
                                class="relative p-4 rounded-3xl border-2 {{ $address->default ? 'border-info/50 bg-info/5 shadow-inner' : 'border-gray-100 bg-white' }} transition-all duration-300 hover:border-info hover:shadow-lg">
                                @if ($address->default)
                                    <div
                                        class="absolute -top-3 ltr:-right-3 rtl:-left-3 bg-info text-white p-1 rounded-xl shadow-lg border-2 border-white">
                                        <span class="material-icons text-sm">stars</span>
                                    </div>
                                @endif

                                <div class="mb-2">
                                    <h5 class="font-black text-gray-800 mb-1 flex items-center gap-2 text-sm">
                                        <span class="material-icons text-info text-xs">public</span>
                                        {{ $address->governorate->name }} / {{ $address->city->name }}
                                    </h5>
                                    <p class="text-xs text-gray-600 line-clamp-2 italic">
                                        {{ $address->details }}
                                    </p>
                                </div>

                                @if ($address->landmarks)
                                    <div class="bg-white/50 p-2 rounded-xl border border-dashed border-gray-300 mt-2">
                                        <span
                                            class="text-[9px] font-black text-gray-400 uppercase block mb-1 ltr:font-sans rtl:font-cairo tracking-widest">{{ __('admin/ordersPages.Landmarks') }}</span>
                                        <p class="text-[10px] text-gray-500 font-semibold">
                                            {{ $address->landmarks }}</p>
                                    </div>
                                @endif
                            </div>
                        @empty
                            <div class="text-center py-6 text-gray-400">
                                <span class="material-icons text-4xl mb-2 opacity-10">map</span>
                                <p class="font-bold">{{ __('admin/ordersPages.No Addresses Saved') }}</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Orders History -->
    <div class="row mb-8">
        <div class="col-md-12">
            <div
                class="card border-0 shadow-xl rounded-3xl overflow-hidden transition-all duration-300 hover:shadow-2xl">
                <div
                    class="card-header bg-gradient-to-r rtl:bg-gradient-to-l from-amber-600 to-white p-4 flex justify-between items-center">
                    <h4 class="card-title text-white font-bold flex items-center gap-2 m-0">
                        <span class="material-icons">history</span>
                        {{ __('admin/ordersPages.All Orders') }}
                    </h4>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-gray-50 border-b border-gray-100">
                                <tr>
                                    <th
                                        class="text-center font-black text-gray-400 text-xs uppercase px-6 py-4 ltr:font-sans rtl:font-cairo tracking-widest border-0">
                                        {{ __('admin/ordersPages.ID') }}</th>
                                    <th
                                        class="font-black text-gray-400 text-xs uppercase px-6 py-4 ltr:font-sans rtl:font-cairo tracking-widest border-0">
                                        {{ __('admin/ordersPages.Status') }}</th>
                                    <th
                                        class="text-center font-black text-gray-400 text-xs uppercase px-6 py-4 ltr:font-sans rtl:font-cairo tracking-widest border-0">
                                        {{ __('admin/ordersPages.Total') }}</th>
                                    <th
                                        class="text-center font-black text-gray-400 text-xs uppercase px-6 py-4 ltr:font-sans rtl:font-cairo tracking-widest border-0">
                                        {{ __('admin/ordersPages.Last Update') }}</th>
                                    <th
                                        class="text-center font-black text-gray-400 text-xs uppercase px-6 py-4 ltr:font-sans rtl:font-cairo tracking-widest border-0">
                                        {{ __('admin/usersPages.Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @forelse($customer->orders as $order)
                                    <tr class="group transition-all duration-300 hover:bg-gray-50/80 cursor-default">
                                        <td class="text-center px-6 py-4 border-0">
                                            <span class="font-black text-gray-800">#{{ $order->id }}</span>
                                        </td>
                                        <td class="px-6 py-4 border-0">
                                            <span
                                                class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-[10px] font-bold shadow-sm ring-1 ring-inset"
                                                style="background-color: {{ $order->status->color }}15; color: {{ $order->status->color }}; ring-color: {{ $order->status->color }}30">
                                                <span class="w-1 h-1 rounded-full"
                                                    style="background-color: {{ $order->status->color }}"></span>
                                                {{ $order->status->name }}
                                            </span>
                                        </td>
                                        <td class="text-center px-6 py-4 border-0">
                                            <span
                                                class="font-black text-success">{{ formatTotal($order->invoice->total ?? 0) }}</span>
                                            <span
                                                class="text-[9px] text-gray-400 font-bold ml-1 uppercase">{{ __('admin/ordersPages.EGP') }}</span>
                                        </td>
                                        <td class="text-center px-6 py-4 border-0">
                                            <span
                                                class="text-xs text-gray-500 font-semibold">{{ $order->updated_at->format('Y-m-d H:i') }}</span>
                                        </td>
                                        <td class="text-center px-6 py-4 border-0">
                                            <div
                                                class="flex justify-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                                <a href="{{ route('admin.orders.show', $order->id) }}"
                                                    class="p-2 rounded-xl bg-info/10 text-info hover:bg-info hover:text-white transition-all duration-300"
                                                    title="{{ __('admin/ordersPages.View') }}">
                                                    <span class="material-icons text-sm">visibility</span>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-20 text-gray-400 border-0">
                                            <span
                                                class="material-icons text-5xl mb-4 opacity-10">shopping_cart_checkout</span>
                                            <p class="text-lg font-bold">
                                                {{ __('admin/ordersPages.No data in this table') }}</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Points History -->
    <div class="row mb-8">
        <div class="col-md-12">
            <div
                class="card border-0 shadow-xl rounded-3xl overflow-hidden transition-all duration-300 hover:shadow-2xl">
                <div
                    class="card-header bg-gradient-to-r rtl:bg-gradient-to-l from-primary to-primary-dark p-4 flex justify-between items-center">
                    <h4 class="card-title text-white font-bold flex items-center gap-2 m-0">
                        <span class="material-icons">stars</span>
                        {{ __('admin/usersPages.Points History') }}
                    </h4>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-gray-50 border-b border-gray-100">
                                <tr>
                                    <th
                                        class="text-center font-black text-gray-400 text-xs uppercase px-6 py-4 ltr:font-sans rtl:font-cairo tracking-widest border-0">
                                        {{ __('admin/usersPages.Points') }}</th>
                                    <th
                                        class="font-black text-gray-400 text-xs uppercase px-6 py-4 ltr:font-sans rtl:font-cairo tracking-widest border-0">
                                        {{ __('admin/ordersPages.Status') }}</th>
                                    <th
                                        class="text-center font-black text-gray-400 text-xs uppercase px-6 py-4 ltr:font-sans rtl:font-cairo tracking-widest border-0">
                                        {{ __('admin/usersPages.Source') }}</th>
                                    <th
                                        class="text-center font-black text-gray-400 text-xs uppercase px-6 py-4 ltr:font-sans rtl:font-cairo tracking-widest border-0">
                                        {{ __('admin/usersPages.Added Date') }}</th>
                                    <th
                                        class="text-center font-black text-gray-400 text-xs uppercase px-6 py-4 ltr:font-sans rtl:font-cairo tracking-widest border-0">
                                        {{ __('admin/usersPages.Expiry Date') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @forelse($customer->points as $point)
                                    @php
                                        $isExpired = $point->status == 1 && $point->created_at->addDays(90)->isPast();
                                        $expiryDate = $point->created_at->addDays(90);
                                    @endphp
                                    <tr class="group transition-all duration-300 hover:bg-gray-50/80 cursor-default">
                                        <td class="text-center px-6 py-4 border-0">
                                            <span
                                                class="font-black {{ $point->status == 1 && !$isExpired ? 'text-primary' : 'text-gray-400' }} text-lg">{{ $point->value }}</span>
                                        </td>
                                        <td class="px-6 py-4 border-0">
                                            @if ($isExpired)
                                                <span
                                                    class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-[10px] font-bold shadow-sm ring-1 ring-inset bg-red-50 text-red-600 ring-red-100">
                                                    <span class="w-1 h-1 rounded-full bg-red-600"></span>
                                                    {{ __('admin/usersPages.Expired') }}
                                                </span>
                                            @else
                                                <span
                                                    class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-[10px] font-bold shadow-sm ring-1 ring-inset"
                                                    style="background-color: {{ $point->status == 1 ? '#4caf50' : '#ff9800' }}15; color: {{ $point->status == 1 ? '#4caf50' : '#ff9800' }}; ring-color: {{ $point->status == 1 ? '#4caf50' : '#ff9800' }}30">
                                                    <span class="w-1 h-1 rounded-full"
                                                        style="background-color: {{ $point->status == 1 ? '#4caf50' : '#ff9800' }}"></span>
                                                    {{ $point->status == 1 ? __('admin/usersPages.Approved') : __('admin/usersPages.Pending') }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-center px-6 py-4 border-0">
                                            @if ($point->order_id)
                                                <a href="{{ route('admin.orders.show', $point->order_id) }}"
                                                    class="text-xs font-bold text-info hover:underline flex items-center justify-center gap-1">
                                                    <span class="material-icons text-xs">shopping_cart</span>
                                                    #{{ $point->order_id }}
                                                </a>
                                            @else
                                                <span
                                                    class="text-xs font-bold text-gray-500 flex items-center justify-center gap-1">
                                                    <span class="material-icons text-xs">person</span>
                                                    {{ __('admin/usersPages.Manual') }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-center px-6 py-4 border-0">
                                            <span
                                                class="text-xs text-gray-500 font-semibold">{{ $point->created_at->format('Y-m-d') }}</span>
                                        </td>
                                        <td class="text-center px-6 py-4 border-0">
                                            <span
                                                class="text-xs {{ $isExpired ? 'text-red-500 font-black' : 'text-gray-500 font-semibold' }}">{{ $expiryDate->format('Y-m-d') }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-20 text-gray-400 border-0">
                                            <span class="material-icons text-5xl mb-4 opacity-10">stars</span>
                                            <p class="text-lg font-bold">
                                                {{ __('admin/ordersPages.No data in this table') }}</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('js')
        <script>
            // #### Customer Add Points ####
            window.addEventListener('swalAddPointsForm', function(e) {
                Swal.fire({
                    title: `<div class="flex items-center justify-center gap-3 text-amber-500">
                            <span class="material-icons text-3xl">stars</span>
                            <span class="font-black">${e.detail.title}</span>
                        </div>`,
                    input: 'number',
                    inputPlaceholder: '0',
                    customClass: {
                        popup: 'rounded-[2rem] border-0 shadow-2xl p-8',
                        title: 'text-2xl font-black pt-4',
                        input: 'role-grapper rounded-2xl text-center border-2 border-gray-100 focus:outline-none focus:border-amber-500 focus:ring-4 focus:ring-amber-500/10 text-3xl font-black py-4 mt-6',
                        confirmButton: 'btn bg-amber-500 hover:bg-amber-600 text-white btn-round px-10 py-3 mx-2 font-bold shadow-lg shadow-amber-500/30',
                        cancelButton: 'btn bg-gray-200 hover:bg-gray-300 text-gray-700 btn-round px-10 py-3 mx-2 font-bold',
                    },
                    buttonsStyling: false,
                    showCancelButton: true,
                    confirmButtonText: e.detail.confirmButtonText,
                    cancelButtonText: e.detail.denyButtonText,
                    reverseButtons: true,

                }).then((result) => {
                    if (result.isConfirmed) {
                        Livewire.dispatch('addPoints', {
                            user_id: e.detail.user_id,
                            points: result.value
                        });
                    }
                });
            });
            // #### Customer Add Points ####
        </script>
    @endpush
</div>
