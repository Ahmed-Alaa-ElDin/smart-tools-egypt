<div class="flex flex-col justify-center gap-2">
    <div class="h4 text-center font-bold my-2 text-gray-800">
        {{ __('admin/ordersPages.Track Order') }}
    </div>
    <div class="px-4">
        <ol class="relative border-l rtl:border-r rtl:border-l-0 border-gray-200">
            @forelse ($statuses as $status)
                <li class="mb-6 ml-4 rtl:mr-4 rtl:ml-0">
                    <div
                        class="absolute w-3 h-3 rounded-full mt-1.5 -left-1.5 rtl:-right-1.5 border border-white {{ in_array($status->id, [1, 2, 14, 15, 16])
                            ? 'bg-yellow-500'
                            : (in_array($status->id, [3, 45, 12])
                                ? 'bg-green-500'
                                : (in_array($status->id, [4, 5, 6])
                                    ? 'bg-blue-500'
                                    : (in_array($status->id, [8, 9, 13])
                                        ? 'bg-red-500'
                                        : 'bg-blue-500'))) }}">
                    </div>
                    <time dir="ltr"
                        class="mb-1 text-sm font-normal leading-none {{ in_array($status->id, [1, 2, 14, 15, 16])
                            ? 'text-yellow-500'
                            : (in_array($status->id, [3, 45, 12])
                                ? 'text-green-500'
                                : (in_array($status->id, [4, 5, 6])
                                    ? 'text-blue-500'
                                    : (in_array($status->id, [8, 9, 13])
                                        ? 'text-red-500'
                                        : 'text-blue-500'))) }}">
                        {{ $status->pivot->created_at->format('Y-m-d h:i:s a') }}
                    </time>
                    <h3
                        class="text-lg font-semibold {{ in_array($status->id, [1, 2, 14, 15, 16])
                            ? 'text-yellow-600'
                            : (in_array($status->id, [3, 45, 12])
                                ? 'text-green-600'
                                : (in_array($status->id, [4, 5, 6])
                                    ? 'text-blue-600'
                                    : (in_array($status->id, [8, 9, 13])
                                        ? 'text-red-600'
                                        : 'text-blue-600'))) }}">
                        {{ $status->name }}
                    </h3>
                    @if ($status->pivot->notes)
                        <p class="mb-4 text-base font-normal text-gray-500">
                            {{ $status->pivot->notes }}
                        </p>
                    @endif
                </li>
            @empty
                <div class="text-center font-bold">
                    {{ __('front/homePage.No data to be tracked') }}
                </div>
            @endforelse
        </ol>
    </div>
</div>
