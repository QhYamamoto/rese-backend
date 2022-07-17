<?php

namespace Tests\Unit\Services;

use App\Repositories\Review\ReviewRepository;
use App\Repositories\Review\ReviewRepositoryInterface;
use App\Models\Review;
use App\Services\ReviewService;
use Tests\TestCase;
use Mockery;

class ReviewServiceTest extends TestCase
{
    private $repositoryMock;
    private $service;
    private $attributes;

    public function setUp() : void
    {
        parent::setUp();

        $this->repositoryMock = Mockery::mock(ReviewRepository::class);
        $this->service = new ReviewService(
            $this->instance(ReviewRepositoryInterface::class, $this->repositoryMock)
        );
        $this->attributes = [
            'reservation_id' => 100,
            'rate' => 5,
            'title' => 'テストレビュー',
            'content' => 'これはテストレビューです。'
        ];
    }

    public function testRegister()
    {
        $this->repositoryMock
            ->shouldReceive('create')
            ->once()
            ->with($this->attributes)
            ->andReturn(new Review($this->attributes));

        $result = $this->service->register($this->attributes);

        $resultAttributes = $result->getData(true)['newData'];
        $status = $result->getStatusCode();

        $this->assertEquals($this->attributes, $resultAttributes);
        $this->assertEquals(200, $status);
    }

    public function testUpdate()
    {
        $id = 100;
        $this->repositoryMock
            ->shouldReceive('update')
            ->once()
            ->with($this->attributes, compact('id'));

        $result = $this->service->update($this->attributes, compact('id'));
    
        $message = $result->getData(true)['message'];
        $status = $result->getStatusCode();
    
        $this->assertEquals('レビューが変更されました。', $message);
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
        $this->assertEquals('レビューを削除しました。', $message);
    }
}
