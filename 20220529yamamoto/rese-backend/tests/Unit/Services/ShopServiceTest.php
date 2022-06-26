<?php

namespace Tests\Unit\Services;

use App\Repositories\Shop\ShopRepository;
use App\Repositories\Shop\ShopRepositoryInterface;
use App\Repositories\User\UserRepository;
use App\Repositories\User\UserRepositoryInterface;
use App\Models\Shop;
use App\Models\User;
use App\Services\ShopService;
use Tests\TestCase;
use Mockery;
use Illuminate\Support\Str;

class ShopServiceTest extends TestCase
{
    private $shopRepositoryMock;
    private $userRepositoryMock;
    private $partiallyMockedService;
    private $attributes;

    public function setUp(): void
    {
        parent::setUp();

        $this->shopRepositoryMock = Mockery::mock(ShopRepository::class);
        $this->userRepositoryMock = Mockery::mock(UserRepository::class);

        /* サービスのstoreImageメソッドのみモック化 */
        $this->partiallyMockedService = Mockery::mock(
            ShopService::class,         // モック化するクラスを指定
            [   // 指定されたクラスのコンストラクタの引数を配列で指定(ここではモック化したリポジトリのインスタンスを渡している)
                $this->instance(ShopRepositoryInterface::class, $this->shopRepositoryMock),
                $this->instance(UserRepositoryInterface::class, $this->userRepositoryMock)
            ]
        );

        $this->partiallyMockedService
            ->makePartial()                 // 部分的なモックであることを明示
            ->shouldReceive('storeImage')   // サービスのなかでモック化するメソッドを指定
            ->andReturn('testImage.jpg');   // モック化されたメソッドの戻り値を指定

        $this->attributes = [
            'representative_id' => 100,
            'region_id' => 200,
            'genre_id' => 300,
            'name' => 'テスト店舗',
            'description' => 'テスト店舗です。',
        ];
    }

    public function testIndex()
    {
        $this->shopRepositoryMock
            ->shouldReceive('getAll')
            ->once()
            ->andReturn(array(new Shop($this->attributes)));

        $result = $this->partiallyMockedService->index();

        $resultData = $result->getData(true)['data'];
        $status = $result->getStatusCode();

        $this->assertEquals($this->attributes, $resultData[0]);
        $this->assertEquals(200, $status);
    }

    public function testGetById()
    {
        $id = 100;
        $representative_id = 100;

        $this->shopRepositoryMock
            ->shouldReceive('getById')
            ->once()
            ->andReturn(new Shop(compact('representative_id')));

        $result = $this->partiallyMockedService->getById($id, $representative_id);

        $resultData = $result->getData(true)['data'];
        $status = $result->getStatusCode();

        $this->assertEquals(compact('representative_id'), $resultData);
        $this->assertEquals(200, $status);
    }

    public function testGetFavoriteShops()
    {
        $user_id = 100;

        $this->userRepositoryMock
            ->shouldReceive('getBy')
            ->once()
            ->andReturn(new User());
        
        $this->shopRepositoryMock
            ->shouldReceive('getShops')
            ->once()
            ->with('id', [])
            ->andReturn([]);

        $result = $this->partiallyMockedService->getFavoriteShops(['id' => $user_id]);

        $resultDataNum = count($result->getData(true)['data']);
        $status = $result->getStatusCode();
        
        $this->assertEquals(0, $resultDataNum);
        $this->assertEquals(200, $status);
    }

    public function testGetMyShops()
    {
        $representative_id = 100;

        $this->shopRepositoryMock
            ->shouldReceive('getShops')
            ->once()
            ->with('representative_id', [$representative_id])
            ->andReturn([
                new Shop(compact('representative_id'))
            ]);

        $result = $this->partiallyMockedService->getMyShops($representative_id);

        $resultData = $result->getData(true)['data'];
        $status = $result->getStatusCode();

        $this->assertEquals(compact('representative_id'), $resultData[0]);
        $this->assertEquals(200, $status);
    }

    public function testRegister()
    {
        $image = Str::random();

        $expectedResultAttributes = $this->attributes += array('image' => 'testImage.jpg');

        $this->shopRepositoryMock
            ->shouldReceive('create')
            ->once()
            ->andReturn(new Shop($expectedResultAttributes));

        $result = $this->partiallyMockedService->register($this->attributes, $image);

        $resultAttributes = $result->getData(true)['newData'];
        $status = $result->getStatusCode();

        $this->assertEquals($expectedResultAttributes, $resultAttributes);
        $this->assertEquals(200, $status);
    }

    public function testUpdate()
    {
        $id = 100;
        $newImage = Str::random();

        $this->shopRepositoryMock
            ->shouldReceive([
                'getById' => new Shop(),
                'update' => null,
            ])
            ->once();
        
        $result = $this->partiallyMockedService->update(compact('id'), $this->attributes, $newImage);

        $message = $result->getData(true)['message'];
        $status = $result->getStatusCode();

        $this->assertEquals('店舗データが更新されました。', $message);
        $this->assertEquals(200, $status);
    }
}
