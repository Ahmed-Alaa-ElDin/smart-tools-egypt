<div>
    {{-- Loader : Start --}}
    <x-admin.waiting />
    {{-- Loader : End --}}

    <form enctype="multipart/form-data">
        @csrf
        {{-- Image --}}
        <div class="grid grid-cols-12 gap-x-6 gap-y-2 items-center bg-gray-100 p-2 text-center my-2 	rounded">

            {{-- Loading Spinner --}}
            <div wire:loading wire:target="photo" class="col-span-12 my-2">
                <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="img" width="1em" height="1em"
                    preserveAspectRatio="xMidYMid meet" viewBox="0 0 50 50" class="animate-spin inline-block">
                    <path fill="currentColor"
                        d="M41.9 23.9c-.3-6.1-4-11.8-9.5-14.4c-6-2.7-13.3-1.6-18.3 2.6c-4.8 4-7 10.5-5.6 16.6c1.3 6 6 10.9 11.9 12.5c7.1 2 13.6-1.4 17.6-7.2c-3.6 4.8-9.1 8-15.2 6.9c-6.1-1.1-11.1-5.7-12.5-11.7c-1.5-6.4 1.5-13.1 7.2-16.4c5.9-3.4 14.2-2.1 18.1 3.7c1 1.4 1.7 3.1 2 4.8c.3 1.4.2 2.9.4 4.3c.2 1.3 1.3 3 2.8 2.1c1.3-.8 1.2-2.5 1.1-3.8c0-.4.1.7 0 0z" />
                </svg> <span> &nbsp;&nbsp; {{ __('admin/deliveriesPages.Uploading ...') }}</span>
            </div>

            {{-- preview --}}
            @if ($temp_path)
                <div class="col-span-12 text-center w-1/2 md:w-1/4 my-2">
                    <img src="{{ $temp_path }}" class="rounded-xl">
                </div>
                <div class="col-span-12 text-center">
                    <button class="btn btn-danger btn-sm text-bold"
                        wire:click.prevent='removePhoto'>{{ __('admin/deliveriesPages.Remove / Replace Logo') }}</button>
                </div>
            @else
                <label for="photo" class="col-span-12 md:col-span-2 text-black font-bold m-0 text-center">
                    {{ __('admin/deliveriesPages.Logo') }} </label>
                <input
                    class="form-control block w-full md:w-50 px-2 py-1 text-sm font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none col-span-12 md:col-span-10 py-1 rounded text-center border-gray-300 focus:outline-gray-600 focus:ring-gray-300 focus:border-gray-300"
                    id="photo" type="file" type="image" wire:model="photo">

                @error('photo')
                    <span class="col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">{{ $message }}</span>
                @enderror
            @endif

        </div>

        {{-- Name --}}
        <div class="grid grid-cols-12 gap-x-6 gap-y-2 items-center bg-red-100 p-2 rounded text-center my-2">
            <label
                class="col-span-12 md:col-span-2 text-black font-bold m-0 text-center">{{ __('admin/deliveriesPages.Name') }}</label>
            {{-- Name Ar --}}
            <div class="col-span-6 md:col-span-5">
                <input
                    class="first_input py-1 w-full rounded text-center border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300 @error('name.ar') border-red-900 border-2 @enderror"
                    type="text" wire:model.lazy="name.ar" placeholder="{{ __('admin/deliveriesPages.in Arabic') }}"
                    required>
                @error('name.ar')
                    <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                        {{ $message }}</div>
                @enderror
            </div>
            {{-- Name En --}}
            <div class="col-span-6 md:col-span-5 ">
                <input
                    class="py-1 w-full rounded text-center border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300 @error('name.en') border-red-900 border-2 @enderror"
                    type="text" wire:model.lazy="name.en" placeholder="{{ __('admin/deliveriesPages.in English') }}">
                @error('name.en')
                    <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                        {{ $message }}</div>
                @enderror
            </div>
        </div>

        {{-- Contacts --}}
        <div class="grid grid-cols-12 gap-x-6 gap-y-2 items-center bg-gray-100 p-2 rounded text-center my-2">
            <label
                class="col-span-12 md:col-span-2 text-black font-bold m-0 text-center">{{ __('admin/deliveriesPages.Contacts') }}</label>

            {{-- Email --}}
            <div class="col-span-12 sm:col-span-8 sm:col-start-3 md:col-span-5">
                <input
                    class="py-1 w-full rounded text-center border-gray-300 focus:outline-gray-600 focus:ring-gray-300 focus:border-gray-300 @error('email') border-red-900 border-2 @enderror"
                    type="email" wire:model.lazy="email" placeholder="{{ __('admin/deliveriesPages.Email') }}"
                    dir="ltr">
                @error('email')
                    <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                        {{ $message }}</div>
                @enderror
            </div>

            {{-- Phone --}}
            <div class="col-span-12 md:col-span-5 grid grid-cols-6 gap-y-2">
                @foreach ($phones as $index => $phone)
                    {{-- Add remove button if their are more than one phone number --}}
                    @if (count($phones) > 1)
                        <div class="col-span-1">
                            <button
                                class=" bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded-full shadow btn btn-xs"
                                wire:click.prevent='removePhone({{ $index }})'
                                title="{{ __('admin/deliveriesPages.Delete') }}">
                                <span class="material-icons">
                                    close
                                </span>
                            </button>
                        </div>
                    @endif

                    {{-- phone input field --}}
                    <input
                        class="@if (count($phones) > 1) col-span-4 @else col-span-5 @endif py-1 w-full rounded text-center border-gray-300 focus:outline-gray-600 focus:ring-gray-300 focus:border-gray-300"
                        type="text" wire:model.lazy="phones.{{ $index }}.phone"
                        placeholder="{{ __('admin/deliveriesPages.Phone') }}" dir="ltr">

                    {{-- Default Radio Button --}}
                    <div class="col-span-1  flex flex-column justify-center items-center gap-1">
                        <label for="defaultPhone{{ $index }}"
                            class="text-xs text-black m-0 cursor-pointer">{{ __('admin/deliveriesPages.Default') }}</label>
                        <input type="radio" id="defaultPhone{{ $index }}" wire:model.lazy="defaultPhone"
                            value="{{ $index }}"
                            class="appearance-none checked:bg-secondary outline-none ring-0 cursor-pointer">
                    </div>
                @endforeach

                {{-- Error Messages --}}
                @error('phones.*.phone')
                    <div class="inline-block mt-2 col-span-6 bg-red-700 rounded text-white shadow px-3 py-1">
                        {{ $message }}</div>
                @enderror

                @error('defaultPhone')
                    <div class="inline-block mt-2 col-span-3 bg-red-700 rounded text-white shadow px-3 py-1">
                        {{ $message }}</div>
                @enderror


                {{-- Add New Phone Button --}}
                <button
                    class="col-start-3 col-span-2 bg-gray-600 hover:bg-gray-800 text-white font-bold py-2 px-4 rounded-xl shadow btn btn-sm text-center text-xs"
                    wire:click.prevent="addPhone" title="{{ __('admin/deliveriesPages.Add') }}">
                    <span class="material-icons rtl:ml-1 ltr:mr-1">
                        add
                    </span>
                    {{ __('admin/deliveriesPages.Add') }}</button>
            </div>
        </div>

        {{-- Active --}}
        <div class="grid grid-cols-12  items-center text-center my-2">
            <div
                class="col-span-6 md:col-span-4 grid grid-cols-6 md:grid-cols-4 gap-x-6 gap-y-1 items-center bg-red-100 p-2 rounded text-center">
                <label
                    class="col-span-6 md:col-span-2 text-black font-bold m-0 text-center">{{ __('admin/deliveriesPages.Active') }}</label>

                {{-- Active --}}
                <div class="col-span-6 md:col-span-2 text-center flex items-center justify-center">
                    <input class="appearance-none rounded-full checked:bg-primary outline-none ring-0 cursor-pointer"
                        type="checkbox" wire:model.lazy="active" value="1">
                </div>
                @error('active')
                    <div class="inline-block mt-2 col-span-6 md:col-span-4 bg-red-700 rounded text-white shadow px-3 py-1">
                        {{ $message }}</div>
                @enderror
            </div>
            <div class="col-span-6 md:col-span-8">
                {{-- Save and Add Zones --}}
                <button type="button" wire:click.prevent="save(false,true)"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-xl shadow btn btn-sm">{{ __("admin/deliveriesPages.Save and Add Company's Zones") }}</button>

            </div>
        </div>

        {{-- Buttons --}}
        <div class="flex flex-wrap gap-3 justify-around mt-4">
            {{-- Save and Back --}}
            <button type="button" wire:click.prevent="save"
                class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-xl shadow btn btn-sm">{{ __('admin/deliveriesPages.Save') }}</button>
            {{-- Save and New --}}
            <button type="button" wire:click.prevent="save(true)"
                class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-xl shadow btn btn-sm">{{ __('admin/deliveriesPages.Save and Add New Company') }}</button>
            {{-- Back --}}
            <a href="{{ route('admin.deliveries.index') }}"
                class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-xl shadow btn btn-sm">{{ __('admin/deliveriesPages.Back') }}</a>
        </div>

    </form>
</div>
