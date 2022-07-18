<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use App\Services\ShopService;
use App\Services\CourseService;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\ShopRequest;
use App\Http\Requests\CourseRequest;

class AdminController extends Controller
{
    protected $userService;
    protected $courseService;

    public function __construct(UserService $userService, ShopService $shopService, CourseService $courseService)
    {
        $this->userService = $userService;
        $this->shopService = $shopService;
        $this->courseService = $courseService;
    }

    public function getMyShops($representative_id)
    {
        return $this->shopService->getMyShops($representative_id);
    }

    public function getShopDetail($id, $representative_id)
    {
        return $this->shopService->getById(compact('id'), $representative_id);
    }

    public function registerShop(ShopRequest $request)
    {
        $shop = $request->only(['representative_id', 'region_id', 'genre_id', 'name', 'description']);
        $image = $request->validated()['image'];

        return $this->shopService->register($shop, $image);
    }

    public function updateShop(ShopRequest $request, $id)
    {
        $newData = $request->only(['region_id', 'genre_id', 'name', 'description']);
        $newImage = (isset($request->validated()['image'])) ? $request->validated()['image'] : null;
        return $this->shopService->update(compact('id'), $newData, $newImage);
    }

    public function registerCourse(CourseRequest $request)
    {
        $course = $request->only(['shop_id', 'name', 'price', 'description']);

        return $this->courseService->register($course);
    }

    public function destroyCourse($id)
    {
        return $this->courseService->destroy(compact('id'));
    }

    public function registerRepresentative(RegisterRequest $request)
    {
        $user = $request->only(['name', 'email', 'password']);
        $user['group'] = 10;
        return $this->userService->register($user);
    }
}
