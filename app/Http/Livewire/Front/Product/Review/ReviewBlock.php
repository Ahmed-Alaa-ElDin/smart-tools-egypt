<?php

namespace App\Http\Livewire\Front\Product\Review;

use App\Models\Review;
use Livewire\Component;
use Livewire\WithPagination;

class ReviewBlock extends Component
{
    public $user_id = null, $product_id, $comment, $rating, $reviewSubmitted = false, $user_review, $all_reviews, $current_page = 1, $total_pages;

    protected $listeners = [
        'updatedComment'
    ];

    ############# Mount :: Start #############
    public function mount()
    {
        // get all product's reviews
        $this->all_product_reviews = get_product_rating($this->product_id);

        if (auth()->check()) {
            // get user's review
            $this->user_review = $this->all_product_reviews->where('user_id', auth()->user()->id)->first();
            // get other reviews
            $this->all_reviews = $this->all_product_reviews->where('user_id', '!=', auth()->user()->id)->forPage($this->current_page, config('constants.constants.PAGINATION'));
        } else {
            $this->user_review = null;
            $this->all_reviews = $this->all_product_reviews->forPage($this->current_page, config('constants.constants.PAGINATION'));
        }

        // get total pages
        $this->total_pages = ceil($this->all_product_reviews->count() / config('constants.constants.PAGINATION'));

        // get product's average rating
        $this->product_rating = $this->all_product_reviews->avg('rating');

        // get product's total reviews
        $this->product_reviews_count = $this->all_product_reviews->count();

        // get 5 stars rating count
        $this->five_stars_count = $this->all_product_reviews->where('rating', 5)->count();
        if ($this->five_stars_count) {
            $this->five_stars_percentage = ($this->five_stars_count / $this->product_reviews_count) * 100;
        } else {
            $this->five_stars_percentage = 0;
        }

        // get 4 stars rating count
        $this->four_stars_count = $this->all_product_reviews->where('rating', 4)->count();
        if ($this->four_stars_count) {
            $this->four_stars_percentage = ($this->four_stars_count / $this->product_reviews_count) * 100;
        } else {
            $this->four_stars_percentage = 0;
        }

        // get 3 stars rating count
        $this->three_stars_count = $this->all_product_reviews->where('rating', 3)->count();
        if ($this->three_stars_count) {
            $this->three_stars_percentage = ($this->three_stars_count / $this->product_reviews_count) * 100;
        } else {
            $this->three_stars_percentage = 0;
        }

        // get 2 stars rating count
        $this->two_stars_count = $this->all_product_reviews->where('rating', 2)->count();
        if ($this->two_stars_count) {
            $this->two_stars_percentage = ($this->two_stars_count / $this->product_reviews_count) * 100;
        } else {
            $this->two_stars_percentage = 0;
        }

        // get 1 stars rating count
        $this->one_stars_count = $this->all_product_reviews->where('rating', 1)->count();
        if ($this->one_stars_count) {
            $this->one_stars_percentage = ($this->one_stars_count / $this->product_reviews_count) * 100;
        } else {
            $this->one_stars_percentage = 0;
        }

        // if user has already reviewed this product
        if ($this->user_review) {
            $this->reviewSubmitted = true;
        }
    }
    ############# Mount :: End #############

    ############# Render :: Start #############
    public function render()
    {
        return view('livewire.front.product.review.review-block');
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
    public function updatedCurrentPage($current_page)
    {
        if (auth()->check()) {
            // get user's review
            $this->user_review = $this->all_product_reviews->where('user_id', auth()->user()->id)->first();
            // get other reviews
            $this->all_reviews = $this->all_product_reviews->where('user_id', '!=', auth()->user()->id)->forPage($current_page, config('constants.constants.PAGINATION'));
        } else {
            $this->user_review = null;
            $this->all_reviews = $this->all_product_reviews->forPage($current_page, config('constants.constants.PAGINATION'));
        }
    }
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
            'product_id' => $this->product_id,
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
    }
    ############# Delete Review :: End #############
}
