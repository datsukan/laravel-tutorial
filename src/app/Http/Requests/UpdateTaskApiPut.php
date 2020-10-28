<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateTaskApiPut extends FormRequest
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
            'task' => 'required|string|max:100|unique:tasks',
        ];
    }

    /**
     * Set custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'task' => 'タスク',
        ];
    }

    /**
     * Set the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages() {
        return [
            'task.required' => ':attributeは必須項目です。',
            'task.string' => ':attributeは文字を入力してください。',
            'task.max' => ':attributeは:max文字以内で入力してください。',
            'task.unique' => ':attributeは既に登録されています。',
        ];
    }

    protected function failedValidation(Validator $validator) : void
    {
        $res = response()->json([
            'errors' => $validator->errors(),
        ], 422);
        throw new HttpResponseException($res);
    }
}
