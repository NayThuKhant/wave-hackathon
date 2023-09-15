<?php

namespace App\Http\Requests\Api;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'employee_id' => 'nullable|exists:users,id',
            'category_id' => 'required|exists:categories,id',
            'started_at' => 'required|date|date_format:Y-m-d H:i:s',
            'address_id' => 'nullable|exists:addresses,id',
            'address' => 'nullable|required_without:address_id',
            'address.floor' => 'nullable|required_without:address_id',
            'address.street' => 'nullable|required_without:address_id',
            'address.township' => 'nullable|required_without:address_id',
            'address.city' => 'nullable|required_without:address_id',
            'services' => 'required|array|min:1',
            'services.*.service_id' => 'required|exists:services,id',
            'services.*.quantity' => 'required|integer|min:1',
        ];
    }
}
