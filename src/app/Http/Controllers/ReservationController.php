<?php

namespace App\Http\Controllers;

use App\Services\ReservationService;
use Illuminate\Http\Request;
use App\Http\Requests\ReservationRequest;

class ReservationController extends Controller
{
    protected $reservationService;

    public function __construct(ReservationService $reservationService)
    {
        $this->reservationService = $reservationService;
    }

    public function getById($id)
    {
        return $this->reservationService->getById(compact('id'));
    }

    public function getByUserId($user_id)
    {
        return $this->reservationService->getByUserId(compact('user_id'));
    }

    public function register(ReservationRequest $request)
    {
        $existingReservation = $request->only('user_id', 'shop_id', 'datetime');
        $newReservation = $request->only('user_id', 'shop_id', 'course_id', 'datetime', 'number');

        return $this->reservationService->register($existingReservation, $newReservation);
    }

    public function update(ReservationRequest $request, $id)
    {
        $newData = $request->only('course_id', 'datetime', 'number');

        return $this->reservationService->update($newData, compact('id'));
    }

    public function completeVisit($id)
    {
        return $this->reservationService->update(['visit_completed' => true], compact('id'));
    }

    public function pay(Request $request, $id)
    {
        $payment = $request->only('amount', 'source');

        return $this->reservationService->pay($payment, compact('id'));
    }

    public function destroy($id)
    {
        return $this->reservationService->destroy(compact('id'));
    }
}
