<div class="sidebar" data-color="red" data-background-color="white"
    data-image="{{ asset('assets/admin/img/smart-tools-logo-100.png') }}">
    <div class="logo">
        <a href="https://creative-tim.com/" class="simple-text logo-normal">
            <img src="{{ asset('assets/admin/img/smart-tools-logo-50.png') }}" alt="" width="50px">
            {{ 'Smart Tools' }}
        </a>
    </div>
    <div class="sidebar-wrapper">
        <ul class="nav">
            <li class="nav-item {{ $activeSection == 'dashboard' ? ' active' : '' }}">
                <a class="nav-link" href="{{ route('admin.dashboard') }}">
                    <i class="fa-solid fa-chart-line"></i>
                    <span>{{ __('admin/master.dashboard') }}</span>
                </a>
            </li>
            <li class="nav-item active {{ $activeSection ? ' active' : '' }}">
                <a class="nav-link" data-toggle="collapse" href="#users" aria-expanded="true">
                    <i class="fa-solid fa-user"></i>
                    <span>{{ __('admin/master.users') }}
                        <b class="caret"></b>
                    </span>
                </a>
                <div class="collapse show" id="users">
                    <ul class="nav">
                        <li class="nav-item {{ $activePage == 'all-users' ? ' active' : '' }}">
                            <a class="nav-link" href="{{ route('admin.dashboard') }}">
                                <i class="fa-solid fa-user-group"></i>
                                <span>{{ __('admin/master.all users') }}
                                </span> </a>
                        </li>
                        <li class="nav-item {{ $activePage == 'add-user' ? ' active' : '' }}">
                            <a class="nav-link" href="{{ route('admin.dashboard') }}">
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
