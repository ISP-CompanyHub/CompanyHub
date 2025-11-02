<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInterviewRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('schedule interviews');
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
            'scheduled_at' => ['required', 'date', 'after:now'],
            'location' => ['nullable', 'string', 'max:255', 'required_if:mode,in-person'],
            'mode' => ['required', 'string', 'in:in-person,remote,phone,video'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
