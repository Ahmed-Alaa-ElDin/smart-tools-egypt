<div class="flex flex-col gap-1">
    @forelse ($statuses as $status)
        @php
            $colorClass = match(true) {
                in_array($status->id, [1, 2, 14, 15, 16]) => 'bg-amber-500 shadow-amber-200/50',
                in_array($status->id, [3, 45, 12]) => 'bg-emerald-500 shadow-emerald-200/50',
                in_array($status->id, [4, 5, 6]) => 'bg-blue-500 shadow-blue-200/50',
                in_array($status->id, [8, 9, 13]) => 'bg-rose-500 shadow-rose-200/50',
                default => 'bg-slate-400 shadow-slate-200/50'
            };
            $textColor = match(true) {
                in_array($status->id, [1, 2, 14, 15, 16]) => 'text-amber-600',
                in_array($status->id, [3, 45, 12]) => 'text-emerald-600',
                in_array($status->id, [4, 5, 6]) => 'text-blue-600',
                in_array($status->id, [8, 9, 13]) => 'text-rose-600',
                default => 'text-slate-600'
            };
        @endphp
        
        <div class="flex gap-4 group">
            {{-- Timeline Bar & Dot --}}
            <div class="flex flex-col items-center flex-shrink-0">
                <div class="relative w-5 h-5 rounded-full bg-white border border-slate-200 flex items-center justify-center z-10 shadow-sm group-hover:border-slate-300 transition-all duration-300">
                    <div class="w-2 h-2 rounded-full {{ $colorClass }}"></div>
                </div>
                @if (!$loop->last)
                    <div class="w-[1.5px] h-full bg-slate-100 mb-1"></div>
                @endif
            </div>

            {{-- Content --}}
            <div class="flex flex-col pb-6">
                <span class="text-[10px] font-black uppercase tracking-widest {{ $textColor }} mb-0.5">
                    {{ $status->name }}
                </span>
                <div class="flex items-center gap-1.5 text-slate-400">
                    <span class="material-icons text-[11px]">schedule</span>
                    <time class="text-[11px] font-bold">
                        {{ $status->pivot->created_at->translatedFormat('d M, Y • h:i A') }}
                    </time>
                </div>
                @if ($status->pivot->notes)
                    <div class="mt-2 p-2.5 bg-slate-50/80 rounded-xl border border-slate-100 text-[11px] font-medium text-slate-500 leading-relaxed italic relative">
                        <span class="absolute -top-2 start-3 bg-slate-50 px-1 text-[8px] font-black text-slate-300 uppercase tracking-widest">{{ __('admin/ordersPages.Notes') }}</span>
                        "{{ $status->pivot->notes }}"
                    </div>
                @endif
            </div>
        </div>
    @empty
        <div class="py-10 text-center bg-slate-50 rounded-2xl border border-dashed border-slate-200">
            <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center mx-auto mb-3 shadow-sm">
                <span class="material-icons text-slate-200 text-2xl">history</span>
            </div>
            <p class="text-xs font-bold text-slate-400">{{ __('front/homePage.No data to be tracked') }}</p>
        </div>
    @endforelse
</div>
