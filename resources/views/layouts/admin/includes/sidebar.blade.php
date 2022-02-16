<div class="sidebar" data-color="red" data-background-color="white"
    data-image="{{ asset('assets/img/logos/smart-tools-logo-only-400.png') }}">
    <div class="logo">
        <a href="https://creative-tim.com/" class="simple-text logo-normal">
            <img src="{{ asset('assets/img/logos/smart-tools-logo-50.png') }}" alt="" width="50px">
            {{ 'Smart Tools' }}
        </a>
    </div>
    <div class="sidebar-wrapper">
        <ul class="nav">

            {{-- Dashboard --}}
            <li class="nav-item {{ $activeSection == 'dashboard' ? ' active' : '' }}">
                <a class="nav-link" href="{{ route('admin.dashboard') }}">
                    <i class="fa-solid fa-chart-line"></i>
                    <span>{{ __('admin/master.dashboard') }}</span>
                </a>
            </li>

            {{-- Users --}}
            <li class="nav-item {{ $activeSection == 'Users' ? ' active' : '' }}">
                <a class="nav-link" data-toggle="collapse" href="#users" aria-expanded="{{ $activeSection == 'Users' ? 'true' : 'false' }}">
                    <i class="fa-solid fa-user"></i>
                    <span>{{ __('admin/master.users') }}
                        <b class="caret"></b>
                    </span>
                </a>
                <div class="collapse {{ $activeSection == 'Users' ? ' show' : '' }}" id="users">
                    <ul class="nav">
                        <li class="nav-item {{ $activePage == 'All Users' ? ' active' : '' }}">
                            <a class="nav-link" href="{{ route('admin.users.index') }}">
                                <i class="fa-solid fa-user-group"></i>
                                <span>{{ __('admin/master.All Users') }}
                                </span> </a>
                        </li>
                        <li class="nav-item {{ $activePage == 'Add User' ? ' active' : '' }}">
                            <a class="nav-link" href="{{ route('admin.users.create') }}">
                                <i class="fa-solid fa-user-plus"></i>
                                <span>{{ __('admin/master.add user') }}
                                </span> </a>
                        </li>
                        <li class="nav-item {{ $activePage == 'soft-deleted-users' ? ' active' : '' }}">
                            <a class="nav-link" href="{{ route('admin.dashboard') }}">
                                <i class="fa-solid fa-users-slash"></i>
                                <span>{{ __('admin/master.Soft Deleted Users') }}
                                </span> </a>
                        </li>
                    </ul>
                </div>
            </li>


        </ul>
    </div>
</div>
