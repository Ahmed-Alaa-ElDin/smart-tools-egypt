<div class="w-full">
    <div class="w-full grid grid-cols-2 gap-8 justify-between items-start">
        <div class="col-span-2 md:col-span-1 flex flex-col gap-6">
            {{-- Old Rewiews Summary :: Start --}}
            <div class="rounded-xl bg-gradient-to-br from-gray-100 to-white py-4 px-8 shadow-lg select-none">
                <p class="text-center text-xl mb-3 font-bold">
                    {{ __('front/homePage.Reviews Summary') }}
                </p>

                {{-- Cumulative Rating :: Start --}}
                <div class="flex gap-4 items-center mb-4">
                    <h5 class="text-8xl font-black">
                        {{ number_format($item_rating, 1) }}
                    </h5>
                    <div class="flex flex-col justify-center items-center">
                        <div>
                            @for ($i = 1; $i <= 5; $i++)
                                <span
                                    class="material-icons inline-block @if ($i <= ceil($item_rating)) text-yellow-300 @else text-gray-400 @endif">
                                    star
                                </span>
                            @endfor
                        </div>
                        <span class="text-sm font-bold text-gray-600">
                            {{ trans_choice('front/homePage.Review/Reviews', $item_reviews_count, ['review' => $item_reviews_count]) }}
                        </span>
                    </div>
                </div>
                {{-- Cumulative Rating :: End --}}

                {{-- Detailed Rating :: Start --}}
                <div>
                    {{-- 5 Stars :: Start --}}
                    <div class="flex gap-4 items-center">
                        <div class="grow min-w-max">
                            @for ($i = 1; $i <= 5; $i++)
                                <span
                                    class="material-icons inline-block text-lg @if ($i <= 5) text-yellow-300 @else text-gray-400 @endif">
                                    star
                                </span>
                            @endfor
                        </div>
                        <div class="w-full bg-gray-200 rounded-full">
                            <div class="bg-green-500 text-xs font-bold text-white text-center p-0.5 leading-none rounded-full truncate"
                                style="width: {{ round($five_stars_percentage) }}%" title="{{ round($five_stars_percentage) }}% ({{ $five_stars_count }})">
                                {{ round($five_stars_percentage) }}% ({{ $five_stars_count }})
                            </div>
                        </div>
                    </div>
                    {{-- 5 Stars :: End --}}

                    {{-- 4 Stars :: Start --}}
                    <div class="flex gap-4 items-center">
                        <div class="grow min-w-max">
                            @for ($i = 1; $i <= 5; $i++)
                                <span
                                    class="material-icons inline-block text-lg @if ($i <= 4) text-yellow-300 @else text-gray-400 @endif">
                                    star
                                </span>
                            @endfor
                        </div>
                        <div class="w-full bg-gray-200 rounded-full">
                            <div class="bg-lime-500 text-xs font-bold text-white text-center p-0.5 leading-none rounded-full truncate"
                            style="width: {{ round($four_stars_percentage) }}%" title="{{ round($four_stars_percentage) }}% ({{ $four_stars_count }})">
                            {{ round($four_stars_percentage) }}% ({{ $four_stars_count }})
                        </div>
                        </div>
                    </div>
                    {{-- 4 Stars :: End --}}

                    {{-- 3 Stars :: Start --}}
                    <div class="flex gap-4 items-center">
                        <div class="grow min-w-max">
                            @for ($i = 1; $i <= 5; $i++)
                                <span
                                    class="material-icons inline-block text-lg @if ($i <= 3) text-yellow-300 @else text-gray-400 @endif">
                                    star
                                </span>
                            @endfor
                        </div>
                        <div class="w-full bg-gray-200 rounded-full">
                            <div class="bg-yellow-300 text-xs font-bold text-white text-center p-0.5 leading-none rounded-full truncate"
                            style="width: {{ round($three_stars_percentage) }}%" title="{{ round($three_stars_percentage) }}% ({{ $three_stars_count }})">
                            {{ round($three_stars_percentage) }}% ({{ $three_stars_count }})
                        </div>
                        </div>
                    </div>
                    {{-- 3 Stars :: End --}}

                    {{-- 2 Stars :: Start --}}
                    <div class="flex gap-4 items-center">
                        <div class="grow min-w-max">
                            @for ($i = 1; $i <= 5; $i++)
                                <span
                                    class="material-icons inline-block text-lg @if ($i <= 2) text-yellow-300 @else text-gray-400 @endif">
                                    star
                                </span>
                            @endfor
                        </div>
                        <div class="w-full bg-gray-200 rounded-full">
                            <div class="bg-orange-400 text-xs font-bold text-white text-center p-0.5 leading-none rounded-full truncate"
                            style="width: {{ round($two_stars_percentage) }}%" title="{{ round($two_stars_percentage) }}% ({{ $two_stars_count }})">
                            {{ round($two_stars_percentage) }}% ({{ $two_stars_count }})
                        </div>
                        </div>
                    </div>
                    {{-- 2 Stars :: End --}}

                    {{-- 1 Star :: Start --}}
                    <div class="flex gap-4 items-center">
                        <div class="grow min-w-max">
                            @for ($i = 1; $i <= 5; $i++)
                                <span
                                    class="material-icons inline-block text-lg @if ($i <= 1) text-yellow-300 @else text-gray-400 @endif">
                                    star
                                </span>
                            @endfor
                        </div>
                        <div class="w-full bg-gray-200 rounded-full">
                            <div class="bg-red-500 text-xs font-bold text-white text-center p-0.5 leading-none rounded-full truncate"
                            style="width: {{ round($one_stars_percentage) }}%" title="{{ round($one_stars_percentage) }}% ({{ $one_stars_count }})">
                            {{ round($one_stars_percentage) }}% ({{ $one_stars_count }})
                        </div>
                        </div>
                    </div>
                    {{-- 1 Star :: End --}}
                </div>

            </div>
            {{-- Old Rewiews Summary :: End --}}

            {{-- New Review :: Start --}}
            {{-- todo :: Check if the user bought this product --}}
            @if (auth()->check() && !$reviewSubmitted)
                <div
                    class="rounded-xl bg-gradient-to-br from-red-100 to-white py-4 px-8 shadow-lg flex flex-col w-full gap-3 items-center">

                    <p class="text-center text-xl mb-3 font-bold">
                        {{ __('front/homePage.Add Review') }}
                    </p>

                    <div class="flex justify-between items-center w-full">
                        <div class="flex gap-3 justify-center items-center">
                            @if (auth()->user()->profile_photo_path)
                                <img class="h-10 w-10 rounded-full"
                                    src="{{ asset('storage/images/profiles/cropped100/' . auth()->user()->profile_photo_path) }}"
                                    alt="{{ auth()->user()->f_name . ' ' . auth()->user()->l_name . ' profile image' }}">
                            @else
                                <span class="material-icons">
                                    person
                                </span>
                            @endif
                            <span class="font-bold">{{ auth()->user()->f_name . ' ' . auth()->user()->l_name }}</span>
                        </div>
                        {{-- Rating :: Start --}}
                        <div class="text-center select-none">
                            @for ($i = 1; $i <= 5; $i++)
                                <span
                                    class="material-icons inline-block mr-1 cursor-pointer transition-all ease-in-out hover:scale-125 @if ($i <= $rating) text-yellow-300 @else text-gray-400 @endif"
                                    wire:click="changeRating({{ $i }})">
                                    star
                                </span>
                            @endfor
                            {{-- Error :: Start --}}
                            @error('rating')
                                <div class="text-red-500 text-xs font-bold">
                                    {{ $message }}
                                </div>
                            @enderror
                            {{-- Error :: End --}}
                        </div>
                        {{-- Rating :: End --}}
                    </div>

                    {{-- Comment :: Start --}}
                    <div wire:ignore
                        class="py-1 w-full min-h-[9rem] px-6 rounded text-right border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300 cursor-text"
                        type="text" id="comment" placeholder="{{ __('front/homePage.Add Your Comment Here') }}">
                        {!! $comment !!}
                    </div>
                    {{-- Comment :: End --}}


                    {{-- Submit :: Start --}}
                    <button class="btn bg-red-500 inline-block w-50 font-bold" wire:click="addReview">
                        {{ __('front/homePage.Submit Review') }}
                    </button>
                    {{-- Submit :: End --}}

                </div>
            @elseif ($reviewSubmitted)
                <div
                    class="rounded-xl bg-gradient-to-br from-green-100 to-white py-4 px-8 shadow-lg flex flex-col w-full gap-3 items-center">
                    <p class="text-center text-xl mb-3 font-bold">
                        {{ __('front/homePage.Add Review') }}
                    </p>
                    <p class="text-center">
                        {{ __('front/homePage.Your Review Submitted Successfully (Could be under-reviewing)') }}
                    </p>
                </div>
            @elseif (!auth()->check())
                <div
                    class="rounded-xl bg-gradient-to-br from-yellow-100 to-white py-4 px-8 shadow-lg flex flex-col w-full gap-3 items-center">
                    <p class="text-center text-xl mb-3 font-bold">
                        {{ __('front/homePage.Add Review') }}
                    </p>
                    <p class="text-center mb-2">
                        {{ __('front/homePage.You Should Login First to be able to add a new review') }}
                    </p>
                    {{-- todo :: login route name --}}
                    <a href="{{ route('login') }}" class="btn bg-yellow-500 font-bold">{{ __('front/homePage.Login') }}</a>
                </div>
            @endif
            {{-- New Review :: End --}}
        </div>

        {{-- Old Reviews :: Start --}}
        <div class="col-span-2 md:col-span-1 flex flex-col gap-4 select-none">
            {{-- Auth User's Review :: Start --}}
            @if ($user_review)
                <div
                    class="rounded-xl bg-gradient-to-br
                    @if ($user_review->rating == 5) from-green-100
                    @elseif ($user_review->rating == 4) from-lime-100
                    @elseif ($user_review->rating == 3) from-yellow-100
                    @elseif ($user_review->rating == 2) from-orange-100
                    @else from-red-100 @endif
                    to-white py-4 px-8 shadow-lg flex flex-col w-full gap-3 items-center">
                    <div class="flex justify-between items-center w-full">
                        <div class="flex gap-3 justify-center items-center">
                            @if ($user_review->user->profile_photo_path)
                                <img class="h-10 w-10 rounded-full"
                                    src="{{ asset('storage/images/profiles/cropped100/' . $user_review->user->profile_photo_path) }}"
                                    alt="{{ $user_review->user->f_name . ' ' . $user_review->user->l_name . ' profile image' }}">
                            @else
                                <span class="material-icons">
                                    person
                                </span>
                            @endif
                            <span
                                class="font-bold">{{ $user_review->user->f_name . ' ' . $user_review->user->l_name }}</span>
                        </div>
                        {{-- Rating :: Start --}}
                        <div class="text-center select-none">
                            @for ($i = 1; $i <= 5; $i++)
                                <span
                                    class="material-icons inline-block @if ($i <= $user_review->rating) text-yellow-300 @else text-gray-400 @endif">
                                    star
                                </span>
                            @endfor
                        </div>
                        {{-- Rating :: End --}}

                        {{-- Delete Review :: Start --}}
                        <div
                            class="flex items-center justify-center bg-white border-2 border-red-500 rounded-circle w-9 h-9 shadow">
                            <span class="material-icons text-red-500 hover:text-red-600 cursor-pointer"
                                wire:click="deleteReview">
                                delete
                            </span>
                        </div>
                        {{-- Delete Review :: End --}}
                    </div>

                    {{-- Comment :: Start --}}
                    @if ($user_review->comment)
                        <div class="py-1 w-full px-6 rounded border-0 shadow-sm" type="text"
                            placeholder="{{ __('front/homePage.Add Your Comment Here') }}">
                            {!! $user_review->comment !!}
                        </div>
                    @endif
                    {{-- Comment :: End --}}
                </div>
            @endif
            {{-- Auth User's Review :: End --}}

            {{-- Other User's Reviews :: Start --}}
            @foreach ($all_reviews as $review)
                <div
                    class="rounded-xl bg-gradient-to-br
                    @if ($review->rating == 5) from-green-100
                    @elseif ($review->rating == 4) from-lime-100
                    @elseif ($review->rating == 3) from-yellow-100
                    @elseif ($review->rating == 2) from-orange-100
                    @else from-red-100 @endif
                    to-white py-4 px-8 shadow-lg flex flex-col w-full gap-3 items-center">
                    <div class="flex justify-between items-center w-full">
                        <div class="flex gap-3 justify-center items-center">
                            @if ($review->user->profile_photo_path)
                                <img class="h-10 w-10 rounded-full"
                                    src="{{ asset('storage/images/profiles/cropped100/' . $review->user->profile_photo_path) }}"
                                    alt="{{ $review->user->f_name . ' ' . $review->user->l_name . ' profile image' }}">
                            @else
                                <span class="material-icons">
                                    person
                                </span>
                            @endif
                            <span class="font-bold">{{ $review->user->f_name . ' ' . $review->user->l_name }}</span>
                        </div>
                        {{-- Rating :: Start --}}
                        <div class="text-center select-none">
                            @for ($i = 1; $i <= 5; $i++)
                                <span
                                    class="material-icons inline-block @if ($i <= $review->rating) text-yellow-300 @else text-gray-400 @endif">
                                    star
                                </span>
                            @endfor
                        </div>
                        {{-- Rating :: End --}}
                    </div>
                    {{-- Comment :: Start --}}
                    @if ($review->comment)
                        <div class="py-1 w-full px-6 rounded border-0 shadow-sm" type="text"
                            placeholder="{{ __('front/homePage.Add Your Comment Here') }}">
                            {!! $review->comment !!}
                        </div>
                    @endif
                    {{-- Comment :: End --}}
                </div>
            @endforeach

            <div>
                {{ $all_reviews->links() }}
            </div>
            {{-- Other User's Reviews :: End --}}
        </div>
        {{-- Old Reviews :: End --}}
    </div>
</div>
