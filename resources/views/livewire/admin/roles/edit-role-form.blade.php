<div>
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

            {{-- Permissions table --}}
            <div class="col-span-12 md:col-span-10 table-responsive">
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
                                        <label for="{{ $permission->name }}" class="cursor-pointer text-black m-0" >{{ $permission->name }}</label>
                                    </td>
                                    <td class="px-3 py-2 bg-red-100">
                                        <input type="checkbox" wire:model='selectedPermissions'
                                            value="{{ $permission->name }}" id="{{ $permission->name }}"
                                            class="appearance-none border-red-900 rounded-full checked:bg-primary outline-none ring-0 cursor-pointer">
                                    </td>
                                @else
                                <td class="px-3 py-2 bg-gray-200">
                                    <label for="{{ $permission->name }}" class="cursor-pointer text-black m-0" >{{ $permission->name }}</label>
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

        {{-- Buttons --}}
        <div class="flex flex-wrap gap-3 justify-around mt-4">
            {{-- Save   --}}
            <button wire:click.prevent="save"
                class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-xl shadow btn btn-sm">{{ __('admin/usersPages.Update') }}</button>
            {{-- Back --}}
            <a href="{{ route('admin.roles.index') }}"
                class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-xl shadow btn btn-sm">{{ __('admin/usersPages.Back') }}</a>
        </div>

    </form>
</div>
