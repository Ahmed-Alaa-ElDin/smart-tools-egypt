<div>
    <div class="flex flex-col">
        <div class="py-3 bg-white space-y-6">
            <div class="flex justify-between gap-6 items-center">


                {{-- Search Box --}}
                <div class="col-span-1">
                    <div class="mt-1 flex rounded-md shadow-sm">
                        <span
                            class="inline-flex items-center px-3 ltr:rounded-l-md rtl:rounded-r-md border border-r-0 border-gray-300 bg-gray-50 text-center text-gray-500 text-sm">
                            <span class="material-icons">
                                search
                            </span> </span>
                        <input type="text" name="company-website" id="company-website" wire:model='search'
                            class="focus:ring-primary focus:border-primary flex-1 block w-full rounded-none ltr:rounded-r-md rtl:rounded-l-md sm:text-sm border-gray-300"
                            placeholder="{{ __('admin/usersPages.Search ...') }}">
                    </div>
                </div>

                {{-- Download --}}
                <div class="form-inline col-span-1 justify-center">
                    <div class="flex justify-center">
                        <button class="btn btn-success dropdown-toggle btn-round btn-sm text-white font-bold "
                            type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="material-icons">
                                file_download
                            </span> &nbsp; {{ __('admin/usersPages.Export Users') }}
                            &nbsp;</button>
                        <div class="dropdown-menu">
                            <a href="{{ route('admin.users.exportExcel') }}"
                                class="dropdown-item dropdown-item-excel justify-center font-bold hover:bg-green-600 focus:bg-green-600">
                                <span class="material-icons">
                                    file_present
                                </span> &nbsp;&nbsp;
                                {{ __('admin/usersPages.download all excel') }}</a>
                            <a href="{{ route('admin.users.exportPDF') }}"
                                class="dropdown-item dropdown-item-pdf justify-center font-bold hover:bg-red-600 focus:bg-red-600">
                                <span class="material-icons">
                                    picture_as_pdf
                                </span>
                                &nbsp;&nbsp;
                                {{ __('admin/usersPages.download all pdf') }}</a>
                        </div>
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
        <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">

                <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        {{-- Datatable Header --}}
                        <thead class="bg-gray-50">
                            <tr>

                                {{-- Name --}}
                                <th wire:click="sortBy('f_name')" scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer select-none">
                                    {{ __('admin/usersPages.Name') }} &nbsp;
                                    @include('partials._sort_icon', [
                                        'field' => 'f_name->' . session('locale'),
                                    ])
                                </th>

                                {{-- Email --}}
                                <th wire:click="sortBy('email')" scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer select-none">
                                    {{ __('admin/usersPages.Contacts') }}&nbsp;
                                    @include('partials._sort_icon', ['field' => 'email'])
                                </th>

                                {{-- Balance --}}
                                <th wire:click="sortBy('balance')" scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer select-none">
                                    <div class="min-w-max">
                                        {{ __('admin/usersPages.Balance') }}&nbsp;
                                        @include('partials._sort_icon', [
                                            'field' => 'balance',
                                        ])
                                    </div>
                                </th>

                                {{-- Visits Num --}}
                                <th wire:click="sortBy('visit_num')" scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer select-none">
                                    <div class="min-w-max">
                                        {{ __('admin/usersPages.Visits No.') }} &nbsp;
                                        @include('partials._sort_icon', [
                                            'field' => 'visit_num',
                                        ])
                                    </div>
                                </th>

                                {{-- Role --}}
                                <th scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider select-none">
                                    {{ __('admin/usersPages.Role') }}
                                </th>

                                {{-- Manage --}}
                                <th scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider select-none">
                                    {{ __('admin/usersPages.Manage') }}
                                    <span class="sr-only">{{ __('admin/usersPages.Manage') }}</span>
                                </th>
                            </tr>
                        </thead>

                        {{-- Datatable Body --}}
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($users as $user)
                                {{-- photo & name --}}
                                <tr>
                                    <td class="px-6 py-2 whitespace-nowrap">
                                        <div class="flex items-center content-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                @if ($user->profile_photo_path)
                                                    <img class="h-10 w-10 rounded-full"
                                                        src="{{ asset('storage/images/profiles/cropped200/' . $user->profile_photo_path) }}"
                                                        alt="{{ $user->f_name . ' ' . $user->l_name . 'profile image' }}">
                                                @else
                                                    <div
                                                        class="h-10 w-10 rounded-full text-white bg-secondary flex justify-center items-center">
                                                        <span class="material-icons">
                                                            account_circle
                                                        </span>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="ltr:ml-4 rtl:mr-4">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $user->f_name . ' ' . $user->l_name }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-2 text-center whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $user->email }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ $user->phones->where('default', 1)->first() ? $user->phones->where('default', 1)->first()->phone : '' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-2 text-center whitespace-nowrap">
                                        {{ $user->balance . ' ' . __('admin/usersPages.LE') }}
                                    </td>
                                    <td class="px-6 py-2 text-center whitespace-nowrap">
                                        {{ $user->visit_num }}
                                    </td>
                                    <td class="px-6 py-2 text-center whitespace-nowrap text-sm text-gray-500">
                                        {{ $user->roles->first() ? $user->roles->first()->name : __('N/A') }}
                                    </td>
                                    <td class="px-6 py-2 whitespace-nowrap text-center text-sm font-medium">

                                        {{-- User Details --}}
                                        @can("See User's Details")
                                            <a href="{{ route('admin.users.show', ['user' => $user->id]) }}"
                                                title="{{ __('admin/usersPages.View') }}" class="m-0">
                                                <span
                                                    class="material-icons p-1 text-lg w-9 h-9 text-white bg-view hover:bg-viewHover rounded">
                                                    visibility
                                                </span>
                                            </a>
                                        @endcan

                                        {{-- Edit Button --}}
                                        @can('Edit User')
                                            <a href="{{ route('admin.users.edit', ['user' => $user->id]) }}"
                                                title="{{ __('admin/usersPages.Edit') }}" class="m-0">
                                                <span
                                                    class="material-icons p-1 text-lg w-9 h-9 text-white bg-edit hover:bg-editHover rounded">
                                                    edit
                                                </span>
                                            </a>
                                        @endcan

                                        {{-- Add Points Button --}}
                                        <a href="#" wire:click.prevent="addPointsForm({{ $user->id }})"
                                            title="{{ __('admin/usersPages.Add Points') }}" class="m-0">
                                            <span
                                                class="material-icons p-1 text-lg w-9 h-9 text-white bg-green-600 hover:bg-green-800 rounded">
                                                <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="img"
                                                    class="inline-block font-bold" width="1em" height="1em"
                                                    preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24">
                                                    <path fill="currentColor"
                                                        d="M20 7h-1.209A4.92 4.92 0 0 0 19 5.5C19 3.57 17.43 2 15.5 2c-1.622 0-2.705 1.482-3.404 3.085C11.407 3.57 10.269 2 8.5 2C6.57 2 5 3.57 5 5.5c0 .596.079 1.089.209 1.5H4c-1.103 0-2 .897-2 2v2c0 1.103.897 2 2 2v7c0 1.103.897 2 2 2h12c1.103 0 2-.897 2-2v-7c1.103 0 2-.897 2-2V9c0-1.103-.897-2-2-2zm-4.5-3c.827 0 1.5.673 1.5 1.5C17 7 16.374 7 16 7h-2.478c.511-1.576 1.253-3 1.978-3zM7 5.5C7 4.673 7.673 4 8.5 4c.888 0 1.714 1.525 2.198 3H8c-.374 0-1 0-1-1.5zM4 9h7v2H4V9zm2 11v-7h5v7H6zm12 0h-5v-7h5v7zm-5-9V9.085L13.017 9H20l.001 2H13z" />
                                                </svg>
                                            </span>
                                        </a>

                                        {{-- Edit Role Button --}}
                                        @can("Edit User's Role")
                                            <a href="#" title="{{ __('admin/usersPages.Role') }}"
                                                wire:click.prevent="editRolesSelect({{ $user->id }})"
                                                class="m-0">
                                                <span
                                                    class="material-icons p-1 text-lg w-9 h-9 text-white bg-role hover:bg-roleHover rounded">
                                                    key
                                                </span>
                                            </a>
                                        @endcan


                                        {{-- Deleted Button --}}
                                        @can('Deleted User')
                                            <a href="#" title="{{ __('admin/usersPages.Delete') }}"
                                                wire:click.prevent="deleteConfirm({{ $user->id }})"
                                                class="m-0">
                                                <span
                                                    class="material-icons p-1 text-lg w-9 h-9 text-white bg-delete hover:bg-deleteHover rounded">
                                                    delete
                                                </span>
                                            </a>
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
            </div>
        </div>

        <div class="mt-4">
            {{ $users->links() }}
        </div>
    </div>
</div>
