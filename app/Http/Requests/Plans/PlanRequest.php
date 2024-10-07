<?php

namespace App\Http\Requests\Plans;

use Illuminate\Foundation\Http\FormRequest;

class PlanRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = request()->user();
        return $user->hasRole('admin');
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            "name" => "required|string|max:200",
            "description"=>"required|string",
            "price"=>"required|numeric|regex:/^\d+(\.\d{1,2})?$/|min:0|",
            "duration"=>"required|numeric",
            "duration_unit"=>"required|in:days,months,years"
        ];
    }
}
