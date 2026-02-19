<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class TagCreateRequest extends FormRequest
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
            'name' => 'required|max:255|unique:tags,name',
            'color' => 'required|unique:tags,color',
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
            'name.required' => 'El nombre de la categoría es obligatorio.',
            'name.max' => 'La categoría no puede tener más de 255 caracteres.',
            'name.unique' => 'Ya existe una categoría con ese nombre.',

            'color.required' => 'El color de la categoría es obligatorio.',
            'color.unique' => 'Ese color ya está en uso.'
        ];
    }
}
