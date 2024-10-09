<?php

namespace App\Filament\Resources\AppointmentResource\Pages;

use App\Filament\Resources\AppointmentResource;
use Filament\Actions;
use App\Models\Appointment;
use App\Http\Controllers\AppointmentController;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Http\Request;

class EditAppointment extends EditRecord
{
    protected static string $resource = AppointmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    protected function handleRecordUpdate(\Illuminate\Database\Eloquent\Model $record, array $data): \Illuminate\Database\Eloquent\Model
    {
        // Create a new request with the provided data
        $request = Request::create('', 'POST', $data);

        // Manually merge ID into request data
        $request->merge(['id' => $record->id]);

        // Call the update method from the controller
        $controller = app()->make(AppointmentController::class);
        return $controller->update($request, $record->id);
    }

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\Section::make('Appointments')
                ->description('Please update the appointment here')
                ->schema([
                    Forms\Components\DateTimePicker::make('start_time')
                        ->label('Start Time')
                        ->required(),
                    Forms\Components\DateTimePicker::make('end_time')
                        ->label('End Time')
                        ->required(),
                    Forms\Components\TextInput::make('total_fee')
                        ->label('Total Fee')
                        ->numeric()
                        ->required(),
                    Forms\Components\TextInput::make('first_name')
                        ->label('First Name')
                        ->required(),
                    Forms\Components\TextInput::make('last_name')
                        ->label('Last Name')
                        ->required(),
                    Forms\Components\TextInput::make('email')
                        ->label('Email')
                        ->required()
                        ->email(),
                    Forms\Components\TextInput::make('base_fee')
                        ->label('Base Fee')
                        ->numeric()
                        ->required(),
                    Forms\Components\TextInput::make('extra_fee_per_minute')
                        ->label('Extra Fee Per Minute')
                        ->numeric()
                        ->required(),
                ]),
        ];
    }

    public function endBooking()
    {
        $request = request();
        return app(AppointmentController::class)->endBooking($request, $this->record->id);
    }

    protected function getActions(): array
    {
        return array_merge(parent::getActions(), [
            Actions\ButtonAction::make('End Booking')
                ->action('endBooking')
                ->requiresConfirmation(),
        ]);
    }
}
