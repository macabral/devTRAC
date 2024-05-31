<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SprintsRequestUpdate extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {

        $id = base64_decode($this->id);

        return [
            'version' => [
                'required',
                'max:30',
                Rule::unique('sprints')
                    ->where('projects_id', $this->input('projects_id'))
                    ->ignore($id)

             ],
            'description' => 'max:255',
            'status' => 'required',
            'projects_id' => 'required'
        ];
    }
}
