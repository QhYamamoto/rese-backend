<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class ReservationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'datetime' => 'required|date|after:now|',
            'number' => 'required|integer|min:1',
        ];
    }

    public function messages()
    {
        return [
            'datetime.required' => '時間が選択されていません。',
            'datetime.date' => '日時の入力形式が不正です。',
            'datetime.after' => '現在よりも後の日時を選択してください。',
            'number.required' => '人数が選択されていません。',
            'number.integer' => '人数の入力形式が不正です。',
            'number.min' => '人数は1人以上でなければなりません。',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $data = [
            'errors' => $validator->errors()->toArray(),
        ];
        throw new HttpResponseException(response()->json($data, 422));
    }
}
