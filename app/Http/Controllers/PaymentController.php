<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\User;
use App\Models\Project;
use App\Models\Rewards;
use GuzzleHttp\Psr7\Request as HTTPRequest;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

class PaymentController extends Controller {

    protected Payment $payment;
    protected User $user;
    protected Project $project;

    public function __construct(Payment $payment){
        $this->payment = $payment;
    }

    public function createReference(Request $request){
        $project = Project::find($request->project_id);

        $user = User::find($request->user);

        $reward = Rewards::find($request->reward_id);

        $client = new Client();

        $requestBody = [
            "items" =>  [
                [
                  "id" => $reward["id"],
                  "title" => $reward["name"],
                  "description" => $reward["description"],
                  "quantity" => 1,
                  "unit_price" => (float) $reward["value"],
                ]
            ],
                "payer" => [
                  "name" => $user["fullname"],
                  "email" => $user["email"],
                    "identification" => [
                      "type" => "CPF",
                      "number" => $user["cpf"]
                  ],
                    "address" => [
                      "zip_code" => $user["zip_code"],
                      "street_name" => $user["street"],
                      "street_number" => $user["number"],
                    ],
                ],
                "shipments" => [
                   "local_pickup" => false,
                   "default_shipping_method" => null,
                    "receiver_address" => [
                      "zip_code" => $user["zip_code"],
                      "street_name" => $user["street"],
                      "city_name" => $user["city"],
                      "state_name" => $user["state"],
                      "street_number" => $user["number"],
                      "country_name" => "Brazil",
                    ],
                    ],
                "payment_methods" => [
                    "excluded_payment_types" => [
                        ["id" => "ticket"],
                        ["id" => "bank_transfer"]
                    ],
                    "installments" => 6,
                ],
                "back_urls" => [
                  "success" => "http://localhost:5173/user/supported",
                  "pending" => "http://localhost:5173/user/supported",
                  "failure" => "http://localhost:5173/user/supported"
                ],
        ];

        $request = new HTTPRequest('POST', 'https://api.mercadopago.com/checkout/preferences', [
            'Authorization' => 'Bearer ' . 'TEST-3396332215883252-121913-8796d6699c0d962cb04bd06004e69bb3-1919598852',
            'Content-Type' => 'application/json'
        ], json_encode($requestBody));

        $res = $client->sendAsync($request)->wait();
        $responseBody = json_decode($res->getBody(), true);
        $linkToPay = $responseBody['sandbox_init_point'];

        $paymentData = [
            'id_preference' => $responseBody['id'],
            'value' => $responseBody['items'][0]['unit_price'],
            'date' => now(),
            'id_project' => $project['id'],
            'id_user' => $user['id'],
            'id_reward' => $reward['id'],
            'status' => 2,
        ];

        $this->storePayment($paymentData);

        return response()->json(['msg' => 'Referência criada com sucesso', 'reference' => $linkToPay], 201);
    }

    public function storePayment(array $data){
        $this->payment->create($data);
    }

    public function getPayments(User $user){
        $payments = Payment::where('id_user', $user['id'])->get(); // Pego os pagamentos de acordo com o ID do Usuário

        $projectsIds = $payments->pluck('id_project'); // Pego os IDs dos projetos

        $projects = Project::whereIn('id', $projectsIds)->get(); // Pega os projetos que tenham os Ids correspondestes

        $responseBody = [];
        foreach($payments as $payment){
            $project = $projects->firstWhere('id', $payment->id_project);

            if($project){
                $responseBody[] = [
                    'project_name' => $project->name,
                    'current_value' => $project->current_value,
                    'meta_value' => $project->meta_value,
                    'value' => $payment->value,
                    'end_date' => $project->end_date,
                    'payment_date' => $payment->date,
                    'status' => $payment->status,
                ];
            };
        };

        return response()->json(['supported' => $responseBody], 200);
    }
}
