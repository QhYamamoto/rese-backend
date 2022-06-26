<?php

namespace App\Services;

use App\Repositories\Favorite\FavoriteRepositoryInterface;

class FavoriteService extends Service
{
    protected $favoriteRepository;

    public function __construct(FavoriteRepositoryInterface $favoriteRepository)
    {
        $this->favoriteRepository = $favoriteRepository;
    }

    public function register($attributes)
    {
        try {
            $newData = $this->favoriteRepository->create($attributes);
            return $this->jsonResponse(compact('newData'));
        } catch (\Throwable $th) {
            return $this->errorResponse($th);
        }
    }

    public function destroy($condition)
    {
        try {
            $this->favoriteRepository->delete($condition);
            return $this->jsonResponse(['message' => 'お気に入りを削除しました。']);
        } catch (\Throwable $th) {
            return $this->errorResponse($th);
        }
    }
}