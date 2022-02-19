<div>
    <form action="{{ route('admin.users.store') }}" method="post" enctype="multipart/form-data" class="">
        @csrf
        {{-- Image --}}
        <div
            class="grid grid-cols-12 gap-x-6 gap-y-2 items-center bg-gray-100 p-2 text-center my-2 justify-items-center	">
            @if ($path)
                <div class="col-span-12 text-center w-1/2 md:w-1/4 my-2">
                    <img src="{{ $path }}" class="rounded-xl">
                </div>
            @endif

            <label for="photo" class="col-span-12 md:col-span-2 text-black font-bold m-0 text-center">
                {{ __('admin/usersPages.Profile Image') }} </label>
            <input
                class="form-control block w-full md:w-50 px-2 py-1 text-sm font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none col-span-12 md:col-span-10 py-1 rounded text-center border-gray-300 focus:outline-gray-600 focus:ring-gray-300 focus:border-gray-300"
                id="photo" type="file" wire:model="photo">
                <div wire:loading wire:target="photo">Uploading...</div>


            @error('photo')
                <span class="col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">{{ $message }}</span>
            @enderror
        </div>

        {{-- First Name --}}
        <div class="grid grid-cols-12 gap-x-6 gap-y-2 items-center bg-red-100 p-2 rounded text-center my-2">
            <label
                class="col-span-12 md:col-span-2 text-black font-bold m-0 text-center">{{ __('admin/usersPages.First Name') }}</label>
            {{-- First Name Ar --}}
            <input
                class="first_input col-span-6 md:col-span-5 py-1 rounded text-center border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300"
                type="text" name="f_name[ar]" placeholder="{{ __('admin/usersPages.in Arabic') }}" autofocus>
            {{-- First Name En --}}
            <input
                class="col-span-6 md:col-span-5 py-1 rounded text-center border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300"
                type="text" name="f_name[en]" placeholder="{{ __('admin/usersPages.in English') }}">
        </div>

        {{-- Last Name --}}
        <div class="grid grid-cols-12 gap-x-6 gap-y-2 items-center bg-gray-100 p-2 rounded text-center">
            <label
                class="col-span-12 md:col-span-2 text-black font-bold m-0 text-center">{{ __('admin/usersPages.Last Name') }}</label>
            {{-- First Name Ar --}}
            <input
                class="col-span-6 md:col-span-5 py-1 rounded text-center border-gray-300 focus:outline-gray-600 focus:ring-gray-300 focus:border-gray-300"
                type="text" name="l_name[ar]" placeholder="{{ __('admin/usersPages.in Arabic') }}">
            {{-- First Name En --}}
            <input
                class="col-span-6 md:col-span-5 py-1 rounded text-center border-gray-300 focus:outline-gray-600 focus:ring-gray-300 focus:border-gray-300"
                type="text" name="l_name[en]" placeholder="{{ __('admin/usersPages.in English') }}">
        </div>

        {{-- Contacts --}}
        <div class="grid grid-cols-12 gap-x-6 gap-y-2 items-center bg-red-100 p-2 rounded text-center my-2">
            <label
                class="col-span-12 md:col-span-2 text-black font-bold m-0 text-center">{{ __('admin/usersPages.Contacts') }}</label>
            {{-- Email --}}
            <input
                class="col-span-6 md:col-span-5 py-1 rounded text-center border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300"
                type="email" name="email" placeholder="{{ __('admin/usersPages.Email') }}" dir="ltr">
            {{-- Phone --}}
            <input
                class="col-span-6 md:col-span-5 py-1 rounded text-center border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300"
                type="text" name="phone" placeholder="{{ __('admin/usersPages.Phone') }}" dir="ltr">
        </div>

        {{-- Other Information --}}
        <div class="grid grid-cols-12 gap-x-6 gap-y-2 items-center bg-gray-100 p-2 rounded text-center">
            <label
                class="col-span-12 lg:col-span-2 text-black font-bold m-0 text-center">{{ __('admin/usersPages.Other Information') }}</label>

            <div class="col-span-12 lg:col-span-10 grid grid-cols-12 gap-x-4 gap-y-2 items-center">
                {{-- Gender --}}
                <div
                    class="col-span-12 sm:col-span-6  xl:col-span-3 py-1 grid grid-cols-3 gap-x-4 gap-y-2 items-center">
                    <label for="gender"
                        class="col-span-1 select-none cursor-pointer text-black font-medium m-0">{{ __('admin/usersPages.Gender') }}</label>
                    <select
                        class="col-span-2 rounded w-full cursor-pointer py-1 text-center border-gray-300 focus:outline-gray-600 focus:ring-gray-300 focus:border-gray-300"
                        name="gender" id="gender">
                        <option value="0">{{ __('admin/usersPages.Male') }}</option>
                        <option value="1">{{ __('admin/usersPages.Female') }}</option>
                    </select>
                </div>

                {{-- Role --}}
                <div
                    class="col-span-12 sm:col-span-6  xl:col-span-3 py-1 grid grid-cols-3 gap-x-4 gap-y-2 items-center">
                    <label for="role"
                        class="col-span-1 select-none cursor-pointer text-black font-medium m-0">{{ __('admin/usersPages.Role') }}</label>
                    <select
                        class="col-span-2 rounded w-full cursor-pointer py-1 text-center border-gray-300 focus:outline-gray-600 focus:ring-gray-300 focus:border-gray-300"
                        name="role" id="role">
                        @forelse ($roles as $role)
                            <option value="{{ $role->id }}">
                                {{ __('admin/usersPages.' . $role->name) }}</option>
                        @empty
                            <option value="">{{ __('admin/usersPages.No Roles in the database') }}
                            </option>
                        @endforelse
                    </select>
                </div>

                {{-- Birth Date --}}
                <div
                    class="col-span-12 sm:col-span-12 xl:col-span-6 py-1 grid grid-cols-3 gap-x-4 gap-y-2 items-center">
                    <label for="birth_date"
                        class="col-span-1 select-none cursor-pointer text-black font-medium m-0">{{ __('admin/usersPages.Birth Date') }}</label>
                    <input
                        class="col-span-2 rounded w-full cursor-pointer py-1 text-center border-gray-300 focus:outline-gray-600 focus:ring-gray-300 focus:border-gray-300"
                        type="date" name="birth_date" id="birth_date">
                </div>
            </div>
        </div>

        {{-- Address --}}
        <div class="grid grid-cols-12 gap-x-4 gap-y-2 items-center bg-red-100 p-2 rounded text-center my-2">
            <label
                class="col-span-12 md:col-span-2 text-black font-bold m-0 text-center">{{ __('admin/usersPages.Address') }}</label>
            {{-- User Address Select Boxes --}}
            @livewire('admin.users.user-address-select-boxes')
        </div>

        {{-- Password Notification --}}
        <div class="flex flex-wrap gap-3 justify-around mt-4">
            <a type="button" href="{{ route('admin.users.store') }}"
                class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-xl shadow btn btn-sm">{{ __('admin/usersPages.Save') }}</a>
            <a type="button" href="{{ route('admin.users.store') }}"
                class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-xl shadow btn btn-sm">{{ __('admin/usersPages.Save and Add New User') }}</a>
            <a type="button" href="{{ route('admin.users.index') }}"
                class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-xl shadow btn btn-sm">{{ __('admin/usersPages.Back') }}</a>
        </div>

    </form>
</div>
