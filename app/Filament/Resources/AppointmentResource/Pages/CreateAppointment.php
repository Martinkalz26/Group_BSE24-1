<?php
namespace App\Filament\Resources\AppointmentResource\Pages;

use App\Filament\Resources\AppointmentResource;
use Filament\Resources\Pages\CreateRecord;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\StripePaymentController;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;
use App\Models\Appointment;

class CreateAppointment extends CreateRecord
{
    protected static string $resource = AppointmentResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $appointmentController = new AppointmentController();
        try {
            return $appointmentController->createAppointment($data);
        } catch (\Exception $e) {
            // Handle the exception by showing a notification to the user
            Notification::make()
                ->title('Error')
                ->body($e->getMessage())
                ->danger()
                ->send();
            
            // Create a dummy appointment to satisfy the return type
            $appointment = new Appointment();
            $appointment->id = 0;
            $appointment->client_id = 0;
            $appointment->consultation_id = 0;
            $appointment->start_time = now();
            $appointment->end_time = now();
            $appointment->total_fee = 0;
            $appointment->error_flag = true; // Custom flag to indicate an error

            return $appointment;
        }
    }

    protected function afterCreate()
    {
        $appointment = $this->record;

        // Check if the creation had an error
        if ($appointment->error_flag ?? false) {
            // Redirect back with input if there was an error
            return redirect()->back()->withInput();
        }

        $consultation_type = session('consultation_type');
        $total_fee = session('total_fee');

        $stripeController = new StripePaymentController();
        $sessionId = $stripeController->createStripeSession($consultation_type, $total_fee);

        if ($sessionId) {
            session()->put('stripe_session_id', $sessionId);
            return redirect()->route('stripe.checkout', ['sessionId' => $sessionId]);
        } else {
            return redirect()->route('cancel');
        }
    }

    protected function getRedirectUrl(): string
    {
        return route('stripe.checkout', ['sessionId' => session('stripe_session_id')]);
    }
}
