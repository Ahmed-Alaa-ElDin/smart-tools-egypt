<div>
    <form action="{{ route('admin.users.store') }}" method="post">
        @csrf

        {{-- First Name --}}
        <div class="grid grid-cols-12 gap-x-6 gap-y-2 items-center bg-red-100 p-2 rounded text-center my-2">
            <label
                class="col-span-12 md:col-span-2 text-black font-bold m-0 text-center">{{ __('admin/usersPages.First Name') }}</label>
            {{-- First Name Ar --}}
            <input
                class="col-span-6 md:col-span-5 py-1 rounded text-center border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300"
                type="text" name="f_name[ar]" placeholder="{{ __('admin/usersPages.in Arabic') }}">
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
                type="email" name="email" placeholder="{{ __('admin/usersPages.Email') }}">
            {{-- Phone --}}
            <input
                class="col-span-6 md:col-span-5 py-1 rounded text-center border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300"
                type="text" name="phone" placeholder="{{ __('admin/usersPages.Phone') }}">
        </div>

        {{-- Other Information --}}
        <div class="grid grid-cols-12 gap-x-6 gap-y-2 items-center bg-gray-100 p-2 rounded text-center">
            <label
                class="col-span-12 lg:col-span-2 text-black font-bold m-0 text-center">{{ __('admin/usersPages.Other Information') }}</label>

            <div class="col-span-12 lg:col-span-10 grid grid-cols-12 gap-x-4 gap-y-2 items-center">
                {{-- Gender --}}
                <div class="col-span-12 sm:col-span-6  xl:col-span-3 py-1 grid grid-cols-3 gap-x-4 gap-y-2 items-center">
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

        {{-- Photo --}}
        {{-- Password Notification --}}
        <button type="submit">dasdasd</button>

    </form>
</div>
