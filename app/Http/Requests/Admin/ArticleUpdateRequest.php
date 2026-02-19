<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ArticleUpdateRequest extends FormRequest
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
            'title' => 'min:3|max:255|unique:articles,title,' . $this->id,
            'description' => 'min:10',
            'content' => 'min:10',
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
            'title' => 'título',
            'description' => 'descripción',
            'content' => 'contenido',
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
            'title.required' => 'El título es obligatorio.',
            'title.min' => 'El título ha de tener 3 caracteres como mínimo.',
            'title.max' => 'El título no puede superar los 255 caracteres.',
            'title.unique' => 'Ese título ya existe.',

            'description.min' => 'La descripción ha de tener mínimo 10 caracteres.',

            'content.min' => 'El contenido ha de tener mínimo 10 caracteres.',
        ];
    }
}
