<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTaskRequest extends FormRequest
{
    /**
     * @return array
     */
    public function validationData()
    {
        return [
            'id' => $this->route('id'),
            'title' => $this->input('title'),
            'description' => $this->input('description'),
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id' => 'required|integer|exists:tasks',
            'title' => 'required|string',
            'description' => 'required|string'
        ];
    }
}
