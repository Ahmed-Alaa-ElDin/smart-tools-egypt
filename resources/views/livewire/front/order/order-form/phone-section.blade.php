<div>
    <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
        <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
            <span class="material-icons text-primary">phone</span>
            {{ __('front/homePage.Contact Phone') }}
        </h3>
        <button wire:click="toggleAddForm"
            class="text-sm text-primary hover:text-primary/80 font-bold flex items-center gap-1">
            @if ($show_add_form)
                {{ __('front/homePage.Cancel') }}
                <span class="material-icons text-xs">close</span>
            @else
                {{ __('front/homePage.Add New Phone') }}
                <span class="material-icons text-xs">add</span>
            @endif
        </button>
    </div>

    <div class="p-6">
        @if ($show_add_form)
            <div class="animate-fadeIn space-y-4">
                <div class="space-y-1">
                    <label class="text-xs font-bold text-gray-400 uppercase tracking-wider">
                        {{ __('front/homePage.Phone number') }}
                    </label>
                    <div class="flex gap-2">
                        <input type="tel" wire:model="new_phone" dir="ltr"
                            class="flex-1 rounded-xl border-gray-200 focus:border-primary focus:ring-primary text-sm @error('new_phone') border-red-500 @enderror"
                            placeholder="01xxxxxxxxx">
                        <button wire:click="savePhone"
                            class="px-6 py-2 bg-gray-800 text-white rounded-xl font-bold hover:bg-black transition-colors">
                            {{ __('front/homePage.Save') }}
                        </button>
                    </div>
                    @error('new_phone')
                        <span class="text-xs text-red-500 font-bold px-1">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        @else
            <div class="space-y-3">
                {{-- Primary Phone Selection --}}
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">
                    {{ __('front/homePage.Primary Phone') }}
                </p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    @forelse($phones as $phoneItem)
                        <div wire:click="selectPhone('{{ $phoneItem->phone }}')"
                            class="relative p-4 rounded-2xl border-2 cursor-pointer transition-all duration-300 group {{ $selected_phone == $phoneItem->phone ? 'border-red-600 bg-red-50/30' : 'border-gray-100 hover:border-gray-200' }}">

                            <div class="flex items-center gap-3">
                                <div
                                    class="w-10 h-10 rounded-full flex items-center justify-center {{ $selected_phone == $phoneItem->phone ? 'bg-primary text-white' : 'bg-gray-100 text-gray-400 group-hover:bg-gray-200' }}">
                                    <span class="material-icons text-sm">phone</span>
                                </div>
                                <div class="flex-grow flex gap-2 items-center">
                                    <span class="font-bold text-gray-800 text-sm"
                                        dir="ltr">{{ $phoneItem->phone }}</span>
                                    @if ($phoneItem->default)
                                        <span class="text-[10px] text-success font-bold uppercase ml-2">
                                            {{ __('front/homePage.Default') }}
                                        </span>
                                    @endif
                                </div>
                                @if ($selected_phone == $phoneItem->phone)
                                    <span class="material-icons text-success text-sm animate-bounce">check_circle</span>
                                @endif
                            </div>

                            {{-- Delete Button --}}
                            @if ($phones->count() > 1)
                                <button
                                    wire:click.stop="$dispatch('swalConfirm', {
                                    text: '{{ __('front/homePage.Are you sure you want to delete this phone?') }}',
                                    confirmButtonText: '{{ __('front/homePage.Delete') }}',
                                    denyButtonText: '{{ __('front/homePage.Cancel') }}',
                                    denyButtonColor: 'green',
                                    confirmButtonColor: 'red',
                                    focusDeny: true,
                                    icon: 'warning',
                                    method: 'deletePhone',
                                    id: '{{ $phoneItem->phone }}'
                                })"
                                    class="absolute bottom-2 right-2 p-1 rounded-full bg-red-50 text-red-400 hover:bg-red-100 hover:text-red-600 transition-all opacity-0 group-hover:opacity-100">
                                    <span class="material-icons text-xs">delete</span>
                                </button>
                            @endif
                        </div>
                    @empty
                        <div
                            class="md:col-span-2 py-8 text-center bg-gray-50 rounded-2xl border-2 border-dashed border-gray-100">
                            <span class="material-icons text-4xl text-gray-200 mb-2">phone_disabled</span>
                            <p class="text-gray-500 text-sm font-medium">
                                {{ __('front/homePage.No phones found. Please add one.') }}
                            </p>
                        </div>
                    @endforelse
                </div>

                {{-- Secondary Phone Display --}}
                @if ($selected_phone_secondary)
                    <div class="mt-4 p-3 bg-green-50 rounded-xl flex items-center gap-2">
                        <span class="material-icons text-successDark text-sm">phone_forwarded</span>
                        <span class="text-xs text-successDark font-bold">
                            {{ __('front/homePage.Secondary Phone:') }}
                        </span>
                        <span class="text-sm font-bold text-gray-800"
                            dir="ltr">{{ $selected_phone_secondary }}</span>
                    </div>
                @endif
            </div>
        @endif
    </div>
</div>
