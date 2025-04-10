<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransactionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user() && auth()->user()->can('transaction');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            '*.id_product' => 'required|exists:products,id',
            '*.quantity' => 'required|integer|min:1',
            '*.price' => 'required|numeric|min:0',
            '*.sub_total' => 'required|numeric|min:0',
            '*.total' => 'required|numeric|min:0',
        ];
    }
}