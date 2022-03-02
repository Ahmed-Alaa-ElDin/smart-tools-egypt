<div>
    <div>
        <div class="flex flex-col">
            <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                    <div class="py-3 bg-white space-y-6">
                        <div class="grid grid-cols-2 gap-6 items-center">

                            {{-- Search Box --}}
                            <div class="col-span-1">
                                <div class="mt-1 flex rounded-md shadow-sm">
                                    <span
                                        class="inline-flex items-center px-3 ltr:rounded-l-md rtl:rounded-r-md border border-r-0 border-gray-300 bg-gray-50 text-center text-gray-500 text-sm">
                                        <i class="fa-solid fa-magnifying-glass"></i> </span>
                                    <input type="text" name="company-website" id="company-website" wire:model='search'
                                        class="focus:ring-primary focus:border-primary flex-1 block w-full rounded-none ltr:rounded-r-md rtl:rounded-l-md sm:text-sm border-gray-300"
                                        placeholder="{{ __('admin/usersPages.Search ...') }}">
                                </div>
                            </div>

                            {{-- Pagination Number --}}
                            <div class="form-inline col-span-1 justify-end my-2">
                                {{ __('pagination.Show') }} &nbsp;
                                <select wire:model='perPage' class="form-control w-auto px-3 cursor-pointer">
                                    <option>5</option>
                                    <option>10</option>
                                    <option>25</option>
                                    <option>50</option>
                                    <option>100</option>
                                </select>
                                &nbsp; {{ __('pagination.results') }}
                            </div>
                        </div>
                    </div>

                    <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200">
                            {{-- Data Table Header --}}
                            <thead class="bg-gray-50">
                                <tr>

                                    {{-- Name --}}
                                    <th wire:click="sortBy('name')" scope="col"
                                        class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer select-none">
                                        {{ __('admin/usersPages.Name') }} &nbsp;
                                        @include('partials._sort_icon', ['field' => 'name'])
                                    </th>

                                    {{-- Permissions Num --}}
                                    <th wire:click="sortBy('permissions_count')" scope="col"
                                        class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer select-none">
                                        <div class="min-w-max">
                                            {{ __('admin/usersPages.No. of Permissions') }} &nbsp;
                                            @include('partials._sort_icon', ['field' => 'permissions_count'])
                                        </div>
                                    </th>

                                    {{-- users Num --}}
                                    <th wire:click="sortBy('users_count')" scope="col"
                                        class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer select-none">
                                        <div class="min-w-max">
                                            {{ __('admin/usersPages.No. of Users') }} &nbsp;
                                            @include('partials._sort_icon', ['field' => 'users_count'])
                                        </div>
                                    </th>

                                    {{-- Manage --}}
                                    <th scope="col"
                                        class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider select-none">
                                        {{ __('admin/usersPages.Manage') }}
                                        <span class="sr-only">{{ __('admin/usersPages.Manage') }}</span>
                                    </th>
                                </tr>
                            </thead>

                            {{-- Data Table Body --}}
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($roles as $role)
                                    <tr>
                                        {{-- name --}}
                                        <td class="px-6 py-2 whitespace-nowrap">
                                            <div class="flex justify-center items-center">
                                                {{ $role->name }}
                                            </div>
                                        </td>

                                        {{-- Permissions Numbers --}}
                                        <td class="px-6 py-2 text-center whitespace-nowrap">
                                            <div class="text-sm text-gray-500">
                                                {{ $role->permissions_count }}
                                            </div>
                                        </td>

                                        {{-- User Numbers --}}
                                        <td class="px-6 py-2 text-center whitespace-nowrap">
                                            <div class="text-sm text-gray-500">
                                                {{ $role->users_count }}
                                            </div>
                                        </td>

                                        <td class="px-6 py-2 whitespace-nowrap text-center text-sm font-medium">

                                            {{-- Permissions List --}}
                                            @can("See Role's Permissions")
                                                <a href="{{ route('admin.roles.showPermissions', [$role->id]) }}"
                                                    title="{{ __('admin/usersPages.View permissions List') }}"
                                                    class="m-0"><i
                                                        class="fa-solid fa-key fa-fw p-2 text-white bg-view hover:bg-viewHover rounded"></i></a>
                                            @endcan

                                            {{-- Users List --}}
                                            @can("See Role's Users")
                                                <a href="{{ route('admin.roles.showUsers', [$role->id]) }}"
                                                    title="{{ __('admin/usersPages.View Users List') }}"
                                                    class="m-0"><i
                                                        class="fa-solid fa-user fa-fw p-2 text-white bg-view hover:bg-viewHover rounded"></i></a>
                                            @endcan

                                            {{-- Edit Button --}}
                                            @can('Edit Role')
                                                <a href="{{ route('admin.roles.edit', [$role->id]) }}"
                                                    title="{{ __('admin/usersPages.Edit') }}" class="m-0"><i
                                                        class="fa-solid fa-pen-to-square fa-fw p-2 text-white bg-edit hover:bg-editHover rounded"></i></a>
                                            @endcan

                                            {{-- Delete Button --}}
                                            @can('Delete Role')
                                                <a href="#" title="{{ __('admin/usersPages.Delete') }}"
                                                    wire:click.prevent="deleteConfirm({{ $role->id }})"
                                                    class="m-0"><i
                                                        class="fa-solid fa-trash-can fa-fw p-2 text-white bg-delete hover:bg-deleteHover rounded"></i></a>
                                            @endcan
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-center py-2 font-bold" colspan="6">
                                            {{ $search == ''? __('admin/usersPages.No data in this table'): __('admin/usersPages.No data available according to your search') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        {{ $roles->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
