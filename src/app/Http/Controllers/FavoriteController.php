<?php

namespace App\Http\Controllers;

use App\Services\FavoriteService;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    protected $favoriteService;

    public function __construct(FavoriteService $favoriteService)
    {
        $this->favoriteService = $favoriteService;
    }

    public function register(Request $request)
    {
        $newFavorite = $request->only(['user_id', 'shop_id']);

        return $this->favoriteService->register($newFavorite);
    }

    public function destroy($user_id, $shop_id)
    {
        return $this->favoriteService->destroy(compact('user_id', 'shop_id'));
    }
}
