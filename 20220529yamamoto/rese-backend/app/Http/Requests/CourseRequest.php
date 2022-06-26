<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class CourseRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'price' => 'required|integer|min:1',
            'description' => 'required|string',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'コース名が入力されていません。',
            'name.string' => 'コース名の入力形式が不正です。',
            'name.min' => 'コース名は255文字以下でなければなりません。',
            'price.required' => '価格が入力されていません。',
            'price.integer' => '価格は整数でなければなりません。',
            'price.min' => '価格は正の整数でなければなりません。',
            'description.required' => 'コース説明が入力されていません。',
            'description.string' => 'コース説明の入力形式が不正です。',
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
