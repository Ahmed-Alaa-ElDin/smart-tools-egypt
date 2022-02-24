<div>
    <div class="flex flex-col">
        <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                <div class="py-3 bg-white space-y-6">
                    <div class="grid grid-cols-3 gap-6 items-center">


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

                        {{-- Download --}}
                        <div class="form-inline col-span-1 justify-center">
                            <div class="flex justify-center">
                                <button class="btn btn-success dropdown-toggle btn-round btn-sm text-white font-bold "
                                    type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-sync-alt"></i> &nbsp; {{ __('admin/usersPages.Export Users') }}
                                    &nbsp;</button>
                                <div class="dropdown-menu">
                                    <a href="{{ route('admin.users.exportExcel') }}"
                                        class="dropdown-item dropdown-item-excel justify-center font-bold hover:bg-green-600 focus:bg-green-600"><i
                                            class="fas fa-file-excel"></i>
                                        &nbsp;&nbsp;
                                        {{ __('admin/usersPages.download all excel') }}</a>
                                    <a href="{{ route('admin.users.exportPDF') }}"
                                        class="dropdown-item dropdown-item-pdf justify-center font-bold hover:bg-red-600 focus:bg-red-600"><i
                                            class="fas fa-file-pdf"></i>
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

                <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        {{-- Data Table Header --}}
                        <thead class="bg-gray-50">
                            <tr>

                                {{-- Name --}}
                                <th wire:click="sortBy('f_name')" scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer select-none">
                                    {{ __('admin/usersPages.Name') }} &nbsp;
                                    @include('partials._sort_icon', ['field' => 'f_name'])
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
                                        @include('partials._sort_icon', ['field' => 'balance'])
                                    </div>
                                </th>

                                {{-- Visits Num --}}
                                <th wire:click="sortBy('visit_num')" scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer select-none">
                                    <div class="min-w-max">
                                        {{ __('admin/usersPages.Visits No.') }} &nbsp;
                                        @include('partials._sort_icon', ['field' => 'visit_num'])
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

                        {{-- Data Table Body --}}
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
                                                        <i class="fa-regular fa-user fa-fw"></i>
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
                                            {{ $user->phones->first() ? $user->phones->first()->phone : '' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-2 text-center whitespace-nowrap">
                                        {{ $user->balance . ' ' . __('admin/usersPages.LE') }}
                                    </td>
                                    <td class="px-6 py-2 text-center whitespace-nowrap">
                                        {{ $user->visit_num }}
                                    </td>
                                    <td class="px-6 py-2 text-center whitespace-nowrap text-sm text-gray-500">
                                        {{ $user->roles->first() ? __('admin/usersPages.' . $user->roles->first()->name) : __('N/A') }}
                                    </td>
                                    <td class="px-6 py-2 whitespace-nowrap text-center text-sm font-medium">
                                        <a href="#" title="{{ __('admin/usersPages.View') }}"
                                            class="m-0"><i
                                                class="fa-solid fa-eye fa-fw p-2 text-white bg-view hover:bg-viewHover rounded"></i></a>
                                        <a href="{{ route('admin.users.edit', ['user' => $user->id]) }}"
                                            title="{{ __('admin/usersPages.Edit') }}" class="m-0"><i
                                                class="fa-solid fa-pen-to-square fa-fw p-2 text-white bg-edit hover:bg-editHover rounded"></i></a>
                                        <a href="#" title="{{ __('admin/usersPages.Role') }}"
                                            class="m-0"><i
                                                class="fa-solid fa-key fa-fw p-2 text-white bg-role hover:bg-roleHover rounded"></i></a>
                                        <a href="#" title="{{ __('admin/usersPages.Delete') }}"
                                            class="m-0"><i
                                                class="fa-solid fa-trash-can fa-fw p-2 text-white bg-delete hover:bg-deleteHover rounded"></i></a>
                                    </td>
                                </tr>

                            @empty
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
