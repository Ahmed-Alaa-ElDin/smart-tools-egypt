@extends('layouts.front.user_control_layout', ['titlePage' => __("front/homePage.Edit Profile"), 'page' => 'edit'])

@section('sub-content')
    {{-- Form :: Start --}}
    @livewire('front.profile.profile-edit',['user_id' => $user->id])
    {{-- Form :: End --}}
@endsection

{{-- Extra Scripts --}}
@push('js')
    <script src="{{ asset('assets/js/plugins/flowbite/flowbite.js') }}"></script>

    <script>
        $(document).ready(function() {

        });
    </script>
@endpush
