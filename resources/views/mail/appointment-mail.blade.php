<x-mail::message>
# Thank you for booking an appointment with us.

<br>
These are the details of the appointment.
<br>
<strong>First name:</strong>{{$data['first_name']}}
<br>
<strong>Last name:</strong> {{$data['last_name']}}
<br>
<strong>Consultation type:</strong>{{$data['consultation']}}
<br>
<strong>Start time:</strong> {{$data['start_time']}}
<br>
<strong>End time:</strong> {{$data['end_time']}}
<br>
<strong>Note:</strong> The meeting is supposed to last for 30 minutes and any extra minute will be also charged.

Thanks, Mariuris
</x-mail::message>
