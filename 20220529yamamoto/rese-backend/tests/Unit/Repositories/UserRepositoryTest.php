<?php

namespace Tests\Unit\Repositories;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Repositories\User\UserRepository;
use App\Models\User;
use Hash;

class UserRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private $repository;
    private $attributes;

    public function setUp(): void
    {
        parent::setUp();

        $this->repository = new UserRepository();
        $this->attributes = [
            'name' => 'テスト',
            'email' => 'test@ex.com',
            'password' => Hash::make('password'),
        ];
    }

    public function testGetBy()
    {
        User::create($this->attributes);

        $data = $this->repository->getBy($this->attributes);
        $this->assertInstanceOf(User::class, $data);
        $this->assertEquals($data->name, $this->attributes['name']);
    }
}
