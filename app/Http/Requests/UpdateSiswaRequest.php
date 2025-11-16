<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateSiswaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->hasAnyRole(['admin']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $siswa = $this->route('siswa'); // Ambil siswa dari route parameter
        return [
            'name'          => ['required', 'string', 'max:255'],
            'avatar'        => ['nullable','image','mimes:png,jpg,jpeg'],
            'email'         => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique('users')->ignore($siswa->user->id), // Abaikan email siswa yang sedang diupdate
            ],
            'password'      => ['nullable', 'string', 'min:6'],
            'kelas_id'      => ['required','integer'],
            'kelompok_id'     => ['required','integer'],
        ];
    }
}
