@extends('layouts.admin.admin', [
    'activeSection' => 'Customers',
    'activePage' => 'Add Customer',
    'titlePage' => __('admin/usersPages.Add Customer'),
])

@section('content')
    <div class="content">
        <div class="container-fluid">

            {{-- Hero Header --}}
            <div class="relative overflow-hidden rounded-2xl mb-6"
                style="background: linear-gradient(135deg, #1e293b 0%, #334155 50%, #475569 100%);">
                <div class="absolute inset-0 opacity-10">
                    <div class="absolute -top-10 -right-10 w-40 h-40 rounded-full"
                        style="background: radial-gradient(circle, rgba(255,255,255,0.3) 0%, transparent 70%);"></div>
                    <div class="absolute -bottom-10 -left-10 w-56 h-56 rounded-full"
                        style="background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, transparent 70%);"></div>
                </div>
                <div class="relative px-6 py-5">
                    {{-- Breadcrumb --}}
                    <nav aria-label="breadcrumb" role="navigation" class="mb-3">
                        <ol class="flex items-center gap-2 text-sm m-0 p-0" style="list-style: none;">
                            <li>
                                <a href="{{ route('admin.dashboard') }}"
                                    class="text-gray-300 hover:text-white transition-colors duration-200 flex items-center gap-1">
                                    <span class="material-icons text-sm">dashboard</span>
                                    {{ __('admin/usersPages.Dashboard') }}
                                </a>
                            </li>
                            <li class="text-gray-500">
                                <span class="material-icons text-xs">chevron_right</span>
                            </li>
                            <li>
                                <a href="{{ route('admin.customers.index') }}"
                                    class="text-gray-300 hover:text-white transition-colors duration-200 flex items-center gap-1">
                                    <span class="material-icons text-sm">people</span>
                                    {{ __('admin/usersPages.All Customers') }}
                                </a>
                            </li>
                            <li class="text-gray-500">
                                <span class="material-icons text-xs">chevron_right</span>
                            </li>
                            <li class="text-white font-semibold">
                                {{ __('admin/usersPages.Add Customer') }}
                            </li>
                        </ol>
                    </nav>

                    <div class="flex flex-wrap justify-between items-center">
                        <div>
                            <h1 class="text-2xl font-bold text-white m-0 flex items-center gap-3">
                                <span class="material-icons text-3xl p-2 rounded-xl"
                                    style="background: rgba(255,255,255,0.1);">person_add</span>
                                {{ __('admin/usersPages.Add Customer') }}
                            </h1>
                            <p class="text-gray-300 text-sm mt-1 m-0">
                                {{ __('admin/usersPages.Through this form you can add new customer') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <section class="row">
                <div class="col-md-12 static">
                    {{-- Card Body --}}
                    <div class="static rounded-2xl bg-white shadow-lg border border-gray-100 p-4 md:p-6">
                        {{-- Form Start --}}
                        @livewire('admin.customers.customer-form')
                        {{-- Form End --}}
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection
