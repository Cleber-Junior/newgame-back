<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MercadoPagoWebHookController extends Controller
{
    public function handle(Request $request){
        dd("Ola");

        //TODO: Verificar o status do pagamento, alterar o status no "Payment"
        // settar o current_value do project
        // 0 -> em Andamento | 1 -> Aprovado | 2 -> Negado
    }
}
