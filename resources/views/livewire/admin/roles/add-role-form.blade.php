<div>
    {{-- Loader : Start --}}
    <x-admin.waiting />
    {{-- Loader : End --}}

    <form enctype="multipart/form-data">
        @csrf

        {{-- Name --}}
        <div class="grid grid-cols-12 gap-x-6 gap-y-2 items-center bg-red-100 p-2 rounded text-center my-2">
            <label
                class="col-span-12 md:col-span-2 text-black font-bold m-0 text-center">{{ __('admin/usersPages.Name') }}</label>
            {{-- Name --}}
            <div class="col-span-12 sm:col-start-4 sm:col-span-6 md:col-start-auto md:col-span-5">
                <input
                    class="first_input py-1 w-full rounded text-center border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300 @error('f_name.ar') border-red-900 border-2 @enderror"
                    type="text" wire:model.lazy="name" placeholder="{{ __('admin/usersPages.Role Name') }}"
                    tabindex="1" required>
                @error('name')
                    <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                        {{ $message }}</div>
                @enderror
            </div>
        </div>

        {{-- Permissions --}}
        <div class="grid grid-cols-12 gap-x-6 gap-y-2 items-center bg-gray-100 p-2 rounded text-center">
            <label
                class="col-span-12 md:col-span-2 text-black font-bold m-0 text-center">{{ __('admin/usersPages.Permissions') }}</label>


            <div class="col-span-12 md:col-span-10">

                {{-- Select / Deselect All --}}
                <div class="flex justify-around items-center bg-gray-300 py-1 rounded-xl w-1/2 md:w-1/4 mx-auto mb-2">

                    {{-- Select All button --}}
                    <div class="text-gray-900 bg-white p-1 m-0 shadow rounded cursor-pointer btn" wire:click="selectAll"
                        title="{{ __('admin/deliveriesPages.Select All') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="img" width="1em" height="1em"
                            preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24" class="inline-block w-6 h-6">
                            <path fill="currentColor"
                                d="M20.496 5.627A2.25 2.25 0 0 1 22 7.75v10A4.25 4.25 0 0 1 17.75 22h-10a2.25 2.25 0 0 1-2.123-1.504l2.097.004H17.75a2.75 2.75 0 0 0 2.75-2.75v-10l-.004-.051V5.627ZM17.246 2a2.25 2.25 0 0 1 2.25 2.25v12.997a2.25 2.25 0 0 1-2.25 2.25H4.25A2.25 2.25 0 0 1 2 17.247V4.25A2.25 2.25 0 0 1 4.25 2h12.997Zm0 1.5H4.25a.75.75 0 0 0-.75.75v12.997c0 .414.336.75.75.75h12.997a.75.75 0 0 0 .75-.75V4.25a.75.75 0 0 0-.75-.75Zm-7.665 7.858L13.47 7.47a.75.75 0 0 1 1.133.976l-.073.084l-4.5 4.5a.75.75 0 0 1-1.056.004L8.9 12.95l-1.5-2a.75.75 0 0 1 1.127-.984l.073.084l.981 1.308L13.47 7.47l-3.89 3.888Z" />
                        </svg>
                    </div>

                    {{-- Deselect All button --}}
                    <div class="text-gray-900 bg-white p-1 m-0 shadow rounded cursor-pointer btn"
                        wire:click="deselectAll" title="{{ __('admin/deliveriesPages.Deselect All') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="img" width="1em" height="1em"
                            preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24" class="inline-block w-6 h-6">
                            <path fill="currentColor"
                                d="M20.496 5.627A2.25 2.25 0 0 1 22 7.75v10A4.25 4.25 0 0 1 17.75 22h-10a2.25 2.25 0 0 1-2.123-1.504l2.097.004H17.75a2.75 2.75 0 0 0 2.75-2.75v-10l-.004-.051V5.627ZM17.246 2a2.25 2.25 0 0 1 2.25 2.25v12.997a2.25 2.25 0 0 1-2.25 2.25H4.25A2.25 2.25 0 0 1 2 17.247V4.25A2.25 2.25 0 0 1 4.25 2h12.997Zm0 1.5H4.25a.75.75 0 0 0-.75.75v12.997c0 .414.336.75.75.75h12.997a.75.75 0 0 0 .75-.75V4.25a.75.75 0 0 0-.75-.75Z" />
                        </svg>
                    </div>

                </div>

                {{-- Permissions table --}}
                <div class="table-responsive rounded-xl overflow-hidden">
                    <table class="w-100 table-bordered table-striped table-hover">
                        <thead class="text-center">
                            <tr>
                                <th class="bg-primary text-white px-2">{{ __('admin/usersPages.Permission') }}</th>
                                <th class="bg-primary text-white px-2">{{ __('admin/usersPages.Activate') }}</th>
                                <th class="bg-secondary text-white px-2">{{ __('admin/usersPages.Permission') }}</th>
                                <th class="bg-secondary text-white px-2">{{ __('admin/usersPages.Activate') }}</th>
                            </tr>
                        </thead>
                        <tbody class="text-center">
                            @forelse ($allPermissions as $permission)
                                @if ($loop->odd)
                                    <tr>
                                        <td class="px-3 py-2 bg-red-100">
                                            <label for="{{ $permission->name }}"
                                                class="cursor-pointer text-black m-0">{{ $permission->name }}</label>
                                        </td>
                                        <td class="px-3 py-2 bg-red-100">
                                            <input type="checkbox" wire:model='selectedPermissions'
                                                value="{{ $permission->name }}" id="{{ $permission->name }}"
                                                class="appearance-none border-red-900 rounded-full checked:bg-primary outline-none ring-0 cursor-pointer">
                                        </td>
                                    @else
                                        <td class="px-3 py-2 bg-gray-200">
                                            <label for="{{ $permission->name }}"
                                                class="cursor-pointer text-black m-0">{{ $permission->name }}</label>
                                        </td>
                                        <td class="px-3 py-2 bg-gray-200">
                                            <input type="checkbox" wire:model='selectedPermissions'
                                                value="{{ $permission->name }}" id="{{ $permission->name }}"
                                                class="appearance-none border-gray-600 rounded-full checked:bg-secondary outline-none ring-0 cursor-pointer">
                                        </td>
                                    </tr>
                                @endif
                            @empty
                                <tr>
                                    <td class="text-center py-2 font-bold" colspan="4">
                                        {{ __('admin/usersPages.No permissions in the database') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    @error('selectedPermissions')
                        <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                            {{ $message }}</div>
                    @enderror
                    @error('selectedPermissions.*')
                        <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                            {{ $message }}</div>
                    @enderror

                </div>
            </div>
        </div>

        {{-- Buttons --}}
        <div class="flex flex-wrap gap-3 justify-around mt-4">
            {{-- Save and Back --}}
            <button type="button" wire:click.prevent="save"
                class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-xl shadow btn btn-sm">{{ __('admin/usersPages.Save') }}</button>
            {{-- Save and New --}}
            <button type="button" wire:click.prevent="save('true')"
                class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-xl shadow btn btn-sm">{{ __('admin/usersPages.Save and Add New Role') }}</button>
            {{-- Back --}}
            <a href="{{ route('admin.roles.index') }}"
                class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-xl shadow btn btn-sm">{{ __('admin/usersPages.Back') }}</a>
        </div>

    </form>
</div>
