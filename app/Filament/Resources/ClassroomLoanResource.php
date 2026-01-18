<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClassroomLoanResource\Pages;
use App\Filament\Resources\ClassroomLoanResource\RelationManagers\ObservationsRelationManager;
use App\Filament\Resources\ClassroomLoanResource\RelationManagers\WorkstationsRelationManager;
use App\Models\ClassroomLoan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ClassroomLoanResource extends Resource
{
    protected static ?string $model = ClassroomLoan::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';
    protected static ?string $navigationGroup = 'Aula B201';
    protected static ?string $modelLabel = 'Reserva de aula';
    protected static ?string $pluralModelLabel = 'Reservas Aula B201';

    public static function getNavigationBadge(): ?string
    {
        return (string) static::getModel()::query()
            ->where('status', 'pendiente')
            ->count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Información general')
                    ->schema([
                        Forms\Components\TextInput::make('classroom_code')
                            ->label('Aula')
                            ->default('B201')
                            ->maxLength(20)
                            ->required(),
                        Forms\Components\Select::make('requested_by')
                            ->relationship('requester', 'first_name')
                            ->label('Docente solicitante')
                            ->getOptionLabelFromRecordUsing(fn ($record) => $record->name)
                            ->searchable(['first_name', 'middle_name', 'first_surname', 'second_surname', 'email'])
                            ->preload()
                            ->required(),
                        Forms\Components\Select::make('approved_by')
                            ->relationship('approver', 'first_name')
                            ->label('Aprobado por')
                            ->getOptionLabelFromRecordUsing(fn ($record) => $record->name)
                            ->searchable(['first_name', 'middle_name', 'first_surname', 'second_surname', 'email'])
                            ->preload(),
                        Forms\Components\TextInput::make('subject')
                            ->label('Asignatura/Sesión')
                            ->maxLength(120)
                            ->required(),
                        Forms\Components\TextInput::make('purpose')
                            ->label('Propósito')
                            ->maxLength(180),
                        Forms\Components\Select::make('status')
                            ->options([
                                'pendiente' => 'Pendiente',
                                'aprobado' => 'Aprobado',
                                'rechazado' => 'Rechazado',
                                'en_uso' => 'En uso',
                                'finalizado' => 'Finalizado',
                                'cancelado' => 'Cancelado',
                            ])
                            ->required()
                            ->native(false),
                    ])
                    ->columns(2),
                Forms\Components\Section::make('Agenda')
                    ->schema([
                        Forms\Components\DateTimePicker::make('scheduled_start_at')
                            ->label('Inicio programado')
                            ->seconds(false)
                            ->required(),
                        Forms\Components\DateTimePicker::make('scheduled_end_at')
                            ->label('Fin programado')
                            ->seconds(false)
                            ->required()
                            ->after('scheduled_start_at'),
                        Forms\Components\DateTimePicker::make('actual_start_at')
                            ->label('Inicio real')
                            ->seconds(false),
                        Forms\Components\DateTimePicker::make('actual_end_at')
                            ->label('Fin real')
                            ->seconds(false),
                    ])
                    ->columns(2),
                Forms\Components\Section::make('Control de PCs')
                    ->schema([
                        Forms\Components\TextInput::make('pc_required')
                            ->label('PCs requeridos')
                            ->numeric()
                            ->default(0)
                            ->minValue(0)
                            ->required(),
                        Forms\Components\TextInput::make('pc_in_use')
                            ->label('PCs en uso')
                            ->numeric()
                            ->default(0)
                            ->minValue(0)
                            ->required(),
                        Forms\Components\TextInput::make('pc_unavailable')
                            ->label('PCs no disponibles')
                            ->numeric()
                            ->default(0)
                            ->minValue(0)
                            ->required(),
                        Forms\Components\KeyValue::make('workstations_snapshot')
                            ->label('Estado rápido de estaciones')
                            ->keyLabel('Estación')
                            ->valueLabel('Estado')
                            ->addButtonLabel('Agregar estación')
                            ->columnSpanFull(),
                    ])
                    ->columns(3),
                Forms\Components\Section::make('Notas')
                    ->schema([
                        Forms\Components\Textarea::make('access_instructions')
                            ->label('Instrucciones de acceso')
                            ->rows(3),
                        Forms\Components\Textarea::make('notes')
                            ->label('Notas internas')
                            ->rows(5),
                    ])
                    ->columns(1)
                    ->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('subject')
                    ->label('Sesión')
                    ->description(fn (ClassroomLoan $record) => $record->purpose)
                    ->searchable()
                    ->wrap(),
                Tables\Columns\TextColumn::make('requester.name')
                    ->label('Docente')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('scheduled_start_at')
                    ->label('Inicio')
                    ->dateTime('d/m H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('scheduled_end_at')
                    ->label('Fin')
                    ->dateTime('d/m H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->colors([
                        'warning' => 'pendiente',
                        'success' => ['aprobado', 'finalizado'],
                        'danger' => ['rechazado', 'cancelado'],
                        'info' => 'en_uso',
                    ])
                    ->formatStateUsing(fn (string $state) => match ($state) {
                        'pendiente' => 'Pendiente',
                        'aprobado' => 'Aprobado',
                        'rechazado' => 'Rechazado',
                        'en_uso' => 'En uso',
                        'finalizado' => 'Finalizado',
                        'cancelado' => 'Cancelado',
                        default => ucfirst($state),
                    }),
                Tables\Columns\TextColumn::make('pc_required')
                    ->label('PCs')
                    ->numeric()
                    ->formatStateUsing(fn (ClassroomLoan $record) => "{$record->pc_in_use}/{$record->pc_required}")
                    ->tooltip('PCs en uso / requeridos'),
                Tables\Columns\TextColumn::make('incidents_count')
                    ->label('Incidencias')
                    ->badge()
                    ->color(fn ($state) => $state > 0 ? 'danger' : 'gray')
                    ->sortable(),
                Tables\Columns\TextColumn::make('classroom_code')
                    ->label('Aula')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Actualizado')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pendiente' => 'Pendiente',
                        'aprobado' => 'Aprobado',
                        'rechazado' => 'Rechazado',
                        'en_uso' => 'En uso',
                        'finalizado' => 'Finalizado',
                        'cancelado' => 'Cancelado',
                    ]),
                Tables\Filters\Filter::make('fecha')
                    ->form([
                        Forms\Components\DatePicker::make('from'),
                        Forms\Components\DatePicker::make('until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['from'] ?? null, fn ($q, $date) => $q->whereDate('scheduled_start_at', '>=', $date))
                            ->when($data['until'] ?? null, fn ($q, $date) => $q->whereDate('scheduled_end_at', '<=', $date));
                    }),
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
            ObservationsRelationManager::class,
            WorkstationsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListClassroomLoans::route('/'),
            'create' => Pages\CreateClassroomLoan::route('/create'),
            'edit' => Pages\EditClassroomLoan::route('/{record}/edit'),
        ];
    }
}
