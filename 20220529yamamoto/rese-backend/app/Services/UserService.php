<?php

namespace App\Services;

use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Contracts\Auth\StatefulGuard;
use Laravel\Fortify\Contracts\LogoutResponse;
use Illuminate\Auth\Events\Registered;
use Laravel\Fortify\Contracts\RegisterResponse;
use App\Actions\Fortify\CreateNewUser;
use App\Http\Resources\UserResource;
use Hash;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\RedirectResponse;

class UserService extends Service
{
    protected $userRepository;
    protected $guard;
    protected $creator;

    public function __construct(UserRepositoryInterface $userRepository, StatefulGuard $guard, CreateNewUser $creator)
    {
        $this->userRepository = $userRepository;
        $this->guard = $guard;
        $this->creator = $creator;
    }

    public function register($attributes)
    {
        /* パスワードをハッシュ化 */
        $attributes['password'] = Hash::make($attributes['password']);
        
        try {
            event(new Registered($user = $this->creator->create($attributes)));
            $this->guard->login($user);
        } catch (\Throwable $th) {
            return $this->errorResponse($th);
        }

        return app(RegisterResponse::class);
    }

    public function login($email, $password)
    {
        try {
            $user = $this->userRepository->getBy($email);
        } catch (\Throwable $th) {
            return $this->errorResponse($th);
        }
        
        /* アドレス、パスワードが正しいか検証 */
        if (!$user) {
            return $this->errorResponse(false, 'このメールアドレスは登録されていません。');
        } elseif (!Hash::check($password, $user->password)) {
            return $this->errorResponse(false, 'パスワードに誤りがあります。ご確認の上再度お試しください。');
        }

        /* メールアドレスが検証済みか確認 */
        if (!$user->email_verified_at) {
            return $this->errorResponse(false, 'お客様のアカウントはメールアドレスの検証が完了していません。会員登録時にお送りしたメールをご確認ください。');
        }

        /* groupカラムの値に応じて権限を付与 */
        if ($user->group === 100) {
            $token = $user->createToken('token', ['administrator'])->plainTextToken;
        } elseif ($user->group === 10) {
            $token = $user->createToken('token', ['shop-owner'])->plainTextToken;
        } else {
            $token = $user->createToken('token', ['normal'])->plainTextToken;
        }

        return $this->jsonResponse(compact('token'));
    }

    public function logout($request)
    {
        $this->guard->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return app(LogoutResponse::class);
    }

    public function verifyEmail($id): RedirectResponse
    {
        $user = $this->userRepository->getBy($id);
        
        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return redirect(config('env.spa_url') . '?verified=1');
    }

    public function me($request)
    {
        return $this->jsonResponse(['user' => new UserResource($request->user())]);
    }
}
