<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class StoreCameraRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('administer-system');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'site_id' => [
                'required',
                'uuid',
                Rule::exists('sites', 'id')->where(function (Builder $query): void {
                    if ($this->user()?->organization_id) {
                        $query->where('organization_id', $this->user()->organization_id);
                    }
                }),
            ],
            'name' => ['required', 'string', 'max:150'],
            'code' => ['required', 'string', 'max:50', Rule::unique('cameras', 'code')],
            'stream_url' => ['required', 'string', 'max:2000', 'starts_with:rtsp://,rtsps://,http://,https://'],
            'protocol' => ['required', Rule::in(['rtsp', 'rtsps', 'http', 'https'])],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'direction_degrees' => ['nullable', 'numeric', 'between:0,359.99'],
            'status' => ['required', Rule::in(['online', 'offline', 'maintenance'])],
            'capabilities' => ['nullable', 'array'],
            'capabilities.*' => [Rule::in(['person', 'vehicle', 'plate', 'crowd', 'intrusion'])],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'code' => Str::upper(trim((string) $this->input('code'))),
            'capabilities' => $this->input('capabilities', []),
        ]);
    }
}
