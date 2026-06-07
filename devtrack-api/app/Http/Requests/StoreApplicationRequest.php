<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'job' => 'required|array',
            'job.external_job_id' => 'required|string',
            'job.title' => 'required|string',
            'job.company' => 'required|string',
            'status' => 'nullable|string|in:saved,applied,interview,offer,rejected',
            'notes' => 'nullable|string',
        ];
    }
}
