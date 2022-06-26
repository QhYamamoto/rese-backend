<?php

namespace Tests\Services;

use App\Repositories\Reservation\ReservationRepository;
use App\Repositories\Reservation\ReservationRepositoryInterface;
use App\Models\Reservation;
use App\Services\ReservationService;
use Tests\TestCase;
use Mockery;
use Stripe\Charge;
use Illuminate\Support\Str;

class ReservationServiceTest extends TestCase
{
    private $repositoryMock;
    private $chargeMock;
    private $service;
    private $attributes;

    public function setUp() : void
    {
        parent::setUp();

        $this->repositoryMock = Mockery::mock(ReservationRepository::class);
        $this->chargeMock = Mockery::mock(Charge::class);
        $this->service = new ReservationService(
            $this->instance(ReservationRepositoryInterface::class, $this->repositoryMock),
            $this->instance(Charge::class, $this->chargeMock),
        );
        $this->attributes = [
            'user_id' => 100,
            'shop_id' => 100,
            'course_id' => 100,
            'datetime' => '2030-02-03 23:23:23.00',
            'number' => 2,
        ];
    }

    public function testGetById()
    {
        $id = 100;

        $this->repositoryMock
            ->shouldReceive('getBy')
            ->once()
            ->with(compact('id'))
            ->andReturn(new Reservation($this->attributes));

        $result = $this->service->getById(compact('id'));
    
        $resultAttributes = $result->getData(true)['data'];
        $status = $result->getStatusCode();
    
        $this->assertEquals($this->attributes, $resultAttributes);
        $this->assertEquals(200, $status);
    }

    public function testGetByUserId()
    {
        $user_id = $this->attributes['user_id'];
        $this->repositoryMock
            ->shouldReceive('getAsCollectionBy')
            ->once()
            ->with(compact('user_id'))
            ->andReturn(array(new Reservation($this->attributes)));

        $result = $this->service->getByUserId(compact('user_id'));
    
        $resultData = $result->getData(true)['data'];
        $status = $result->getStatusCode();
    
        $this->assertEquals(1, count($resultData));
        $this->assertEquals(200, $status);
    }

    public function testRegister()
    {
        $existingReservationAttributes = $this->attributes;

        $this->repositoryMock
            ->shouldReceive([
                'getBy' => null,
                'create' => new Reservation($this->attributes),
            ])
            ->once();

        $result = $this->service->register($existingReservationAttributes, $this->attributes);

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
    
        $this->assertEquals('予約内容が変更されました。', $message);
        $this->assertEquals(200, $status);
    }

    public function testPay()
    {
        $id = 100;
        $payment = [
            'amount' => 5000,
            'source' => Str::random(),
        ];

        $this->repositoryMock
            ->shouldReceive([
                'getBy' => new Reservation(['advance_payment' => false]),
                'update' => new Reservation(['advance_payment' => true]),
            ])->once();

        $this->chargeMock
            ->shouldReceive('create')
            ->andReturn(null);

        $result = $this->service->pay($payment, compact('id'));

        $message = $result->getData(true)['message'];
        $status = $result->getStatusCode();

        $this->assertEquals('支払い処理が正常に完了しました。', $message);
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
        $this->assertEquals('予約を削除しました。', $message);
    }
}
