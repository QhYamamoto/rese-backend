<?php

namespace App\Http\Controllers;

use App\Services\ShopService;

class ShopController extends Controller
{
    protected $shopService;

    public function __construct(ShopService $shopService)
    {
        $this->shopService = $shopService;
    }

    public function index()
    {
        return $this->shopService->index();
    }

    public function getById($id)
    {
        return $this->shopService->getById(compact('id'));
    }

    public function getFavoriteShops($user_id)
    {
        return $this->shopService->getFavoriteShops(['id' => $user_id]);
    }
}
