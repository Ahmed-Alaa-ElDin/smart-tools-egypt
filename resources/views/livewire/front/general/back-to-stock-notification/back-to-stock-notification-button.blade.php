<button
    @if ($isNotified) disabled @else wire:click.stop="addToNotify({{ $item_id }},'{{ $type }}')" @endif
    title="@if ($isNotified) {{ __('front/homePage.Will be notified') }} @else {{ __('front/homePage.Notify me when available') }} @endif"
    class="stop-propagation rounded-full h-9 text-center shadow inline-flex justify-center items-center gap-2 min-w-max mb-3
    @if ($isNotified) disabled text-primary bg-white border border-primary @else text-white bg-primary hover:bg-secondary animate-pulse @endif
    @if ($large) p-6 w-full @endif
    @if ($text) transition ease-in-out p-3 hover:animate-none @else w-9 @endif">
    @if ($isNotified)
        <span class="material-icons  @if ($large) text-xl @else text-lg @endif">
            notifications_active
        </span>
    @else
        <span class="material-icons  @if ($large) text-xl @else text-lg @endif">
            notification_add
        </span>
    @endif
    @if ($text)
        @if ($isNotified)
            <span class="font-bold @if (!$large) text-xs @endif">
                {{ __('front/homePage.Will be notified') }}
            </span>
        @else
            <span class="font-bold @if (!$large) text-xs @endif">
                {{ __('front/homePage.Notify me when available') }}
            </span>
        @endif
    @endif
</button>
