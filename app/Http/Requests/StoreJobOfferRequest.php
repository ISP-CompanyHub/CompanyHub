<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreJobOfferRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create job offers');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'candidate_id' => ['required', 'exists:candidates,id'],
            'salary' => ['required', 'numeric', 'min:0'],
            'start_date' => ['required', 'date', 'after:today'],
            'expires_at' => ['required', 'date', 'after:start_date'],
        ];
    }
}
