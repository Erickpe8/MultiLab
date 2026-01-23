<?php

namespace App\Filament\Resources\ClassroomLoanResource\RelationManagers;

use App\Models\ClassroomWorkstation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class WorkstationsRelationManager extends RelationManager
{
    protected static string $relationship = 'workstations';

    protected static ?string $title = 'Puestos de trabajo';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('classroom_workstation_id')
                    ->label('Estación')
                    ->options(function () {
                        ClassroomWorkstation::syncFromComputers();

                        return ClassroomWorkstation::query()
                            ->orderBy('label')
                            ->pluck('label', 'id');
                    })
                    ->searchable()
                    ->preload()
                    ->required()
                    ->disabledOn('edit'),
                Forms\Components\TextInput::make('assigned_user')
                    ->label('Usuario asignado')
                    ->maxLength(80),
                Forms\Components\KeyValue::make('metrics')
                    ->label('Métricas')
                    ->keyLabel('Descripción')
                    ->valueLabel('Valor')
                    ->addButtonLabel('Agregar métrica')
                    ->deleteButtonLabel('Eliminar fila')
                    ->afterStateHydrated(function (Forms\Components\KeyValue $component, $state) {
                        if (is_string($state)) {
                            $decoded = json_decode($state, true);
                            $component->state($decoded ?? []);
                        }
                    })
                    ->dehydrateStateUsing(function ($state) {
                        if (is_array($state)) {
                            $state = collect($state)
                                ->reject(fn ($value, $key) => blank($key) && blank($value))
                                ->map(fn ($value) => $value === '' ? null : $value)
                                ->toArray();
                        }

                        return empty($state) ? null : $state;
                    })
                    ->nullable(),
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
                Tables\Columns\BadgeColumn::make('status_label')
                    ->label('Disponibilidad')
                    ->colors([
                        'success' => fn ($state) => $state === 'Disponible',
                        'danger' => fn ($state) => $state !== 'Disponible',
                    ])
                    ->icon(fn ($state) => $state === 'Disponible' ? 'heroicon-m-check-circle' : 'heroicon-m-x-circle'),
                Tables\Columns\TextColumn::make('pivot.assigned_user')
                    ->label('Usuario asignado')
                    ->placeholder('—'),
                Tables\Columns\TextColumn::make('pivot.notes')
                    ->label('Notas')
                    ->limit(40)
                    ->wrap(),
            ])
            ->headerActions([])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->modalHeading('Editar puesto de trabajo'),
            ]);
    }
}
