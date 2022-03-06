<div>
    <form enctype="multipart/form-data">
        {{-- Name --}}
        <div class="grid grid-cols-12 gap-x-6 gap-y-2 items-center bg-red-100 p-2 rounded text-center my-2">
            <label
                class="col-span-12 md:col-span-2 text-black font-bold m-0 text-center">{{ __('admin/deliveriesPages.Company Name') }}</label>
            {{-- Name Ar --}}
            <div class="col-span-6 md:col-span-5">
                <input class="py-1 w-full rounded text-center border-gray-300 text-gray-500" type="text"
                    wire:model.lazy="name.ar" disabled>
            </div>
            {{-- Name En --}}
            <div class="col-span-6 md:col-span-5 ">
                <input class="py-1 w-full rounded text-center border-gray-300 text-gray-500" type="text"
                    wire:model.lazy="name.en" disabled>
            </div>
        </div>

        {{-- Address --}}
        <div class="grid grid-cols-12 gap-x-4 gap-y-2 items-center bg-gray-100 p-2 rounded text-center my-2">
            <label
                class="col-span-12 md:col-span-2 text-black font-bold m-0 text-center">{{ __('admin/deliveriesPages.Zones') }}</label>
            {{-- User Address Select Boxes --}}
            <div class="grid grid-cols-12 gap-x-4 gap-y-2 col-span-12 md:col-span-10">

                @foreach ($zones as $index => $zone)
                    <div class="bg-gray-200 rounded col-span-12 grid grid-cols-12 gap-x-4 gap-y-2 p-2 ">

                        {{-- toolbar --}}
                        <div class="col-span-12 flex justify-around items-center bg-gray-300 py-1 rounded-xl md:p-1">

                            {{-- Activation button --}}
                            <div class="text-sm text-gray-900 flex items-center bg-white px-3 rounded-full"> <span
                                    class="inline-block rtl:ml-2 ltr:mr-2 font-bold text-xs">{{ __('admin/deliveriesPages.Activate') }}</span>
                                {!! $delivery->is_active ? '<span class="inline-block cursor-pointer material-icons text-green-600 text-3xl" wire:click="activate(' . $delivery->id . ')">toggle_on</span>' : '<span class="block cursor-pointer material-icons text-red-600 text-3xl" wire:click="activate(' . $delivery->id . ')">toggle_off</span>' !!}
                            </div>

                            {{-- Remove button --}}
                            <button class=" bg-red-500 hover:bg-red-700 text-white p-1 rounded-full shadow btn btn-xs"
                                wire:click.prevent='removeZone({{ $index }})'
                                title="{{ __('admin/deliveriesPages.Delete') }}">
                                <span class="material-icons">
                                    remove
                                </span>
                            </button>
                        </div>

                        {{-- Zone Name --}}
                        <div class="col-span-12 grid grid-cols-12 gap-x-4 gap-y-2 p-2 ">
                            <label
                                class="col-span-12 md:col-span-2 text-black font-bold m-0 text-center">{{ __('admin/deliveriesPages.Zone Name') }}</label>
                            {{-- Name Ar --}}
                            <div class="col-span-6 md:col-span-5">
                                <input class="py-1 w-full rounded text-center border-gray-300 text-gray-500" type="text"
                                    wire:model.lazy="" placeholder="{{ __('admin/usersPages.in Arabic') }}">
                            </div>
                            {{-- Name En --}}
                            <div class="col-span-6 md:col-span-5 ">
                                <input class="py-1 w-full rounded text-center border-gray-300 text-gray-500" type="text"
                                    wire:model.lazy="" placeholder="{{ __('admin/usersPages.in English') }}">
                            </div>
                        </div>

                        <hr class="col-span-12">

                        {{-- Zone Fees --}}
                        <div class="col-span-12 grid grid-cols-12 gap-x-4 gap-y-2 p-2 ">
                            <label
                                class="col-span-12 md:col-span-2 text-black font-bold m-0 text-center">{{ __('admin/deliveriesPages.Shipping Fees') }}</label>
                            {{-- Base Fees --}}
                            <div class="col-span-4 md:col-span-5">
                                <input class="py-1 w-full rounded text-center border-gray-300 text-gray-500" type="text"
                                    wire:model.lazy="" placeholder="{{ __('admin/usersPages.Base Fees (EGP)') }}">
                            </div>
                            {{-- Base Weight --}}
                            <div class="col-span-4 md:col-span-5 ">
                                <input class="py-1 w-full rounded text-center border-gray-300 text-gray-500" type="text"
                                    wire:model.lazy="" placeholder="{{ __('admin/usersPages.Base Weight (Kg)') }}">
                            </div>

                            {{-- Weight by Kg --}}
                            <div class="col-span-4 md:col-span-5 ">
                                <input class="py-1 w-full rounded text-center border-gray-300 text-gray-500" type="text"
                                    wire:model.lazy="" placeholder="{{ __('admin/usersPages.Fees by Kg') }}">
                            </div>
                        </div>

                        <hr class="col-span-12">

                        {{-- Zone's addresses --}}
                        <div class="col-span-12 grid grid-cols-12 bg-gray-300 p-1 rounded-xl">

                            {{-- Addresses toolbar --}}
                            <div
                                class="col-span-12 sm:col-span-4 sm:order-2 flex justify-around items-center bg-gray-400 py-1 rounded-xl md:p-1">

                                {{-- Select All button --}}
                                <div class="text-gray-900 bg-white p-1 m-0 shadow rounded cursor-pointer btn">
                                    <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="img" width="1em"
                                        height="1em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24"
                                        class="inline-block w-6 h-6">
                                        <path fill="currentColor"
                                            d="M20.496 5.627A2.25 2.25 0 0 1 22 7.75v10A4.25 4.25 0 0 1 17.75 22h-10a2.25 2.25 0 0 1-2.123-1.504l2.097.004H17.75a2.75 2.75 0 0 0 2.75-2.75v-10l-.004-.051V5.627ZM17.246 2a2.25 2.25 0 0 1 2.25 2.25v12.997a2.25 2.25 0 0 1-2.25 2.25H4.25A2.25 2.25 0 0 1 2 17.247V4.25A2.25 2.25 0 0 1 4.25 2h12.997Zm0 1.5H4.25a.75.75 0 0 0-.75.75v12.997c0 .414.336.75.75.75h12.997a.75.75 0 0 0 .75-.75V4.25a.75.75 0 0 0-.75-.75Zm-7.665 7.858L13.47 7.47a.75.75 0 0 1 1.133.976l-.073.084l-4.5 4.5a.75.75 0 0 1-1.056.004L8.9 12.95l-1.5-2a.75.75 0 0 1 1.127-.984l.073.084l.981 1.308L13.47 7.47l-3.89 3.888Z" />
                                    </svg>
                                </div>

                                {{-- Select All button --}}
                                <div class="text-gray-900 bg-white p-1 m-0 shadow rounded cursor-pointer btn">
                                    <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="img" width="1em"
                                        height="1em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24"
                                        class="inline-block w-6 h-6">
                                        <path fill="currentColor"
                                            d="M20.496 5.627A2.25 2.25 0 0 1 22 7.75v10A4.25 4.25 0 0 1 17.75 22h-10a2.25 2.25 0 0 1-2.123-1.504l2.097.004H17.75a2.75 2.75 0 0 0 2.75-2.75v-10l-.004-.051V5.627ZM17.246 2a2.25 2.25 0 0 1 2.25 2.25v12.997a2.25 2.25 0 0 1-2.25 2.25H4.25A2.25 2.25 0 0 1 2 17.247V4.25A2.25 2.25 0 0 1 4.25 2h12.997Zm0 1.5H4.25a.75.75 0 0 0-.75.75v12.997c0 .414.336.75.75.75h12.997a.75.75 0 0 0 .75-.75V4.25a.75.75 0 0 0-.75-.75Z" />
                                    </svg>
                                </div>

                                {{-- Remove button --}}
                                <div>
                                    <button
                                        class="bg-red-500 hover:bg-red-700 text-white p-1 rounded-full shadow btn btn-xs"
                                        wire:click.prevent='removeZone({{ $index }})'
                                        title="{{ __('admin/deliveriesPages.Delete') }}">
                                        <span class="material-icons">
                                            remove
                                        </span>
                                    </button>
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-8 sm:order-1 ">
                                Ahmed
                            </div>
                        </div>
                    </div>
                @endforeach

                {{-- Add New Address Button --}}
                <button
                    class="col-start-4 col-span-6 sm:col-start-5 sm:col-span-4 bg-gray-600 hover:bg-gray-800 text-white font-bold py-2 px-4 rounded-xl shadow btn btn-sm text-center text-xs"
                    wire:click.prevent="addAddress" title="{{ __('admin/deliveriesPages.Add Zone') }}"> <span
                        class="material-icons rtl:ml-1 ltr:mr-1">
                        add
                    </span>
                    {{ __('admin/deliveriesPages.Add Zone') }}</button>
            </div>
        </div>
    </form>
</div>
