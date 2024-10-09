<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AppointmentResource\Pages;
use App\Models\Appointment;
use App\Models\Consultations;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Facades\Auth;


class AppointmentResource extends Resource
{
    protected static ?string $model = Appointment::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Appointments')
                        ->description('Please make appointment here')
                        ->schema([
                            TextInput::make('first_name')
                            ->label('Client First Name')
                            ->required(),
                        TextInput::make('last_name')
                            ->label('Client Last Name')
                            ->required(),
                        TextInput::make('email')
                            ->label('Client Email')
                            ->required(),

                Select::make('consultation_id')
                    ->label('Consultation Type')
                    ->options(Consultations::all()->pluck('type', 'id'))
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set) {
                        $consultation = Consultations::find($state);
                        if ($consultation) {
                            $set('base_fee', $consultation->base_fee);
                            $set('extra_fee_per_minute', $consultation->extra_fee_per_minute);
                        }
                    })
                    ->required(),

                TextInput::make('base_fee')
                    ->label('Base Fee')
                    ->numeric()
                    ->disabled(),

                TextInput::make('extra_fee_per_minute')
                    ->label('Extra Fee Per Minute')
                    ->numeric()
                    ->disabled(),

                DateTimePicker::make('start_time')
                    ->label('Start Time')
                    ->required(),

                DateTimePicker::make('end_time')
                    ->label('End Time')
                    ->required(),

                TextInput::make('total_fee')
                    ->label('Total Fee')
                    ->numeric()
                    ->disabled(),
                ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('client.first_name')->label('Client First Name')->searchable(),
                Tables\Columns\TextColumn::make('client.last_name')->label('Client Last Name')->searchable(),
                Tables\Columns\TextColumn::make('client.email')->label('Client Email')->searchable(),
                Tables\Columns\TextColumn::make('consultation.type')->label('Consultation Type')->searchable(),
                Tables\Columns\TextColumn::make('start_time')->searchable(),
                Tables\Columns\TextColumn::make('end_time')->searchable(),
                Tables\Columns\TextColumn::make('total_fee')->searchable(),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAppointments::route('/'),
            'create' => Pages\CreateAppointment::route('/create'),
            'view' => Pages\ViewAppointment::route('/{record}'),
            'edit' => Pages\EditAppointment::route('/{record}/edit'),
        ];
    }
}
