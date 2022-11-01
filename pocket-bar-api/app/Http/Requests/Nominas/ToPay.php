<?php

namespace App\Http\Requests\Nominas;

use App\Enums\Rol;
use Illuminate\Foundation\Http\FormRequest;

class ToPay extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->rol_id == Rol::Administrativo || auth()->user()->rol_id == Rol::Gerencia;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "payroll" => "required|array|min:1",
            "payroll.*.user_id" => "required|integer|exists:users,id",
            "payroll.*.payment" => "required|numeric",
            "payroll.*.tip" => "required|numeric",
            "payroll.*.name" => "required|string|max:255",
        ];
    }
}
