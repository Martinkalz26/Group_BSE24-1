<?php

namespace App\Filament\Resources\AppointmentResource\Pages;

use App\Filament\Resources\AppointmentResource;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Filament\Actions;

class ListAppointments extends ListRecords
{
    protected static string $resource = AppointmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getTableQuery(): Builder
    {
        $user = Auth::user();

        // Check if the user is an admin
        if ($user->isAdmin()) {
            // Admins can see all appointments
            return parent::getTableQuery();
        } else {
            // Regular users can only see their own appointments
            return parent::getTableQuery()
                ->join('clients', 'clients.id', '=', 'appointments.client_id')
                ->where('clients.email', $user->email)
                ->select('appointments.*');
        }
    }
}
