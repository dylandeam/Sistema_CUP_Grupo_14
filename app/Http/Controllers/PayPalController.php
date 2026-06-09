<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use Illuminate\Support\Facades\Mail;

class PayPalController extends Controller
{
    protected $provider;
    
    public function __construct()
    {
        $this->provider = new PayPalClient;
        $this->provider->setApiCredentials(config('paypal'));
        $this->provider->getAccessToken(); // mantenemos el objeto, no lo reemplazamos
    }

    public function pago(Request $request)
    {
        $total = config('inscripcion.costo'); // costo de inscripción desde config
        $data = [
            "intent" => "CAPTURE",
            "purchase_units" => [
                [
                    "amount" => [
                        "currency_code" => config('paypal.currency'),
                        "value" => number_format($total, 2, '.', '')
                    ],
                    "description" => "Pago de inscripción al proceso de admisión"
                ]
            ],
            "application_context" => [
                "cancel_url" => route('web.paypal.cancelar'),
                "return_url" => route('web.paypal.gracias')
            ]
        ];

        try {
            $response = $this->provider->createOrder($data);

            if (isset($response['id']) && $response['id'] != null) {
                // Buscar el link de aprobación
                $approveLink = null;
                foreach ($response['links'] as $link) {
                    if ($link['rel'] === 'approve') {
                        $approveLink = $link['href'];
                        break;
                    }
                }

                if ($approveLink) {
                    // 👇 Usamos directamente el email que viene del formulario
                    if ($request->filled('email')) {
                        $messageBody = "Hola,\n\n" .
                            "Se ha generado una solicitud de pago para la inscripción.\n" .
                            "Monto: $" . number_format($total, 2) . "\n" .
                            "Por favor utilice el siguiente enlace para completar el pago con PayPal:\n" .
                            "$approveLink\n\n" .
                            "Gracias.";

                        Mail::raw($messageBody, function ($message) use ($request) {
                            $message->to($request->email)
                                    ->subject('Solicitud de pago de inscripción');
                        });
                    }

                    // Redirigir al usuario a PayPal
                    return redirect()->away($approveLink);
                }

                return redirect()->route('web.paypal.cancelar')
                                 ->with('error', 'No se pudo encontrar el enlace de aprobación.');
            } else {
                return redirect()->route('web.paypal.cancelar')
                                 ->with('error', 'Error al crear la orden de PayPal.');
            }
        } catch (\Exception $e) {
            return redirect()->route('web.paypal.cancelar')
                             ->with('error', 'Excepción: ' . $e->getMessage());
        }
    }

    public function gracias(Request $request)
    {
        try {
            $result = $this->provider->capturePaymentOrder($request->token);

            if ($result['status'] === 'COMPLETED') {
                return view('paypal.pagado')->with('message', 'Pago confirmado');
            }

            return view('paypal.cancelado')->with('message', 'Error en el pago');
        } catch (\Exception $e) {
            return view('paypal.cancelar')->with('message', 'Excepción: ' . $e->getMessage());
        }
    }

    public function cancelar()
    {
        return view('paypal.cancelado');
    }
}
