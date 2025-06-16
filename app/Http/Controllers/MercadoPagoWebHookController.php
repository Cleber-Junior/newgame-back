<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Payment;
use App\Models\Project;

class MercadoPagoWebHookController extends Controller
{
    public function handle(Request $request)
    {
        $data = $request->all();

        if (isset($data['type']) && $data['type'] === 'payment' && isset($data['data']['id'])) {
            $paymentId = $data['data']['id'];
            $accessToken = env('MERCADOPAGO_ACCESS_TOKEN');

            $response = Http::withToken($accessToken)->get("https://api.mercadopago.com/v1/payments/{$paymentId}");

            Log::info('Webhook MercadoPago: Resposta da API de pagamentos.', [
                'payment_id_mp' => $paymentId,
                'response_body' => $response->json(),
                'status_code' => $response->status()
            ]);

            if ($response->successful()) {
                $paymentInfo = $response->json();
                $externalReference = $paymentInfo['external_reference'] ?? null;

                if ($externalReference) {
                    $paymentId = str_replace('payment_', '', $externalReference);
                    $localPayment = Payment::find($paymentId);

                    if ($localPayment) {
                        switch ($paymentInfo['status']) {
                            case 'approved':
                                $localPayment->status = 1;

                                $project = Project::find($localPayment->id_project);

                                if ($project) {
                                    log::info('Project Value: Entrou no if do projeto', [
                                        'project_id' => $project->id,
                                        'current_value' => $project->current_value,
                                        'payment_value' => $localPayment->value
                                    ]);
                                    $project->current_value += $localPayment->value;
                                    $project->save();
                                }
                                break;
                            case 'rejected':
                                $localPayment->status = 0;
                                break;
                            case 'cancelled':
                                $localPayment->status = 2;
                                break;
                        }

                        $localPayment->save();
                    } else {
                        Log::error('Webhook MercadoPago: Pagamento não encontrado no banco de dados local.', [
                            'payment_id' => $paymentId,
                            'status_code' => $response->status(),
                            'response' => $response->body()
                        ]);
                    }
                }
            }
            return response()->json(['status' => 'received'], 200);
        }
    }
}
