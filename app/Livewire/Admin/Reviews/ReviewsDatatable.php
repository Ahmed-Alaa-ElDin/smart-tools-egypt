<?php

namespace App\Livewire\Admin\Reviews;

use Livewire\Component;
use Illuminate\Support\Facades\Config;
use App\Models\Review;
use Livewire\WithPagination;

class ReviewsDatatable extends Component
{
    use WithPagination;

    public $sortBy = 'created_at';
    public $sortDirection = 'DESC';
    public $perPage = 10;
    public $search = '';
    public $selectedReviews = [];
    public $selectAll = false;

    // Render Once
    public function mount()
    {
        $this->perPage = Config::get('settings.back_pagination');

        $this->sortBy = 'created_at';

        $this->sortDirection = 'DESC';
    }

    public function render()
    {
        $reviews = Review::select([
            'id',
            'reviewable_id',
            'reviewable_type',
            'user_id',
            'rating',
            'comment',
            'status',
            'created_at',
            'updated_at',
        ])
            ->with([
                'reviewable' => function ($query) {
                    $query->select('id', 'name', 'slug')->without('brand');
                },
                'user:id,f_name,l_name',
            ])
            // ->where(function ($q) {
            //     $q
            //         ->where('products.name->en', 'like', '%' . $this->search . '%')
            //         ->orWhere('products.name->ar', 'like', '%' . $this->search . '%')
            //         ->orWhereHas('product', function ($query) {
            //             $query->where('name->en', 'like', '%' . $this->search . '%')
            //                 ->orWhere('name->ar', 'like', '%' . $this->search . '%');
            //         })
            //         ->orWhereHas('user', function ($query) {
            //             $query->where('f_name', 'like', '%' . $this->search . '%')
            //                 ->orWhere('l_name', 'like', '%' . $this->search . '%')
            //                 ->orWhere('email', 'like', '%' . $this->search . '%');
            //         });
            // })
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.admin.reviews.reviews-datatable', compact('reviews'));
    }

    // reset pagination after new search
    public function updatedSearch()
    {
        $this->resetPage();
    }

    // reset pagination after change per page
    public function updatedPerPage()
    {
        $this->resetPage();
    }

    // set sort by
    public function setSortBy($field)
    {
        $this->sortBy = $field;
        $this->sortDirection = $this->sortDirection === 'ASC' ? 'DESC' : 'ASC';
    }

    // Approve one review (change status to 1)
    public function approveReview($reviewId)
    {
        try {
            $review = Review::findOrFail($reviewId);
            $review->status = 1;
            $review->save();

            $this->dispatch(
                'swalDone',
                text: __('admin/reviewsPages.Review has been approved'),
                icon: 'success'
            );
        } catch (\Throwable $th) {
            $this->dispatch(
                'swalDone',
                text: __('admin/reviewsPages.Review has not been approved'),
                icon: 'error'
            );
        }
    }

    // Reject one review (change status to 2)
    public function rejectReview($reviewId)
    {
        try {
            $review = Review::findOrFail($reviewId);
            $review->status = 2;
            $review->save();

            $this->dispatch(
                'swalDone',
                text: __('admin/reviewsPages.Review has been rejected'),
                icon: 'success'
            );
        } catch (\Throwable $th) {
            $this->dispatch(
                'swalDone',
                text: __('admin/reviewsPages.Review has not been rejected'),
                icon: 'error'
            );
        }
    }

    // Approve all reviews (change status to 1)
    public function approveSelectedReviews()
    {
        try {
            Review::whereIn('id', $this->selectedReviews)->update(['status' => 1]);
            $this->selectedReviews = [];

            $this->dispatch(
                'swalDone',
                text: __('admin/reviewsPages.Reviews have been approved'),
                icon: 'success'
            );
        } catch (\Throwable $th) {
            $this->dispatch(
                'swalDone',
                text: __('admin/reviewsPages.Reviews haven\'t been approved'),
                icon: 'error'
            );
        }
    }

    // Reject all reviews (change status to 2)
    public function rejectSelectedReviews()
    {
        try {
            Review::whereIn('id', $this->selectedReviews)->update(['status' => 2]);
            $this->selectedReviews = [];

            $this->dispatch(
                'swalDone',
                text: __('admin/reviewsPages.Reviews have been rejected'),
                icon: 'success'
            );
        } catch (\Throwable $th) {
            $this->dispatch(
                'swalDone',
                text: __('admin/reviewsPages.Reviews haven\'t been rejected'),
                icon: 'error'
            );
        }
    }

    // Show review comment
    public function showReviewComment($id)
    {
        try {
            $comment = Review::findOrFail($id)->comment;

            $this->dispatch(
                'swalConfirm',
                html: nl2br(e($comment)),
                confirmButtonText: __('admin/reviewsPages.Done'),
                confirmButtonColor: 'green',
                showDenyButton: false,
                showConfirmButton: true,
                focusDeny: false,
                icon: null,
            );
        } catch (\Throwable $th) {
            $this->dispatch(
                'swalDone',
                text: __('admin/reviewsPages.Review has not been found'),
                icon: 'error'
            );
        }
    }
}
