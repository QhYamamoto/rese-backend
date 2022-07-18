<?php

namespace Tests\Unit\Services;

use App\Repositories\Course\CourseRepository;
use App\Repositories\Course\CourseRepositoryInterface;
use App\Models\Course;
use App\Services\CourseService;
use Tests\TestCase;
use Mockery;

class CourseServiceTest extends TestCase
{
    private $repositoryMock;
    private $service;

    public function setUp() : void
    {
        parent::setUp();

        $this->repositoryMock = Mockery::mock(CourseRepository::class);
        $this->service = new CourseService(
            $this->instance(CourseRepositoryInterface::class, $this->repositoryMock)
        );
    }

    public function testRegister()
    {
        $attributes = [
            'shop_id' => 100,
            'name' => 'テストコース',
            'price' => 5000,
            'description' => 'テスト用のコースです。',
        ];

        $this->repositoryMock
            ->shouldReceive('create')
            ->once()
            ->with($attributes)
            ->andReturn(new Course($attributes));

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
        $this->assertEquals('コースを削除しました。', $message);
    }
}
