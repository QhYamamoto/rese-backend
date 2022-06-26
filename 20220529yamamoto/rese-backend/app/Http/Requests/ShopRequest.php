<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\File\File;

class ShopRequest extends FormRequest
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
            'region_id' => 'required_with:representative_id',
            'genre_id' => 'required_with:representative_id',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'required_with:representative_id|image'
        ];
    }

    public function messages()
    {
        return [
            'region_id.required_with' => '地域を選択してください。',
            'genre_id.required_with' => 'ジャンルを選択してください。',
            'name.required' => '店名が入力されていません。',
            'name.string' => '店名の入力形式が不正です。',
            'name.min' => '店名は255文字以下でなければなりません。',
            'description.required' => '店舗説明が入力されていません。',
            'description.string' => '店舗説明の入力形式が不正です。',
            'image.required_with' => '画像を選択してください',
            'image.image' => 'アップロードされたファイルは画像データではありません。',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $data = [
            'errors' => $validator->errors()->toArray(),
        ];
        throw new HttpResponseException(response()->json($data, 422));
    }

    public function validationData()
    {
        $all = parent::validationData();

        /* 余分な文字列がある場合は除去 */
        if ($this->get('base64EncodedImage')) {
            $data = explode(',', $this->get('base64EncodedImage'));
            if (isset($data[1])) {
                $fileData = base64_decode($data[1]);
            } else {
                $fileData = base64_decode($data[0]);
            }
            
            // tmp領域に画像ファイルとして保存、UploadedFileに変換
            $tmpFilePath = sys_get_temp_dir() . '/' . Str::uuid()->toString();
            file_put_contents($tmpFilePath, $fileData);
            $tmpFile = new File($tmpFilePath);
            $filename = $tmpFile->getFilename();
            
            /* UploadedFileに変換 */
            $file = new UploadedFile(
                $tmpFile->getPathname(),
                $filename,
                $tmpFile->getMimeType(),
                0,
                true
            );
            $all['image'] = $file;
        }
        
        return $all;
    }
}
