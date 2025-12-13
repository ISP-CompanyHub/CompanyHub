<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSalaryLogRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('edit salaries');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => ['required', 'exists:users,id'],
            'period_from' => ['required', 'date'],
            'period_until' => [
                'required',
                'date',
                'after_or_equal:period_from',
                function ($attribute, $value, $fail) {
                    if ($this->period_from && \Carbon\Carbon::parse($value)->format('Y-m') !== \Carbon\Carbon::parse($this->period_from)->format('Y-m')) {
                        $fail('The period must be within the same month.');
                    }
                },
            ],
            'components' => ['required', 'array'],
            'components.*.name' => ['required', 'string', 'max:255'],
            'components.*.amount' => ['required', 'numeric'],
        ];
    }
}