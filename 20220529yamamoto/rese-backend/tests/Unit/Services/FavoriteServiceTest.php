<?php

namespace Tests\Services;

use App\Repositories\Favorite\FavoriteRepository;
use App\Repositories\Favorite\FavoriteRepositoryInterface;
use App\Models\Favorite;
use App\Services\FavoriteService;
use Tests\TestCase;
use Mockery;

class FavoriteServiceTest extends TestCase
{
    private $repositoryMock;
    private $service;

    public function setUp() : void
    {
        parent::setUp();

        $this->repositoryMock = Mockery::mock(FavoriteRepository::class);
        $this->service = new FavoriteService(
            $this->instance(FavoriteRepositoryInterface::class, $this->repositoryMock)
        );
    }

    public function testRegister()
    {
        $attributes = [
            'user_id' => 100,
            'shop_id' => 200,
        ];

        $this->repositoryMock
            ->shouldReceive('create')
            ->once()
            ->with($attributes)
            ->andReturn(new Favorite($attributes));

        $result = $this->service->register($attributes);

        $resultAttributes = $result->getData(true)['newData'];
        $status = $result->getStatusCode();

        $this->assertEquals($attributes, $resultAttributes);
        $this->assertEquals(200, $status);
    }

    public function testDestroy()
    {
        $id = 100;
        $this->repositoryMock
            ->shouldReceive('delete')
            ->once()
            ->with(compact('id'));
        
        $result = $this->service->destroy(compact('id'));

        $message = $result->getData(true)['message'];
        $status = $result->getStatusCode();
        
        $this->assertEquals(200, $status);
        $this->assertEquals('お気に入りを削除しました。', $message);
    }
}
