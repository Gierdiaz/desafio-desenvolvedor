<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FileRequest extends FormRequest
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
            'file' => 'required|file|max:50000|mimetypes:text/plain,text/csv,application/csv,application/vnd.ms-excel',
        ];
    }

    public function messages()
    {
        return [
            'file.required'  => 'O arquivo é obrigatório.',
            'file.file'      => 'O campo deve ser um arquivo válido.',
            'file.mimetypes' => 'O arquivo deve estar no formato CSV válido (texto ou compatível com Excel). Verifique se ele foi salvo corretamente como ".csv".',

        ];
    }
}
