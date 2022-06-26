<?php

namespace App\Http\Controllers;

use App\Services\ReviewService;
use App\Http\Requests\ReviewRequest;

class ReviewController extends Controller
{
    protected $reviewService;

    public function __construct(ReviewService $reviewService)
    {
        $this->reviewService = $reviewService;
    }

    public function register(ReviewRequest $request)
    {
        $review = $request->only(['reservation_id', 'rate', 'title', 'content']);

        return $this->reviewService->register($review);
    }

    public function update(ReviewRequest $request, $id)
    {
        $newData = $request->safe()->only('rate', 'title', 'content');

        return $this->reviewService->update($newData, compact('id'));
    }

    public function destroy($id)
    {
        return $this->reviewService->destroy(compact('id'));
    }
}
