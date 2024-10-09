<?php
namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Client;
use App\Models\Consultations;
use Illuminate\Http\Request;
use App\Mail\AppointmentMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AppointmentController extends Controller
{
    public function createAppointment(array $data)
    {
        // Find or create client
        $client = Client::firstOrCreate(
            ['email' => $data['email']],
            ['first_name' => $data['first_name'], 'last_name' => $data['last_name']]
        );

        // Find consultation
        $consultation = Consultations::find($data['consultation_id']);
        if (!$consultation) {
            throw new ModelNotFoundException('Consultation not found');
        }

        // Log start and end times
        Log::info('Raw start time: ' . $data['start_time']);
        Log::info('Raw end time: ' . $data['end_time']);

        // Correctly parse the 12-hour format with AM/PM in EAT timezone
        try {
            $start = Carbon::createFromFormat('Y-m-d H:i:s', $data['start_time'], 'Africa/Nairobi');
            $end = Carbon::createFromFormat('Y-m-d H:i:s', $data['end_time'], 'Africa/Nairobi');
        } catch (\Exception $e) {
            Log::error('Error parsing dates: ' . $e->getMessage());
            throw $e;
        }

        // Log the parsed start and end times
        Log::info('Parsed start time: ' . $start->toDateTimeString());
        Log::info('Parsed end time: ' . $end->toDateTimeString());

        // Ensure both start and end times are in the same timezone
        $start->setTimezone('Africa/Nairobi');
        $end->setTimezone('Africa/Nairobi');

        // Check for overlapping appointments regardless of consultation type
        $overlappingAppointments = Appointment::where(function ($query) use ($start, $end) {
                $query->where('start_time', '<', $end)
                      ->where('end_time', '>', $start);
            })
            ->exists();

        if ($overlappingAppointments) {
            throw new \Exception('The selected time slot is already taken. Please select another time frame.');
        }

        $duration = $end->diffInMinutes($start);

        // Log duration
        Log::info('Duration in minutes: ' . $duration);

        $total_fee = $consultation->base_fee;

        if ($duration > 30) {
            $extra_minutes = $duration - 30;
            $total_fee += $extra_minutes * $consultation->extra_fee_per_minute;

            // Log extra minutes and calculated total fee
            Log::info('Extra minutes: ' . $extra_minutes);
            Log::info('Extra fee per minute: ' . $consultation->extra_fee_per_minute);
            Log::info('Total fee with extras: ' . $total_fee);
        }

        // Log final total fee
        Log::info('Final total fee: ' . $total_fee);

        // Create appointment
        $appointment = Appointment::create([
            'client_id' => $client->id,
            'consultation_id' => $consultation->id,
            'start_time' => $start->toDateTimeString(),
            'end_time' => $end->toDateTimeString(),
            'total_fee' => $total_fee,
        ]);

        // Send email notifications
        Mail::to($client->email)->send(new AppointmentMail([
            'first_name' => $client->first_name,
            'last_name' => $client->last_name,
            'consultation' => $consultation->type,
            'start_time' => $start->toDateTimeString(),
            'end_time' => $end->toDateTimeString(),
            'payment_status' => 'pending',
        ]));

        // Store necessary information in the session for Stripe redirection
        session()->put('appointment_id', $appointment->id);
        session()->put('consultation_type', $consultation->type);
        session()->put('total_fee', $total_fee);

        return $appointment;
    }
}
