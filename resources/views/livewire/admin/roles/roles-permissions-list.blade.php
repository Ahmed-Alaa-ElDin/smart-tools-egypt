<div class="grid grid-cols-12">
    <h2 class="h3 col-span-12 text-center m-3 font-bold">
        {{ $role->name }}
    </h2>

    <div class="table-responsive  col-span-12">
        <table class="w-100 table-bordered table-striped table-hover">
            <thead class="text-center">
                <tr>
                    <th class="bg-primary text-white px-2">{{ __('admin/usersPages.Permission') }}</th>
                    <th class="bg-primary text-white px-2">{{ __('admin/usersPages.Active') }}</th>
                    <th class="bg-secondary text-white px-2">{{ __('admin/usersPages.Permission') }}</th>
                    <th class="bg-secondary text-white px-2">{{ __('admin/usersPages.Active') }}</th>
                </tr>
            </thead>
            <tbody class="text-center">
                @forelse ($allPermissions as $permission)
                    @if ($loop->odd)
                        <tr>
                            <td class="px-3 py-2 bg-red-100">
                                {{ $permission->name }}
                            </td>
                            <td class="px-3 py-2 bg-red-100">
                                @if (in_array($permission->id, $rolesPermissions))
                                    <span class="text-success pt-1 font-bold material-icons">
                                        check
                                    </span>
                                @else
                                    <span class="text-danger pt-1 font-bold material-icons">
                                        close
                                    </span>
                                @endif
                            </td>
                        @else
                            <td class="px-3 py-2 bg-gray-100">{{ $permission->name }}
                            </td>
                            <td class="px-3 py-2 bg-gray-100">
                                @if (in_array($permission->id, $rolesPermissions))
                                    <span class="text-success pt-1 font-bold material-icons">
                                        check
                                    </span>
                                @else
                                    <span class="text-danger pt-1 font-bold material-icons">
                                        close
                                    </span>
                                @endif
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
    </div>
</div>
