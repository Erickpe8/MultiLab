<?php

namespace App\Filament\Resources\ClassroomLoanResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class WorkstationsRelationManager extends RelationManager
{
    protected static string $relationship = 'workstations';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('classroom_workstation_id')
                    ->relationship('workstation', 'label')
                    ->label('Estación')
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\Select::make('status')
                    ->label('Estado')
                    ->options([
                        'reservado' => 'Reservado',
                        'en_uso' => 'En uso',
                        'liberado' => 'Liberado',
                        'inactivo' => 'Inactivo',
                    ])
                    ->default('reservado')
                    ->required()
                    ->native(false),
                Forms\Components\TextInput::make('assigned_user')
                    ->label('Usuario asignado')
                    ->maxLength(80),
                Forms\Components\KeyValue::make('metrics')
                    ->label('Métricas')
                    ->keyLabel('Clave')
                    ->valueLabel('Valor'),
                Forms\Components\Textarea::make('notes')
                    ->label('Notas')
                    ->rows(3)
                    ->columnSpanFull(),
            ])
            ->columns(2);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('label')
                    ->label('Estación')
                    ->state(fn ($record) => $record->pivot->classroom_workstation_id ? $record->label : '-')
                    ->description(fn ($record) => $record->code),
                Tables\Columns\BadgeColumn::make('pivot.status')
                    ->label('Estado')
                    ->colors([
                        'warning' => 'reservado',
                        'info' => 'en_uso',
                        'gray' => 'liberado',
                        'danger' => 'inactivo',
                    ])
                    ->formatStateUsing(fn ($state) => ucfirst(str_replace('_', ' ', $state))),
                Tables\Columns\TextColumn::make('pivot.assigned_user')
                    ->label('Usuario asignado')
                    ->placeholder('—'),
                Tables\Columns\TextColumn::make('pivot.notes')
                    ->label('Notas')
                    ->limit(40)
                    ->wrap(),
                Tables\Columns\TextColumn::make('pivot.created_at')
                    ->label('Asignado')
                    ->since()
                    ->sortable(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
