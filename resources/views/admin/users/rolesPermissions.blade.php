@extends('layouts.admin.admin', ['activeSection' => 'Users', 'activePage' => '', 'titlePage' =>
__('admin/usersPages.Role\'s Permissions List')])

@section('content')
    <div class="content">
        <div class="container-fluid">
            {{-- Breadcrumb --}}
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb text-sm">
                    <li class="breadcrumb-item hover:text-primary"><a
                            href="{{ route('admin.dashboard') }}">{{ __('admin/usersPages.Dashboard') }}</a></li>
                    <li class="breadcrumb-item hover:text-primary"><a
                            href="{{ route('admin.users.index') }}">{{ __('admin/usersPages.All Users') }}</a></li>
                    <li class="breadcrumb-item hover:text-primary"><a
                            href="{{ route('admin.roles.index') }}">{{ __('admin/usersPages.Roles Management') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('admin/usersPages.Role\'s Permissions List') }}
                    </li>
                </ol>
            </nav>

            <section class="row">
                <div class="col-md-12">

                    {{-- Card --}}
                    <div class="card">

                        {{-- Card Head --}}
                        <div class="card-header card-header-primary">
                            <div class="row">
                                <div class="col-12 ltr:text-left rtl:text-right font-bold self-center text-gray-100">
                                    <p class=""> {{ __('admin/usersPages.Here you can view the list of role\'s permissions') }}</p>
                                </div>
                            </div>
                        </div>

                        {{-- Card Body --}}
                        <div class="card-body overflow-hidden">

                            {{-- List Start --}}
                            @livewire('admin.roles.roles-permissions-list',['role_id' => $id])

                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection
