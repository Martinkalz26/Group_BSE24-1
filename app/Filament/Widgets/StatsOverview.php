<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\User;
use App\Models\Appointment;

class StatsOverview extends BaseWidget
{
    protected static ?string $pollingInterval = '15s';
    
    protected static bool $islazy = true;

    protected function getStats(): array
    {
        return [
            Stat::make('Total Customers', User::count())
            ->description("Increase in customers")
            ->descriptionIcon('heroicon-m-arrow-trending-up')
            ->color('success')
            ->chart([7, 4, 5,8, 3]),
            Stat::make('Pending Appointments', Appointment::where('payment_status', 'pending')->count())
            ->description("Increase in pending appointments")
            ->descriptionIcon('heroicon-m-arrow-trending-up')
            ->color('danger')
            ->chart([7, 4, 5,8, 3]),
            Stat::make('Paid Appointments', Appointment::where('payment_status', 'paid')->count())
            ->description("Increase in paid appointments")
            ->descriptionIcon('heroicon-m-arrow-trending-up')
            ->color('success')
            ->chart([7, 4, 5,8, 3]),
            Stat::make('Total Amount Paid in USD($)', Appointment::sum('total_fee'))
                ->description("Total amount received from appointments")
                ->descriptionIcon('heroicon-o-currency-dollar')
                ->color('primary')
                ->chart([15, 25, 35, 45, 55]), // example chart data
        ];
    }
}
