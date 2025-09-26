<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LocationSearchRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'radius' => 'required_without_all:min_lat,max_lat,min_lng,max_lng|numeric',
            'min_lat' => 'required_without:radius|numeric',
            'max_lat' => 'required_without:radius|numeric',
            'min_lng' => 'required_without:radius|numeric',
            'max_lng' => 'required_without:radius|numeric',
        ];
    }
}
