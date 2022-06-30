@extends('layouts.front.site', ['titlePage' => __('front/homePage.Shopping Cart')])

@section('content')
    <div class="container p-4">
        Product
    </div>
@endsection

{{-- Extra Scripts --}}
@push('js')
    <script>
        $(document).ready(function() {

        });
    </script>
@endpush
