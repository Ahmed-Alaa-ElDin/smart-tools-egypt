<div class="divide-y divide-gray-50">
    @forelse ($items as $item)
        <x-admin.orders.order-item-row :item="$item" />
    @empty
        <div class="p-12 text-center">
            <span class="material-icons text-slate-200 text-5xl mb-2">shopping_basket</span>
            <p class="text-slate-400 font-bold">{{ __('admin/ordersPages.No items found in this order') }}</p>
        </div>
    @endforelse
</div>
