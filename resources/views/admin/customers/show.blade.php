@extends('layouts.admin.admin', [
    'activeSection' => 'Customers',
    'activePage' => 'All Customers',
    'titlePage' => __('admin/usersPages.User Profile'),
])

@section('content')
    <div class="content">
        <div class="container-fluid">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb text-sm">
                    <li class="breadcrumb-item hover:text-primary"><a
                            href="{{ route('admin.dashboard') }}">{{ __('admin/usersPages.Dashboard') }}</a></li>
                    <li class="breadcrumb-item hover:text-primary"><a
                            href="{{ route('admin.customers.index') }}">{{ __('admin/usersPages.All Customers') }}</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('admin/usersPages.User Profile') }}</li>
                </ol>
            </nav>

            @livewire('admin.customers.customer-profile', ['customer_id' => $customer->id])
        </div>
    </div>
@endsection
