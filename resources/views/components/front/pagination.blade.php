<div class="flex justify-center items-center gap-3">
    @for ($i = 1; $i <= $totalPages; $i++)
        <span
            class="btn font-bold text-sm p-2 rounded-circle w-9 h-9 transition-all ease-in-out
            @if ($currentPage == $i) bg-primary hover:bg-red-800 select-none
            @else bg-gray-300 hover:bg-gray-400 text-gray-700 @endif
        " wire:click="$set('currentPage','{{ $i }}')">{{ $i }}</span>
    @endfor
</div>
