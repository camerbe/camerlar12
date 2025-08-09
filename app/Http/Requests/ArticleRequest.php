<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ArticleRequest extends FormRequest
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
        return [
            'info' => 'required',
            'fkrubrique' => 'required',
            'fkuser' => 'required|numeric',
            'slug' => 'required|unique:articles',
            'dateparution' => 'required|date',
            'fkpays' => 'required',
            'titre' => 'required',
            'auteur' => 'required',
            'source' => 'required',
            'image' => 'required',
        ];
    }
}
