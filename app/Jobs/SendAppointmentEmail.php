<?php
namespace App\Jobs;

use App\Mail\AppointmentMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendAppointmentEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $client;
    protected $consultation;
    protected $data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($client, $consultation, $data)
    {
        $this->client = $client;
        $this->consultation = $consultation;
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->client->email)->send(new AppointmentMail([
            'first_name' => $this->client->first_name,
            'last_name' => $this->client->last_name,
            'consultation' => $this->consultation->type,
            'start_time' => $this->data['start_time'],
            'end_time' => $this->data['end_time'],
        ]));
    }
}
