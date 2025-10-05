<?php

namespace App\Livewire\Front\Product\Review;

use App\Models\Review;
use Livewire\Component;
use Livewire\WithPagination;

class ReviewBlock extends Component
{
    use WithPagination;

    public $user_id = null, $user_review, $reviews, $item_id, $comment, $rating, $reviewSubmitted = false, $item_rating, $type;

    public $item_reviews_count, $five_stars_count, $five_stars_percentage, $four_stars_count, $four_stars_percentage, $three_stars_count, $three_stars_percentage, $two_stars_count, $two_stars_percentage, $one_stars_count, $one_stars_percentage;

    ############# Mount :: Start #############
    public function mount() {}
    ############# Mount :: End #############

    ############# Render :: Start #############
    public function render()
    {
        $perPage = config('settings.back_pagination');
        $allItemReviews = $this->reviews;
        $user = auth()->user();

        // get all item's reviews
        $all_item_reviews = $this->reviews;

        if ($user) {
            // get user's review
            $user_review = $all_item_reviews->where('user_id', $user->id)->first();
            $this->reviewSubmitted = $user_review ? true : false;
            $this->user_review = $user_review;
            // get other reviews
            $all_reviews = $all_item_reviews->where('user_id', '!=', $user->id)->where('status', 1)->paginate($perPage);
        } else {
            $user_review = null;
            $this->user_review = null;
            $all_reviews = $all_item_reviews->where('status', 1)->paginate($perPage);
            $this->reviewSubmitted = false;
        }

        $all_approved_reviews = $all_item_reviews->where('status', 1);

        // get item's average rating
        $this->item_rating = $all_approved_reviews->avg('rating');

        // get item's total reviews
        $this->item_reviews_count = $all_approved_reviews->count();

        // get 5 stars rating count
        $this->five_stars_count = $all_approved_reviews->where('rating', 5)->count();
        if ($this->five_stars_count) {
            $this->five_stars_percentage = $this->item_reviews_count > 0 ? ($this->five_stars_count / $this->item_reviews_count) * 100 : 0;
        } else {
            $this->five_stars_percentage = 0;
        }

        // get 4 stars rating count
        $this->four_stars_count = $all_approved_reviews->where('rating', 4)->count();
        if ($this->four_stars_count) {
            $this->four_stars_percentage = $this->item_reviews_count > 0 ? ($this->four_stars_count / $this->item_reviews_count) * 100 : 0;
        } else {
            $this->four_stars_percentage = 0;
        }

        // get 3 stars rating count
        $this->three_stars_count = $all_approved_reviews->where('rating', 3)->count();
        if ($this->three_stars_count) {
            $this->three_stars_percentage = $this->item_reviews_count > 0 ? ($this->three_stars_count / $this->item_reviews_count) * 100 : 0;
        } else {
            $this->three_stars_percentage = 0;
        }

        // get 2 stars rating count
        $this->two_stars_count = $all_approved_reviews->where('rating', 2)->count();
        if ($this->two_stars_count) {
            $this->two_stars_percentage = $this->item_reviews_count > 0 ? ($this->two_stars_count / $this->item_reviews_count) * 100 : 0;
        } else {
            $this->two_stars_percentage = 0;
        }

        // get 1 stars rating count
        $this->one_stars_count = $all_approved_reviews->where('rating', 1)->count();
        if ($this->one_stars_count) {
            $this->one_stars_percentage = $this->item_reviews_count > 0 ? ($this->one_stars_count / $this->item_reviews_count) * 100 : 0;
        } else {
            $this->one_stars_percentage = 0;
        }

        return view('livewire.front.product.review.review-block', compact(
            'all_reviews'
        ));
    }
    ############# Render :: End #############

    ############# Rating :: Start #############
    public function changeRating($rating)
    {
        $this->rating = $rating;
    }

    ############# Add Review :: Start #############
    public function addReview()
    {
        try {
            $this->validate([
                'rating' => 'required|numeric|between:1,5'
            ], [
                'rating.required' => __('front/homePage.Please select rating.'),
                'rating.numeric' => __('front/homePage.Please select rating.'),
                'rating.between' => __('front/homePage.Please select rating.')
            ]);

            Review::create([
                'user_id' => auth()->user()->id,
                'reviewable_id' => $this->item_id,
                'reviewable_type' => 'App\\Models\\' . $this->type,
                'comment' => $this->comment,
                'rating' => $this->rating,
                'status' => 0
            ]);

            $this->dispatch(
                'swalDone',
                text: __('front/homePage.Review has been Added successfully'),
                icon: 'success'
            );

            $this->reviewSubmitted = true;
        } catch (\Exception $e) {
            $this->dispatch(
                'swalDone',
                text: __('front/homePage.Something went wrong'),
                icon: 'error'
            );
        }
    }
    ############# Add Review :: End #############

    ############# Delete Review :: Start #############
    public function deleteReview()
    {
        $this->user_review->delete();

        $this->dispatch(
            'swalDone',
            text: __('front/homePage.Review has been Deleted successfully'),
            icon: 'success'
        );

        $this->dispatch('tinyMCE');

        $this->reviewSubmitted = false;
        $this->user_review = null;

        return redirect(request()->header('Referer'));
    }
    ############# Delete Review :: End #############
}
