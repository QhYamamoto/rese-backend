<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class RegisterRequest extends FormRequest
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
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:8|max:255',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'ユーザー名が入力されていません。',
            'name.string' => 'ユーザー名の入力形式が不正です。',
            'name.max' => 'ユーザー名は255文字以内で入力してください。',
            'email.required' => 'メールアドレスが入力されていません。',
            'email.email' => 'メールアドレスの入力形式が不正です。',
            'email.max' => 'メールアドレスは255文字以内で入力してください。',
            'email.unique' => '入力されたメールアドレスは既に登録されています。',
            'password.required' => 'パスワードが入力されていません。',
            'password.min' => 'パスワードは8文字以上で入力してください。',
            'password.max' => 'パスワードは255文字以内で入力してください。',
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
