<?php

namespace App\Services;

use App\Repositories\Shop\ShopRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Support\Facades\Storage;

class ShopService extends Service
{
    protected $shopRepository;
    protected $userRepository;

    public function __construct(ShopRepositoryInterface $shopRepository, UserRepositoryInterface $userRepository)
    {
        $this->shopRepository = $shopRepository;
        $this->userRepository = $userRepository;
    }

    public function index()
    {
        try {
            $data = $this->shopRepository->getAll();
            return $this->jsonResponse(compact('data'));
        } catch (\Throwable $th) {
            return $this->errorResponse($th);
        }
    }

    public function getById($id, $representative_id = null)
    {
        $necessaryData = ['region', 'genre', 'courses'];

        /* 店舗責任者の場合は予約情報も追加 */
        if ($representative_id) {
            array_push($necessaryData, 'reservations', 'reservations.review', 'reservations.user');
        }

        try {
            $data = $this->shopRepository->getById($id, $necessaryData);
        } catch (\Throwable $th) {
            return $this->errorResponse($th);
        }
        
        /* 店舗責任者が自分の店舗以外の情報にアクセスしようとした場合 */
        if ($representative_id && $data->representative_id !== +$representative_id) {
            return $this->errorResponse(false, 'この店舗の情報を表示することはできません。', 401);
        }
        
        return $this->jsonResponse(compact('data'));
    }

    public function getFavoriteShops($user_id)
    {
        try {
            $user = $this->userRepository->getBy($user_id);
        } catch (\Throwable $th) {
            return $this->errorResponse($th);
        }
        
        /* ユーザーがお気に入り登録している店舗のIdを配列に格納 */
        $shops_id = [];
        foreach ($user->shop as $shop) {
            array_push($shops_id, $shop->id);
        }
        
        try {
            $data = $this->shopRepository->getShops('id', $shops_id);
            return $this->jsonResponse(compact('data'));
        } catch (\Throwable $th) {
            return $this->errorResponse($th);
        }
    }

    public function getMyShops($representative_id)
    {
        try {
            $data = $this->shopRepository->getShops('representative_id', [$representative_id]);
            return $this->jsonResponse(compact('data'));
        } catch (\Throwable $th) {
            return $this->errorResponse($th);
        }
    }

    public function register($attributes, $image)
    {
        /* 画像を保存、ファイル名を$attributesに追加 */
        $attributes['image'] = $this->storeImage($image);

        try {
            $newData = $this->shopRepository->create($attributes);
            return $this->jsonResponse(compact('newData'));
        } catch (\Throwable $th) {
            return $this->errorResponse($th);
        }
    }

    public function update($id, $attributes, $newImage = null)
    {
        if ($newImage) {
            /* 既存画像を削除、新規画像を保存し、後者のファイル名を$attributesに追加 */
            try {
                $data = $this->shopRepository->getById(compact('id'));
                $attributes['image'] = $this->storeImage($newImage, $data);
            } catch (\Throwable $th) {
                return $this->errorResponse($th);
            }
        }

        try {
            $this->shopRepository->update($attributes, $id);
            return $this->jsonResponse(['message' => '店舗データが更新されました。']);
        } catch (\Throwable $th) {
            return $this->errorResponse($th);
        }
    }

    public function storeImage($image, $existingData = null)
    {
        /* データが渡されていれば、既存の画像を削除*/
        if ($existingData) {
            Storage::delete(config('env.images_path').'/'.$existingData->image);
        }
        /* 画像をストレージに保存、相対パスを変数に格納 */
        $imagePath = $image->store(config('env.images_path'));
        
        /* ファイル名を返却 */
        return basename($imagePath);
    }
}
