<?php

namespace App\Http\Requests;

use App\Models\Report;
use Illuminate\Foundation\Http\FormRequest;

class StoreReportRequest extends FormRequest
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
     * @return array<string, string>
     */
    public function rules(): array
    {
        $categories = implode(',', (array) config('report.categories', []));
        $imageMaxKilobytes = (int) config('report.image.max_kilobytes', 5120);
        $imageMimes = implode(',', (array) config('report.image.mimes', [
            'jpg', 'jpeg', 'png', 'webp',
        ]));

        return [
            'type' => 'required|string|in:lost,found',
            'title' => 'required|string|max:'.Report::MAX_LENGTH_TITLE,
            'category' => "required|string|in:{$categories}",
            'description' => 'required|string|max:5000',
            'color' => 'nullable|string|max:'.Report::MAX_LENGTH_COLOR,
            'location' => 'required|string|max:'.Report::MAX_LENGTH_LOCATION,
            'event_date' => 'required|date',
            'image' => "nullable|image|mimes:{$imageMimes}|max:{$imageMaxKilobytes}",
        ];
    }
}
