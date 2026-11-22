<?php

namespace App\Http\Requests;

use App\Models\Report;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateReportRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $categories = implode(',', (array) config('report.categories', []));
        $imageMaxKilobytes = (int) config('report.image.max_kilobytes', 5120);
        $imageMimes = implode(',', (array) config('report.image.mimes', [
            'jpg', 'jpeg', 'png', 'webp']
        ));

        return [
            'type' => 'sometimes|required|string|in:lost,found',
            'title' => 'sometimes|required|string|max:'.Report::MAX_LENGTH_TITLE,
            'category' => "sometimes|required|string|in:{$categories}",
            'description' => 'sometimes|required|string|max:5000',
            'color' => 'sometimes|nullable|string|max:'.Report::MAX_LENGTH_COLOR,
            'location' => 'sometimes|required|string|max:'.Report::MAX_LENGTH_LOCATION,
            'event_date' => 'sometimes|required|date',
            'image' => "sometimes|nullable|image|mimes:{$imageMimes}|max:{$imageMaxKilobytes}",
        ];
    }
}
