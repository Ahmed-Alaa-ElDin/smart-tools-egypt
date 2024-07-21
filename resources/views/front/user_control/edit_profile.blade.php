@extends('layouts.front.user-control-layout', ['titlePage' => __("front/homePage.Edit Profile"), 'page' => 'edit'])

@section('sub-content')
    {{-- Form :: Start --}}
    @livewire('front.profile.profile-edit',['user_id' => $user->id])
    {{-- Form :: End --}}
@endsection

{{-- Extra Scripts --}}
@push('js')
@endpush
