<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class ReviewRequest extends FormRequest
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
            'rate' => 'required|integer|min:1|max:5',
            'title' => 'sometimes|string|max:255',
            'content' => 'sometimes|string',
        ];
    }

    public function messages()
    {
        return [
            'rate.required' => '評価が選択されていません。',
            'rate.integer' => '評価の入力形式が不正です。',
            'rate.min' => '評価は1以上でなければなりません。',
            'rate.max' => '評価は5以下でなければなりません。',
            'title.string' => 'タイトルの入力形式が不正です。',
            'title.max' => 'タイトルは255文字以下でなければなりません。',
            'content.string' => '入力形式が不正です。',
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
