<?php

namespace App\Http\Requests;

use App\Enums\TicketItemStatus;
use App\Enums\TicketStatus;
use Illuminate\Foundation\Http\FormRequest;

class TicketChangeStatusRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        //",En preparación,Entregado,Recibido"
        return [
            "id" => "required|integer|exists:tickets_tbl,id",
            "subStatus" => "required|string|in:" . TicketItemStatus::Standby . "," . TicketItemStatus::InPreparation . "," . TicketItemStatus::Prepared . "," . TicketItemStatus::Received
        ];
    }
}
