<?php

namespace Tests\Unit\Repositories;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Repositories\Favorite\FavoriteRepository;
use Illuminate\Support\Facades\Schema;
use App\Models\Favorite;

class FavoriteRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private $repository;
    private $attributes;

    public function setUp(): void
    {
        parent::setUp();
        $this->repository = new FavoriteRepository();
        $this->attributes = [
            'user_id' => 100,
            'shop_id' => 100,
        ];

        Schema::disableForeignKeyConstraints();
    }

    public function testCreate()
    {
        $data = $this->repository->create($this->attributes);
        $this->assertInstanceOf(Favorite::class, $data);
        $this->assertDatabaseHas('favorites', $this->attributes);
    }

    public function testDestroy()
    {
        $this->repository->create($this->attributes);
        $this->repository->delete($this->attributes);
        $this->assertDatabaseMissing('favorites', $this->attributes);
    }
}
