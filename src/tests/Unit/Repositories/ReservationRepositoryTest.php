<?php

namespace Tests\Unit\Repositories;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Repositories\Reservation\ReservationRepository;
use Illuminate\Support\Facades\Schema;
use App\Models\Reservation;
use App\Http\Resources\ReservationResource;
use App\Http\Resources\ReservationCollection;

class ReservationRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private $repository;
    private $attributes;
    
    public function setUp(): void
    {
        parent::setUp();
        $this->repository = new ReservationRepository();
        $this->attributes = [
            'user_id' => 100,
            'shop_id' => 100,
            'course_id' => 100,
            'datetime' => '2030-02-03 23:23:23',
            'number' => 1,
        ];

        Schema::disableForeignKeyConstraints();
    }

    public function testGetBy()
    {
        $createdData = $this->repository->create($this->attributes);
        $returnedData = $this->repository->getBy(['id' => $createdData['id']]);
        $this->assertInstanceOf(ReservationResource::class, $returnedData);
        foreach ($this->attributes as $key => $attribute) {
            $this->assertEquals($attribute, $returnedData[$key]);
        }
    }

    public function testGetAsCollectionBy()
    {
        $this->repository->create($this->attributes);
        $returnedData = $this->repository->getAsCollectionBy($this->attributes);
        $this->assertInstanceOf(ReservationCollection::class, $returnedData);
        foreach ($this->attributes as $key => $attribute) {
            $this->assertEquals($attribute, $returnedData[0][$key]);
        }
    }

    public function testCreate()
    {
        $data = $this->repository->create($this->attributes);
        $this->assertInstanceOf(Reservation::class, $data);
        $this->assertDatabaseHas('reservations', $this->attributes);
    }

    public function testUpdate()
    {
        $updatedAttributes = [
            'course_id' => 200,
            'datetime' => '2030-02-03 23:23:23',
            'number' => 2,
        ];

        $this->repository->create($this->attributes);
        $result = $this->repository->update($updatedAttributes, $this->attributes);
        $this->assertEquals(1, $result);
        $this->assertDatabaseHas('reservations', $updatedAttributes);
    }

    public function testDestroy()
    {
        $this->repository->create($this->attributes);
        $this->repository->delete($this->attributes);
        $this->assertDatabaseMissing('reservations', $this->attributes);
    }
}
