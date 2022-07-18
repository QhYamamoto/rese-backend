<?php

namespace App\Services;

use App\Repositories\Review\ReviewRepositoryInterface;

class ReviewService extends Service
{
    protected $reviewRepository;

    public function __construct(ReviewRepositoryInterface $reviewRepository)
    {
        $this->reviewRepository = $reviewRepository;
    }

    public function register($attributes)
    {
        try {
            $newData = $this->reviewRepository->create($attributes);
            return $this->jsonResponse(compact('newData'));
        } catch (\Throwable $th) {
            return $this->errorResponse($th);
        }

    }

    public function update($attributes, $id)
    {
        if (!isset($attributes['title'])) {
            $attributes['title'] = '';
        }

        if (!isset($attributes['content'])) {
            $attributes['content'] = '';
        }   

        try {
            $this->reviewRepository->update($attributes, $id);
            return $this->jsonResponse(['message' => 'レビューが変更されました。']);
        } catch (\Throwable $th) {
            return $this->errorResponse($th);
        }

    }

    public function destroy($id)
    {
        try {
            $this->reviewRepository->delete($id);
            return $this->jsonResponse(['message' => 'レビューを削除しました。']);
        } catch (\Throwable $th) {
            return $this->errorResponse($th);
        }
    }
}