<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Helpers\ApiResponse;

class BaseFormRequest extends FormRequest
{
    protected function failedValidation(Validator $validator)
    {
        $response = ApiResponse::fail($validator->errors()->toArray(),'Dữ liệu không hợp lệ',401);
        throw new HttpResponseException($response);
    }
}
