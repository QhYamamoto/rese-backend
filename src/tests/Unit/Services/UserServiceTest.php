<?php

namespace Tests\Unit\Services;

use App\Repositories\User\UserRepository;
use App\Repositories\User\UserRepositoryInterface;
use App\Actions\Fortify\CreateNewUser;
use Illuminate\Contracts\Auth\StatefulGuard;
use App\Models\User;
use App\Services\UserService;
use App\Http\Resources\UserResource;
use Tests\TestCase;
use Mockery;
use Hash;
use Illuminate\Support\Str;

class UserServiceTest extends TestCase
{
    private $repositoryMock;
    private $creatorMock;
    private $guardMock;
    private $service;

    public function setUp() : void
    {
        parent::setUp();

        $this->repositoryMock = Mockery::mock(UserRepository::class);
        $this->guardMock = Mockery::mock(StatefulGuard::class);
        $this->creatorMock = Mockery::mock(CreateNewUser::class);
        $this->service = new UserService(
            $this->instance(UserRepositoryInterface::class, $this->repositoryMock),
            $this->instance(StatefulGuard::class, $this->guardMock),
            $this->instance(CreateNewUser::class, $this->creatorMock)
        );
    }

    public function testLogin()
    {
        $userMock = Mockery::mock(User::class)->makePartial();
        $email = 'test@ex.com';
        $pseudoToken = new \stdClass();
        $pseudoToken->plainTextToken = Str::random();

        $userMock
            ->shouldReceive('createToken')
            ->once()
            ->with('token', ['normal'])
            ->andReturn($pseudoToken);

        $userMock->password = Hash::make('password');
        $userMock->email_verified_at = '2022-02-02 22:22:22.22';

        $this->repositoryMock
            ->shouldReceive('getBy')
            ->once()
            ->with(compact('email'))
            ->andReturn(new UserResource($userMock));

        $result = $this->service->login(compact('email'), 'password');

        $resultToken = $result->getData(true)['token'];
        $status = $result->getStatusCode();

        $this->assertEquals($pseudoToken->plainTextToken, $resultToken);
        $this->assertEquals(200, $status);
    }
}
