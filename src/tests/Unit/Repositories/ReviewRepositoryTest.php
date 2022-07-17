<?php

namespace Tests\Unit\Repositories;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Repositories\Review\ReviewRepository;
use Illuminate\Support\Facades\Schema;
use App\Models\Review;

class ReviewRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private $repository;
    private $attributes;
    
    public function setUp(): void
    {
        parent::setUp();
        $this->repository = new ReviewRepository();
        $this->attributes = [
            'reservation_id' => 100,
            'rate' => 5,
            'title' => 'テストレビュー',
            'content' => 'テストレビューです。',
        ];

        Schema::disableForeignKeyConstraints();
    }

    public function testCreate()
    {
        $data = $this->repository->create($this->attributes);
        $this->assertInstanceOf(Review::class, $data);
        $this->assertDatabaseHas('reviews', $this->attributes);
    }

    public function testUpdate()
    {
        $updatedAttributes = [
            'rate' => 3,
            'title' => 'テストレビュー更新',
            'content' => 'テストレビューが更新されました。',
        ];

        $data = $this->repository->create($this->attributes);
        $result = $this->repository->update($updatedAttributes, ['id' => $data['id']]);
        $this->assertEquals(1, $result);
        $this->assertDatabaseMissing('reviews', $this->attributes);
        $this->assertDatabaseHas('reviews', $updatedAttributes);
    }

    
    public function testDestroy()
    {
        $data = $this->repository->create($this->attributes);
        $this->repository->delete($this->attributes);
        $this->assertDatabaseMissing('reviews', $this->attributes);
    }
}
