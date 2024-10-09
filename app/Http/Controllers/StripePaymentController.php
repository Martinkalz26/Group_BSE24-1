<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\StripeClient;
use App\Models\Appointment;

class StripePaymentController extends Controller
{
    public function checkout(Request $request)
    {
        $session_id = $request->query('sessionId');
        return view('stripe', ['session_id' => $session_id]);
    }

    public function success(Request $request)
    {
        $session_id = $request->query('sessionId');
        $appointment_id = session('appointment_id');
        $appointment = Appointment::find($appointment_id);

        if ($appointment) {
            $appointment->payment_status = 'paid';
            $appointment->save();
            Log::info('Payment status updated to paid for appointment ID: ' . $appointment_id);
        } else {
            Log::error('Appointment not found for ID: ' . $appointment_id);
        }

        return view('stripe_messages.success', ['session_id' => $session_id]);
    }

    public function cancel()
    {
        return view('stripe_messages.cancel');
    }

    public function createStripeSession($consultation_type, $total_fee)
    {
        $stripe = new StripeClient(config('stripe.stripe_sk'));

        try {
            $response = $stripe->checkout->sessions->create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => 'Consultation',
                            'description' => $consultation_type,
                        ],
                        'unit_amount' => $total_fee * 100,
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => route('success') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('cancel'),
            ]);

            return $response->id;
        } catch (\Exception $e) {
            Log::error('Stripe error: ' . $e->getMessage());
            return null;
        }
    }
}
