<?php

namespace Tests\Unit\Repositories;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Repositories\Shop\ShopRepository;
use Illuminate\Support\Facades\Schema;
use App\Models\Shop;
use App\Http\Resources\ShopResource;
use App\Http\Resources\ShopCollection;

class ShopRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private $repository;
    private $attributes;

    public function setUp(): void
    {
        parent::setUp();

        $this->repository = new ShopRepository();
        $this->attributes = [
            'representative_id' => 100,
            'region_id' => 100,
            'genre_id' => 100,
            'name' => 'テスト店舗',
            'description' => 'テスト店舗です。',
            'image' => 'testImage.jpg',
        ];

        Schema::disableForeignKeyConstraints();
    }

    
    public function testGetAll()
    {
        $this->repository->create($this->attributes);
        $data = $this->repository->getAll();
        $this->assertInstanceOf(ShopCollection::class, $data);
        foreach ($this->attributes as $key => $attribute) {
            $this->assertEquals($attribute, $data[0][$key]);
        }
    }

    public function testGetById()
    {
        $data = $this->repository->create($this->attributes);
        $gotData = $this->repository->getById(['id' => $data['id']]);
        $this->assertInstanceOf(ShopResource::class, $gotData);
        foreach ($this->attributes as $key => $attribute) {
            $this->assertEquals($attribute, $gotData[$key]);
        }
    }

    public function testGetAsCollectionWhere()
    {
        $this->repository->create($this->attributes);
        $data = $this->repository->getAsCollectionWhere('representative_id', [100]);
        $this->assertInstanceOf(ShopCollection::class, $data);
        foreach ($this->attributes as $key => $attribute) {
            $this->assertEquals($attribute, $data[0][$key]);
        }
    }

    public function testCreate()
    {
        $data = $this->repository->create($this->attributes);
        $this->assertInstanceOf(Shop::class, $data);
        $this->assertDatabaseHas('shops', $this->attributes);
    }

    public function testUpdate()
    {
        $updatedAttributes = [
            'representative_id' => 100,
            'region_id' => 200,
            'genre_id' => 200,
            'name' => 'テスト店舗更新',
            'description' => 'テスト店舗が更新されました。',
            'image' => 'updatedTestImage.jpg'
        ];

        $data = $this->repository->create($this->attributes);
        $result = $this->repository->update($updatedAttributes, ['id' => $data['id']]);
        $this->assertEquals(1, $result);
        $this->assertDatabaseHas('shops', $updatedAttributes);
    }
}
