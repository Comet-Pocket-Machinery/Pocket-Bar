<?php

namespace App\Http\Controllers;

use App\Http\Requests\Nominas\ToPay;
use App\Models\CashRegisterCloseData;
use App\Models\Nomina;
use App\Models\Workshift;
use DB;

class NominasController extends Controller
{
    public function nominasToPay(ToPay $request)
    {
        $usersToPay = $request->input('nominas');
        $workshift = Workshift::where('active', 1)->first();
        $total = 0;
        $response = [
            "total" => $total,
            "nominas" => [],
            "workshift" => $workshift,
            "bruto" => CashRegisterCloseData::where('workshift_id', $workshift->id)->sum('amount'),
            "neto" => 0
        ];
        DB::beginTransaction();
        try {
            foreach ($usersToPay as $userToPay) {
                $nomina = new Nomina();
                $nomina->workshift_id = $workshift->id;
                $nomina->user_id = $userToPay['id'];
                $nomina->base = $userToPay['nomina'];
                $nomina->tips = $userToPay['propina'];
                $nomina->name = $userToPay['nombre'];
                $nomina->paid = $usersToPay['base'] + ($usersToPay['tips'] * .75);
                $total = $total + $nomina->paid;
                $response["usersToPay"][] = [
                    "user_id" => $userToPay['user_id'],
                    "name" => $userToPay['name'],
                    "paid" => $nomina->paid
                ];
                throw_if(!$nomina->save(), "Error al guardar el registro");
            }
            $response["neto"] = $response["bruto"] - $total;
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(["error" => $th->getMessage()], 500);
        }

        return response()->json($response);
    }
}
