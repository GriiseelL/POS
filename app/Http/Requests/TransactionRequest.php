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
        return true;
    }

   protected function prepareForValidation()
   {
       $this->merge([
           'seller' => auth()->user()->name ?? 'default_seller',
       ]);
   }



    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // 'id_product' => 'required|exists:products,id',
            'metode_pembayaran' => 'required',
            // 'quantity' => 'required',
            // 'price' => 'required',


            'items' => 'required|array|min:1',
            'items.*.id_product' => 'required|integer|exists:products,id',
            'items.*.price' => 'required|numeric|min:0',
            // 'items.*.metode_pembayaran' => 'required',
            'items.*.quantity' => 'required|integer|min:1',

            // '*.total' => 'nullable|numeric|min:0',
        ];
    }
}
