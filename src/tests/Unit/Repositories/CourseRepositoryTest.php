<?php

namespace Tests\Unit\Repositories;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Repositories\Course\CourseRepository;
use Illuminate\Support\Facades\Schema;
use App\Models\Course;

class CourseRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private $repository;
    private $attributes;

    public function setUp(): void
    {
        parent::setUp();
        $this->repository = new CourseRepository();
        $this->attributes = [
            'shop_id' => 100,
            'name' => 'テストコース',
            'price' => 3000,
            'description' => 'テストコースです。',
        ];

        Schema::disableForeignKeyConstraints();
    }

    public function testCreate()
    {
        $data = $this->repository->create($this->attributes);
        $this->assertInstanceOf(Course::class, $data);
        $this->assertDatabaseHas('courses', $this->attributes);
    }

    public function testDestroy()
    {
        $this->repository->create($this->attributes);
        $this->repository->delete($this->attributes);
        $this->assertDatabaseMissing('courses', $this->attributes);
    }
}
