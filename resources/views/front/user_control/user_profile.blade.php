@extends('layouts.front.user_control_layout', ['titlePage' => __("front/homePage.User's Profile"), 'page' => 'dashbaord'])

@section('sub-content')
    {{-- User :: Left :: Start --}}
    <div class="col-span-12 lg:col-span-4 flex flex-col gap-4">
        {{-- User Image, Name & Contacts : Start --}}
        <div class="flex flex-col gap-4 bg-white rounded-xl shadow-lg p-8 overflow-hidden">
            {{-- User Image :: Start --}}
            @if ($user->profile_photo_path)
                <div class="relative flex justify-center items-center max-w-max mx-auto mb-4">
                    <img src="{{ asset('storage/images/profiles/original/' . $user->profile_photo_path) }}"
                        alt="{{ $user->f_name . ' ' . $user->l_name }}" class="w-56 h-56 m-auto rounded-circle shadow">
                    <a href="{{ route('front.profile.edit', $user->id) }}"
                        class="absolute block bottom-2 right-2 w-12 h-12 rounded-circle bg-primary flex justify-center items-center">
                        <span class="material-icons text-white font-bold">
                            edit
                        </span>
                    </a>
                    @if ($user->email_verified_at)
                        <div
                            class="absolute top-2 right-2 w-12 h-12 rounded-circle shadow bg-white flex justify-center items-center">
                            <span class="material-icons text-5xl text-success font-bold">
                                verified
                            </span>
                        </div>
                    @endif
                </div>
            @else
                <div
                    class="relative flex justify-center items-center bg-gray-100 w-56 h-56 max-w-max mx-auto mb-4 rounded-circle">
                    <span class="block material-icons text-[200px] ">
                        person
                    </span>
                    <a href="{{ route('front.profile.edit', $user->id) }}"
                        class="absolute block bottom-2 right-2 w-12 h-12 rounded-circle bg-primary flex justify-center items-center">
                        <span class="material-icons text-white font-bold">
                            add
                        </span>
                    </a>
                    @if ($user->email_verified_at)
                        <div
                            class="absolute top-2 right-2 w-12 h-12 rounded-circle shadow bg-white flex justify-center items-center">
                            <span class="material-icons text-5xl text-success font-bold">
                                verified
                            </span>
                        </div>
                    @endif
                </div>
            @endif
            {{-- User Image :: End --}}

            {{-- User Name :: Start --}}
            <div class="flex justify-center gap-2 items-center max-w-max m-auto">
                <h3 class="text-2xl font-bold text-center">
                    {{ $user->f_name . ' ' . $user->l_name }}
                </h3>

                @if (!$user->gender)
                    <span
                        class="material-icons w-7 h-7 flex justify-center items-center rounded-circle bg-male text-white shadow">
                        male
                    </span>
                @else
                    <span
                        class="material-icons w-7 h-7 flex justify-center items-center rounded-circle bg-female text-white shadow">
                        female
                    </span>
                @endif

            </div>
            {{-- User Name :: End --}}

            {{-- User Email :: Start --}}
            <div class="flex justify-center items-center max-w-max m-auto">
                @if ($user->email)
                    <p class="text-lg font-bold text-center text-gray-700">
                        {{ $user->email }}
                    </p>
                @else
                    <span class="font-bold text-center text-danger">
                        {{ __('front/homePage.No Email Available') }}
                    </span>
                @endif
            </div>
            {{-- User Email :: End --}}

            {{-- User Phone :: Start --}}
            <div class="flex flex-col justify-center items-center max-w-max m-auto">
                @forelse ($user->phones as $phone)
                    <p class="text-lg font-bold text-center text-gray-700 flex items-center">
                        <span>
                            {{ $phone->phone }}
                        </span>
                        @if ($phone->default)
                            <span class="text-xs font-bold text-success">
                                &nbsp; ({{ __('front/homePage.Default') }})
                            </span>
                        @endif
                    </p>
                @empty
                    <span class="font-bold text-center text-danger">
                        {{ __('front/homePage.No Phone Number Available') }}
                    </span>
                @endforelse
            </div>
            {{-- User Phone :: End --}}

            {{-- User Birth Date :: Start --}}
            @if ($user->birth_date)
                <div class="flex justify-center items-center max-w-max m-auto">
                    <p class="text-lg font-bold text-center text-gray-700">
                        {{ $user->birth_date }}
                    </p>
                </div>
            @endif
            {{-- User Birth Date :: End --}}

        </div>
        {{-- User Image, Name & Contacts : End --}}

    </div>
    {{-- User :: Left :: End --}}

    {{-- User :: Right :: Start --}}
    <div class="col-span-12 lg:col-span-8 grid grid-cols-12 gap-4">
        {{-- User Addresses :: Start --}}
        <div class="col-span-12 md:order-2 flex flex-col gap-4 bg-white rounded-xl shadow-lg p-8">
            {{-- User Addresses :: Start --}}
            <div class="flex justify-center items-center max-w-max m-auto">
                <h3 class="text-2xl font-bold text-center">
                    {{ __('front/homePage.Addresses') }}
                </h3>
            </div>
            <div class="flex justify-center items-center m-auto w-full">
                <div class="grid grid-cols-2 gap-4 w-full">
                    @forelse ($user->addresses as $address)
                        <div
                            class="col-span-2 lg:col-span-1 self-center shadow-inner hover:shadow @if ($address->default) bg-green-100 @else bg-gray-100 @endif rounded-xl flex flex-col items-center justify-center gap-2 w-full p-2">
                            @if ($address->default)
                                <span class="text-xs font-bold text-successDark">
                                    {{ __('front/homePage.Default Shipping Address') }}
                                </span>
                            @endif
                            <div class="flex justify-center items-center max-w-max m-auto">
                                <h3 class="text-2xl font-bold text-center">
                                    {{ $address->address }}
                                </h3>
                            </div>
                            <div class="flex items-center justify-center gap-2">
                                <p class="text-lg font-bold text-center text-gray-700">
                                    {{ $address->country->name }}
                                </p>
                                <span>
                                    -
                                </span>
                                <p class="text-lg font-bold text-center text-gray-700">
                                    {{ $address->governorate->name }}
                                </p>
                                <span>
                                    -
                                </span>
                                <p class="text-lg font-bold text-center text-gray-700">
                                    {{ $address->city->name }}
                                </p>
                            </div>
                            @if ($address->details)
                                <p class="text-sm font-bold text-gray-600">
                                    {{ $address->details }}
                                </p>
                            @endif
                        </div>
                    @empty
                        <span class="col-span-2 font-bold text-center text-danger">
                            {{ __('front/homePage.No Addresses Available') }}
                        </span>
                    @endforelse
                </div>
            </div>
            {{-- User Addresses :: End --}}
        </div>
        {{-- User Addresses :: End --}}

        {{-- Grid :: Start --}}
        <div class="col-span-12 grid grid-cols-12 gap-4">

            {{-- Balance :: Start --}}
            <div
                class="relative col-span-6  w-full bg-gradient-to-br from-primaryLight to-primaryDark h-36 rounded-xl overflow-hidden shadow-xl">
                <div class="flex flex-col justify-start items-start gap-2 h-full text-white p-4">
                    <p class="text-4xl font-bold text-center flex gap-2">
                        <span dir="ltr">
                            {{ number_format($user->balance,2,'.','\'') }}
                        </span>
                        <span class="text-sm">
                            {{ __('front/homePage.EGP') }}
                        </span>
                    </p>
                    <h3 class="text-xs font-bold text-center text-gray-100">
                        {{ __('front/homePage.Balance') }}
                    </h3>
                </div>
                <svg class="absolute bottom-0 left-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
                    <path fill="rgba(255,255,255,0.3)" fill-opacity="1"
                        d="M0,192L30,208C60,224,120,256,180,245.3C240,235,300,181,360,144C420,107,480,85,540,96C600,107,660,149,720,154.7C780,160,840,128,900,117.3C960,107,1020,117,1080,112C1140,107,1200,85,1260,74.7C1320,64,1380,64,1410,64L1440,64L1440,320L1410,320C1380,320,1320,320,1260,320C1200,320,1140,320,1080,320C1020,320,960,320,900,320C840,320,780,320,720,320C660,320,600,320,540,320C480,320,420,320,360,320C300,320,240,320,180,320C120,320,60,320,30,320L0,320Z">
                    </path>
                </svg>
            </div>
            {{-- Balance :: End --}}

            {{-- Points :: Start --}}
            <div
                class="relative col-span-6  w-full bg-gradient-to-br from-green-500 to-green-700 h-36 rounded-xl overflow-hidden shadow-xl">
                <div class="flex flex-col justify-start items-start gap-2 h-full text-white p-4">
                    <p class="text-4xl font-bold text-center flex gap-2">
                        <span dir="ltr">
                            {{ number_format($user->validPoints,0,',','\'') }}
                        </span>
                        <span class="text-sm">
                            {{ trans_choice('front/homePage.Point/Points', $user->points) }}
                        </span>
                    </p>
                    <h3 class="text-xs font-bold text-center text-gray-100">
                        {{ __('front/homePage.Points') }}
                    </h3>
                </div>
                <svg class="absolute bottom-0 left-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
                    <path fill="rgba(255,255,255,0.3)" fill-opacity="1"
                        d="M0,128L34.3,112C68.6,96,137,64,206,96C274.3,128,343,224,411,250.7C480,277,549,235,617,213.3C685.7,192,754,192,823,181.3C891.4,171,960,149,1029,117.3C1097.1,85,1166,43,1234,58.7C1302.9,75,1371,149,1406,186.7L1440,224L1440,320L1405.7,320C1371.4,320,1303,320,1234,320C1165.7,320,1097,320,1029,320C960,320,891,320,823,320C754.3,320,686,320,617,320C548.6,320,480,320,411,320C342.9,320,274,320,206,320C137.1,320,69,320,34,320L0,320Z">
                    </path>
                </svg>
            </div>
            {{-- Points :: End --}}

            {{-- Current Orders :: Start --}}
            <div
                class="relative col-span-6  w-full bg-gradient-to-br from-yellow-400 to-yellow-600 h-36 rounded-xl overflow-hidden shadow-xl">
                <div class="flex flex-col justify-start items-start gap-2 h-full text-white p-4">
                    <p class="text-4xl font-bold text-center flex gap-2">
                        <span>
                            {{ $user->orders->whereNotIn('status_id',[9,45,49,50,100,101,102,104])->count() }}
                        </span>
                        <span class="text-sm">
                            {{ trans_choice('front/homePage.Order/Orders', $user->orders->whereNotIn('status_id',[9,45,49,50,100,101,102,104])->count()) }}
                        </span>
                    </p>
                    <h3 class="text-xs font-bold text-center text-gray-100">
                        {{ __('front/homePage.Current Orders') }}
                    </h3>
                </div>
                <svg class="absolute bottom-0 left-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
                    <path fill="rgba(255,255,255,0.3)" fill-opacity="1"
                        d="M0,192L26.7,192C53.3,192,107,192,160,202.7C213.3,213,267,235,320,218.7C373.3,203,427,149,480,117.3C533.3,85,587,75,640,90.7C693.3,107,747,149,800,149.3C853.3,149,907,107,960,112C1013.3,117,1067,171,1120,202.7C1173.3,235,1227,245,1280,213.3C1333.3,181,1387,107,1413,69.3L1440,32L1440,320L1413.3,320C1386.7,320,1333,320,1280,320C1226.7,320,1173,320,1120,320C1066.7,320,1013,320,960,320C906.7,320,853,320,800,320C746.7,320,693,320,640,320C586.7,320,533,320,480,320C426.7,320,373,320,320,320C266.7,320,213,320,160,320C106.7,320,53,320,27,320L0,320Z">
                    </path>
                </svg>
            </div>
            {{-- Current Orders :: End --}}


            {{-- Old Orders :: Start --}}
            <div
                class="relative col-span-6  w-full bg-gradient-to-br from-secondaryLight to-secondaryDark h-36 rounded-xl overflow-hidden shadow-xl">
                <div class="flex flex-col justify-start items-start gap-2 h-full text-white p-4">
                    <p class="text-4xl font-bold text-center flex gap-2">
                        <span>
                            {{ $user->orders->count() }}
                        </span>
                        <span class="text-sm">
                            {{ trans_choice('front/homePage.Order/Orders', $user->orders->count()) }}
                        </span>
                    </p>
                    <h3 class="text-xs font-bold text-center text-gray-100">
                        {{ __('front/homePage.All Orders') }}
                    </h3>
                </div>
                <svg class="absolute bottom-0 left-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
                    <path fill="rgba(255,255,255,0.3)" fill-opacity="1"
                        d="M0,192L30,208C60,224,120,256,180,245.3C240,235,300,181,360,144C420,107,480,85,540,96C600,107,660,149,720,154.7C780,160,840,128,900,117.3C960,107,1020,117,1080,112C1140,107,1200,85,1260,74.7C1320,64,1380,64,1410,64L1440,64L1440,320L1410,320C1380,320,1320,320,1260,320C1200,320,1140,320,1080,320C1020,320,960,320,900,320C840,320,780,320,720,320C660,320,600,320,540,320C480,320,420,320,360,320C300,320,240,320,180,320C120,320,60,320,30,320L0,320Z">
                    </path>
                </svg>
            </div>
            {{-- Old Orders :: End --}}
        </div>
    </div>
    {{-- User :: Right :: End --}}
@endsection

{{-- Extra Scripts --}}
@push('js')
@endpush
