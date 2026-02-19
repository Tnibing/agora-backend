<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class TagUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // only allow updates if the user is logged in
        return backpack_auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'max:255|unique:tags,name,' . $this->id,
            'color' => 'unique:tags,color,' . $this->id,
        ];
    }

    /**
     * Get the validation attributes that apply to the request.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'name' => 'nombre de categoría',
            'color' => 'color',
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.max' => 'La categoría no puede tener más de 255 caracteres.',
            'name.unique' => 'Ya existe una categoría con ese nombre.',

            'color.unique' => 'Ese color ya está en uso.'
        ];
    }
}
