<?php

namespace App\Http\Requests\Api;

use App\Enums\OrderStatus;
use App\Helpers\Enum;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateOrderStatusRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return request()->order->employee_id == Auth::id() || request()->order->employer_id == Auth::id();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        $statuses = Enum::make(OrderStatus::class)->values();

        return [
            'status' => 'required|in:'.implode(',', $statuses),
        ];
    }
}
