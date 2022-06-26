<?php

namespace App\Services;

use App\Repositories\Reservation\ReservationRepositoryInterface;
use Stripe\Stripe;
use Stripe\Charge;

class ReservationService extends Service
{
    protected $reservationRepository;
    protected $charge;

    public function __construct(ReservationRepositoryInterface $reservationRepository, Charge $charge)
    {
        $this->reservationRepository = $reservationRepository;
        $this->charge = $charge;
    }

    public function getById($id)
    {
        try {
            $data = $this->reservationRepository->getBy($id);
            return $this->jsonResponse(compact('data'));
        } catch (\Throwable $th) {
            return $this->errorResponse($th);
        }
    }

    public function getByUserId($user_id)
    {
        try {
            $data = $this->reservationRepository->getAsCollectionBy($user_id);
            return $this->jsonResponse(compact('data'));
        } catch (\Throwable $th) {
            return $this->errorResponse($th);
        }
    }

    public function register($existingReservationAttributes, $newReservationAttributes)
    {
        /* 同一店舗、同一日時の予約がないかチェック */
        try {
            $existingReservation = $this->reservationRepository->getBy($existingReservationAttributes);
        } catch (\Throwable $th) {
            return $this->errorResponse($th);
        }

        if ($existingReservation) {
            return $this->errorResponse(false, 'すでに同一日時の予約が存在します。');
        }

        /* なければ予約を新規作成 */
        try {
            $newData = $this->reservationRepository->create($newReservationAttributes);
            return $this->jsonResponse(compact('newData'));
        } catch (\Throwable $th) {
            return $this->errorResponse($th);
        }
    }

    public function update($attributes, $id)
    {
        try {
            $this->reservationRepository->update($attributes, $id);
            return $this->jsonResponse(['message' => '予約内容が変更されました。']);
        } catch (\Throwable $th) {
            return $this->errorResponse($th);
        }
    }

    public function pay($payment, $id)
    {
        try {
            $data = $this->reservationRepository->getBy($id);
        } catch (\Throwable $th) {
            $this->errorResponse($th);
            return $this->errorResponse($th);
        }

        /* 既に支払い済みの場合はメッセージを返す */
        if ($data->advance_payment) {
            return $this->errorResponse(false, 'お支払いはすでに完了しています。', 422);
        }

        /* Stripeの秘密鍵をセット */
        Stripe::setApiKey(config('env.stripe_sk'));

        /* 支払処理に必要な情報を$paymentに追加 */
        $payment += array(
            'currency' => 'jpy',
            'description' => 'レストラン予約サービスReseを利用した事前決済',
        );

        /* 支払処理 */
        try {
            $this->charge->create($payment);
        } catch (\Throwable $th) {
            return $this->errorResponse($th, '支払処理に失敗しました。時間をおいてから再度お試しください。', 422);
        }

        /* 該当レコードのadvance_paymentカラムをtrueに更新 */
        try {
            $this->reservationRepository->update(['advance_payment' => true], $id);
            return $this->jsonResponse(['message' => '支払い処理が正常に完了しました。']);
        } catch (\Throwable $th) {
            return $this->errorResponse($th, '支払い処理は正常に行われましたが、データベースの更新に失敗しました。'."\n".'お手数をお掛けいたしますが、Rese運営事務局までご連絡下さい。');
        }
    }

    public function destroy($id)
    {
        try {
            $this->reservationRepository->delete($id);
            return $this->jsonResponse(['message' => '予約を削除しました。']);
        } catch (\Throwable $th) {
            return $this->errorResponse($th);
        }
    }
}
