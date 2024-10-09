<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ConsultationsResource\Pages;
use App\Filament\Resources\ConsultationsResource\RelationManagers;
use App\Models\Consultations;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Policies\ConsultationPolicy;

class ConsultationsResource extends Resource
{
    protected static ?string $model = Consultations::class;

    protected static ?string $navigationIcon = 'heroicon-o-queue-list';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Consultations')
                        ->description('Put the consultations details in')
                        ->schema([
                            Forms\Components\Select::make('type')
                            ->label('Type')
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
        
                            Forms\Components\TextInput::make('base_fee')
                                ->label('Base Fee')
                                ->numeric()
                                ->required(),
            
                            Forms\Components\TextInput::make('extra_fee_per_minute')
                                ->label('Extra Fee Per Minute')
                                ->numeric()
                                ->required(),
                        ])   
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('base_fee')
                    ->searchable(),
                Tables\Columns\TextColumn::make('extra_fee_per_minute')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => Pages\ListConsultations::route('/'),
            'create' => Pages\CreateConsultations::route('/create'),
            'view' => Pages\ViewConsultations::route('/{record}'),
            'edit' => Pages\EditConsultations::route('/{record}/edit'),
        ];
    }
}
