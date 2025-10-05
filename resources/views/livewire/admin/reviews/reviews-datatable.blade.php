<div>
    {{-- Loader : Start --}}
    <x-admin.waiting />
    {{-- Loader : End --}}

    <div class="flex flex-col">

        {{-- Multiple Selection Section --}}
        @if (count($selectedReviews ?? 1))
            <div class="flex justify-around  items-center">
                <div
                    class="bg-primary rounded-full text-white font-bold px-3 py-2 flex justify-between items-center shadow gap-x-2 text-xs">
                    {{ trans_choice('admin/reviewsPages.review Selected', count($selectedReviews), ['review' => count($selectedReviews)]) }}
                    <span
                        class="material-icons w-4 h-4 bg-white text-black p-2 rounded-full flex justify-center items-center text-xs font-bold text-red-800 cursor-pointer"
                        wire:click="unselectAll" title="{{ __('admin/reviewsPages.Unselect All') }}">close</span>
                </div>
                <div>
                    <div class="flex justify-center">
                        <button class="btn btn-warning dropdown-toggle btn-round btn-sm text-white font-bold "
                            type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="material-icons">
                                settings
                            </span> &nbsp; {{ __('admin/reviewsPages.Control selected reviews') }}
                            &nbsp;</button>
                        <div class="dropdown-menu text-black ">
                            <a wire:click.prevent="approveSelectedReviews"
                                class="dropdown-item dropdown-item-excel justify-start font-bold hover:bg-success focus:bg-success hover:text-white focus:text-white cursor-pointer">
                                <span class="material-icons">
                                    check
                                </span> &nbsp;&nbsp;
                                {{ __('admin/reviewsPages.Approve Selected') }}
                            </a>
                            <a wire:click.prevent="rejectSelectedReviews"
                                class="dropdown-item dropdown-item-excel justify-start font-bold hover:bg-red-600 focus:bg-red-600 hover:text-white focus:text-white cursor-pointer">
                                <span class="material-icons">
                                    close
                                </span> &nbsp;&nbsp;
                                {{ __('admin/reviewsPages.Reject Selected') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        {{-- Multiple Selection Section --}}

        {{-- Search and Pagination Control --}}
        <div class="py-3 bg-white space-y-3">

            <div class="flex flex-wrap justify-between gap-6 items-center">
                {{-- Search Box --}}
                <div class="col-span-1">
                    <div class="mt-1 flex rounded-md shadow-sm">
                        <span
                            class="inline-flex items-center px-3 ltr:rounded-l-md rtl:rounded-r-md border border-r-0 border-gray-300 bg-gray-50 text-center text-gray-500 text-sm">
                            <span class="material-icons">
                                search
                            </span> </span>
                        <input type="text" wire:model.live='search'
                            class="focus:ring-primary focus:border-primary flex-1 block w-full rounded-none ltr:rounded-r-md rtl:rounded-l-md sm:text-sm border-gray-300"
                            placeholder="{{ __('admin/reviewsPages.Search ...') }}">
                    </div>
                </div>

                {{-- Pagination Number --}}
                <div class="form-inline col-span-1 justify-end my-2">
                    {{ __('pagination.Show') }} &nbsp;
                    <select wire:model.live='perPage' class="form-control w-auto px-3 cursor-pointer">
                        <option>5</option>
                        <option>10</option>
                        <option>25</option>
                        <option>50</option>
                        <option>100</option>
                    </select>
                    &nbsp; {{ __('pagination.results') }}
                </div>
            </div>
        </div>

        {{-- Search and Pagination Control --}}
        <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">

                <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        {{-- Datatable Header --}}
                        <thead class="bg-gray-50">
                            <tr>
                                {{-- Multiple Select Header --}}
                                <th scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider select-none">
                                    <div class="min-w-max">
                                        #
                                    </div>
                                </th>

                                {{-- User Name Header --}}
                                <th scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider select-none">
                                    <div class="min-w-max">
                                        {{ __('admin/reviewsPages.User Name') }} &nbsp;
                                    </div>
                                </th>

                                {{-- Product/Collection Header --}}
                                <th scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider select-none">
                                    <div class="min-w-max">
                                        {{ __('admin/reviewsPages.Product/Collection') }}&nbsp;
                                    </div>
                                </th>

                                {{-- Rating Header --}}
                                <th scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider select-none">
                                    <div class="min-w-max">
                                        {{ __('admin/reviewsPages.Rating') }}&nbsp;
                                    </div>
                                </th>

                                {{-- Comment Header --}}
                                <th scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider select-none">
                                    <div class="min-w-max">
                                        {{ __('admin/reviewsPages.Comment') }}&nbsp;
                                    </div>
                                </th>

                                {{-- Status Header --}}
                                <th scope="col" wire:click="setSortBy('status')"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer select-none">
                                    <div class="min-w-max">
                                        {{ __('admin/reviewsPages.Status') }}&nbsp;
                                        @include('partials._sort_icon', ['field' => 'status'])
                                    </div>
                                </th>

                                {{-- Date Header --}}
                                <th wire:click="setSortBy('created_at')" scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer select-none">
                                    <div class="min-w-max">
                                        {{ __('admin/reviewsPages.Date') }}&nbsp;
                                        @include('partials._sort_icon', ['field' => 'created_at'])
                                    </div>
                                </th>

                                {{-- Manage Header --}}
                                <th scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider select-none">
                                    <div class="min-w-max">
                                        {{ __('admin/reviewsPages.Manage') }}
                                        <span class="sr-only">{{ __('admin/reviewsPages.Manage') }}</span>
                                    </div>
                                </th>
                            </tr>
                        </thead>

                        {{-- Datatable Body --}}
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($reviews as $review)
                                <tr>
                                    {{-- select review Body --}}
                                    <td class="px-6 py-2 max-w-min whitespace-nowrap overflow-hidden">
                                        <div class="flex items-center content-center">
                                            <input type="checkbox" wire:model.live="selectedReviews"
                                                value="{{ $review->id }}"
                                                class="appearance-none border-gray-600 rounded-full checked:bg-secondary outline-none ring-0 cursor-pointer">
                                        </div>
                                    </td>

                                    {{-- User Name Body --}}
                                    <td class="px-6 py-2 max-w-min whitespace-nowrap overflow-hidden">
                                        <div class="flex items-center content-center w-64">
                                            {{ $review->user->f_name }} {{ $review->user->l_name }}
                                        </div>
                                    </td>

                                    {{-- Product/Collection Body --}}
                                    <td class="px-6 py-2 max-w-min whitespace-nowrap overflow-hidden">
                                        <div class="flex items-center content-center justify-center">
                                            <a
                                                href="{{ $review->reviewable->type == 'Product' ? route('front.products.show', ['id' => $review->reviewable->id, 'slug' => $review->reviewable->slug]) : route('front.collections.show', ['id' => $review->reviewable->id, 'slug' => $review->reviewable->slug]) }}">
                                                {{ $review->reviewable ? $review->reviewable->name : __('N/A') }}
                                            </a>
                                        </div>
                                    </td>

                                    {{-- Rating Body --}}
                                    <td class="px-6 py-2 max-w-min whitespace-nowrap overflow-hidden">
                                        <div class="flex flex-wrap items-center content-center justify-center">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <span
                                                    class="material-icons inline-block text-lg @if ($i <= $review->rating) text-yellow-300 @else text-gray-400 @endif">
                                                    star
                                                </span>
                                            @endfor
                                        </div>
                                    </td>

                                    {{-- Comment Body --}}
                                    <td class="px-6 py-2 text-center whitespace-nowrap">
                                        <span title="{{ __('admin/reviewsPages.View Comment') }}"
                                            class="material-icons p-1 text-lg w-9 h-9 text-white bg-view hover:bg-viewHover cursor-pointer rounded"
                                            wire:click="showReviewComment({{ $review->id }})">
                                            visibility
                                        </span>
                                    </td>

                                    {{-- Status Body --}}
                                    <td class="px-6 py-2 text-center whitespace-nowrap">
                                        <div class="text-sm">
                                            @switch ($review->status)
                                                @case(0)
                                                    <span class="bg-warning px-2 py-1 rounded text-white text-xs">
                                                        {{ __('admin/reviewsPages.Pending') }}
                                                    </span>
                                                @break

                                                @case(1)
                                                    <span class="bg-success px-2 py-1 rounded text-white text-xs">
                                                        {{ __('admin/reviewsPages.Approved') }}
                                                    </span>
                                                @break

                                                @case(2)
                                                    <span class="bg-delete px-2 py-1 rounded text-white text-xs">
                                                        {{ __('admin/reviewsPages.Rejected') }}
                                                    </span>
                                                @break
                                            @endswitch
                                        </div>
                                    </td>

                                    {{-- Publish Body --}}
                                    <td class="px-6 py-2 text-center whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ $review->created_at->format('Y-m-d | h:i A') }}
                                        </div>
                                    </td>

                                    {{-- Manage Body --}}
                                    <td class="px-6 py-2 whitespace-nowrap text-center text-sm font-medium">

                                        @switch ($review->status)
                                            {{-- Pending --}}
                                            @case(0)
                                                {{-- Approve Button --}}
                                                <a href="#" title="{{ __('admin/reviewsPages.Approve') }}"
                                                    wire:click.prevent="approveReview({{ $review->id }})" class="m-0">
                                                    <span
                                                        class="material-icons p-1 text-lg w-9 h-9 text-white bg-success hover:bg-successHover rounded">
                                                        check
                                                    </span>
                                                </a>

                                                {{-- Reject Button --}}
                                                <a href="#" title="{{ __('admin/reviewsPages.Reject') }}"
                                                    wire:click.prevent="rejectReview({{ $review->id }})" class="m-0">
                                                    <span
                                                        class="material-icons p-1 text-lg w-9 h-9 text-white bg-delete hover:bg-deleteHover rounded">
                                                        close
                                                    </span>
                                                </a>
                                            @break

                                            @case(1)
                                                {{-- Reject Button --}}
                                                <a href="#" title="{{ __('admin/reviewsPages.Reject') }}"
                                                    wire:click.prevent="rejectReview({{ $review->id }})" class="m-0">
                                                    <span
                                                        class="material-icons p-1 text-lg w-9 h-9 text-white bg-delete hover:bg-deleteHover rounded">
                                                        close
                                                    </span>
                                                </a>
                                            @break

                                            @case(2)
                                                {{-- Approve Button --}}
                                                <a href="#" title="{{ __('admin/reviewsPages.Approve') }}"
                                                    wire:click.prevent="approveReview({{ $review->id }})" class="m-0">
                                                    <span
                                                        class="material-icons p-1 text-lg w-9 h-9 text-white bg-success hover:bg-successHover rounded">
                                                        check
                                                    </span>
                                                </a>
                                            @break
                                        @endswitch
                                    </td>
                                </tr>
                                @empty
                                    <tr>
                                        <td class="text-center py-2 font-bold" colspan="8">
                                            {{ $search == '' ? __('admin/reviewsPages.No data in this table') : __('admin/reviewsPages.No data available according to your search') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="mt-4">
                {{ $reviews->links() }}
            </div>
        </div>

        {{-- Review Comment :: Start --}}
        <div id="reviewCommentModal" tabindex="-1"
            class="overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 w-full md:inset-0 h-modal md:h-full justify-center items-center hidden"
            aria-modal="true" role="dialog">
            <div class="relative p-4 w-full max-w-2xl h-full md:h-auto">
                <!-- Modal content -->
                <div class="relative bg-white rounded-lg shadow">
                    <!-- Modal header -->
                    <div class="flex justify-between items-start p-4 rounded-t border-b">
                        <h3 class="grow text-xl font-semibold text-gray-900 dark:text-white">
                            {{ __('admin/reviewsPages.Review Comment') }}
                        </h3>
                        <button type="button"
                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center"
                            data-modal-toggle="reviewComment-{{ $review->id }}">
                            <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                    <!-- Modal body -->
                    <div class="p-6 space-y-6">
                        <p class="leading-relaxed text-gray-900 text-center">
                            {{ $review->comment }}
                        </p>
                    </div>
                    <!-- Modal footer -->
                    <div class="flex items-center justify-around p-2 space-x-2 rounded-b border-t border-gray-200">
                        <form action="{{ route('front.orders.return-calc', $review->id) }}" method="POST"
                            class="m-0">
                            @csrf

                            <button type="submit" name="type" value="return"
                                class="btn text-gray-600 bg-white hover:bg-gray-100 focus:outline-none rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10">
                                {{ __('admin/reviewsPages.Yes') }}
                            </button>
                        </form>
                        <button data-modal-toggle="reviewComment-{{ $review->id }}" type="button"
                            class="btn bg-primary focus:outline-none font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                            {{ __('admin/reviewsPages.No') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
        {{-- Review Comment :: End --}}

    </div>
