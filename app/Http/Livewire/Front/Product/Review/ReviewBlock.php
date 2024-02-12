<?php

namespace App\Http\Livewire\Front\Product\Review;

use App\Models\Review;
use Livewire\Component;
use Livewire\WithPagination;

class ReviewBlock extends Component
{
    use WithPagination;

    public $user_id = null, $user_review, $reviews, $item_id, $comment, $rating, $reviewSubmitted = false, $item_rating, $type;

    public $item_reviews_count, $five_stars_count, $five_stars_percentage, $four_stars_count, $four_stars_percentage, $three_stars_count, $three_stars_percentage, $two_stars_count, $two_stars_percentage, $one_stars_count, $one_stars_percentage;

    protected $listeners = [
        'updatedComment'
    ];

    ############# Mount :: Start #############
    public function mount()
    {
    }
    ############# Mount :: End #############

    ############# Render :: Start #############
    public function render()
    {
        // get all item's reviews
        $all_item_reviews = $this->reviews;

        if (auth()->check()) {
            // get user's review
            $user_review = $all_item_reviews->where('user_id', auth()->user()->id)->first();
            $this->user_review = $user_review;
            // get other reviews
            $all_reviews = $all_item_reviews->where('user_id', '!=', auth()->user()->id)->paginate(config('settings.back_pagination'));
        } else {
            $user_review = null;
            $this->user_review = null;
            $all_reviews = $all_item_reviews->paginate(config('settings.back_pagination'));
        }

        // get item's average rating
        $this->item_rating = $all_item_reviews->avg('rating');

        // get item's total reviews
        $this->item_reviews_count = $all_item_reviews->count();

        // get 5 stars rating count
        $this->five_stars_count = $all_item_reviews->where('rating', 5)->count();
        if ($this->five_stars_count) {
            $this->five_stars_percentage = $this->item_reviews_count > 0 ? ($this->five_stars_count / $this->item_reviews_count) * 100 : 0;
        } else {
            $this->five_stars_percentage = 0;
        }

        // get 4 stars rating count
        $this->four_stars_count = $all_item_reviews->where('rating', 4)->count();
        if ($this->four_stars_count) {
            $this->four_stars_percentage = $this->item_reviews_count > 0 ? ($this->four_stars_count / $this->item_reviews_count) * 100 : 0;
        } else {
            $this->four_stars_percentage = 0;
        }

        // get 3 stars rating count
        $this->three_stars_count = $all_item_reviews->where('rating', 3)->count();
        if ($this->three_stars_count) {
            $this->three_stars_percentage = $this->item_reviews_count > 0 ? ($this->three_stars_count / $this->item_reviews_count) * 100 : 0;
        } else {
            $this->three_stars_percentage = 0;
        }

        // get 2 stars rating count
        $this->two_stars_count = $all_item_reviews->where('rating', 2)->count();
        if ($this->two_stars_count) {
            $this->two_stars_percentage = $this->item_reviews_count > 0 ? ($this->two_stars_count / $this->item_reviews_count) * 100 : 0;
        } else {
            $this->two_stars_percentage = 0;
        }

        // get 1 stars rating count
        $this->one_stars_count = $all_item_reviews->where('rating', 1)->count();
        if ($this->one_stars_count) {
            $this->one_stars_percentage = $this->item_reviews_count > 0 ? ($this->one_stars_count / $this->item_reviews_count) * 100 : 0;
        } else {
            $this->one_stars_percentage = 0;
        }

        // if user has already reviewed this item
        if ($user_review) {
            $this->reviewSubmitted = true;
        }

        return view('livewire.front.product.review.review-block', compact(
            'user_review',
            'all_reviews'
        ));
    }
    ############# Render :: End #############

    ############# Rating :: Start #############
    public function rating($rating)
    {
        $this->rating = $rating;
    }
    ############# Rating :: End #############

    ############# Comment :: Start #############
    public function updatedComment($comment)
    {
        $this->comment = $comment;
    }
    ############# Comment :: End #############

    ############# Updated Current Page :: Start #############
    // public function updatedCurrentPage($currentPage)
    // {
    //     if (auth()->check()) {
    //         // get user's review
    //         $this->user_review = $this->all_product_reviews->where('user_id', auth()->user()->id)->first();
    //         // get other reviews
    //         $this->all_reviews = $this->all_product_reviews->where('user_id', '!=', auth()->user()->id)->forPage($currentPage, config('settings.back_pagination'));
    //     } else {
    //         $this->user_review = null;
    //         $this->all_reviews = $this->all_product_reviews->forPage($currentPage, config('settings.back_pagination'));
    //     }
    // }
    ############# Updated Current Page :: End #############

    ############# Add Review :: Start #############
    public function addReview()
    {
        $this->validate([
            'rating' => 'required|numeric|between:1,5'
        ], [
            'rating.required' => __('front/homePage.Please select rating.'),
            'rating.numeric' => __('front/homePage.Please select rating.'),
            'rating.between' => __('front/homePage.Please select rating.')
        ]);

        $review = Review::create([
            'user_id' => auth()->user()->id,
            'reviewable_id' => $this->item_id,
            'reviewable_type' => 'App\\Models\\' . $this->type,
            'comment' => $this->comment,
            'rating' => $this->rating,
            'status' => 0
        ]);

        $this->dispatchBrowserEvent('swalDone', [
            "text" => __('front/homePage.Review has been Added successfully'),
            'icon' => 'success'
        ]);

        $this->reviewSubmitted = true;
    }
    ############# Add Review :: End #############

    ############# Delete Review :: Start #############
    public function deleteReview()
    {
        $this->user_review->delete();

        $this->dispatchBrowserEvent('swalDone', [
            "text" => __('front/homePage.Review has been Deleted successfully'),
            'icon' => 'success'
        ]);

        $this->dispatchBrowserEvent('tinyMCE');

        $this->reviewSubmitted = false;
        $this->user_review = null;

        return redirect(request()->header('Referer'));
    }
    ############# Delete Review :: End #############
}
