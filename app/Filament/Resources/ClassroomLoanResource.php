<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClassroomLoanResource\Pages;
use App\Filament\Resources\ClassroomLoanResource\RelationManagers\ObservationsRelationManager;
use App\Filament\Resources\ClassroomLoanResource\RelationManagers\WorkstationsRelationManager;
use App\Helpers\RoleHelper;
use App\Models\ClassroomLoan;
use App\Models\Computer;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\Section as InfoSection;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ClassroomLoanResource extends Resource
{
    protected static ?string $model = ClassroomLoan::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';

    protected static ?string $navigationGroup = 'Aula B202';

    protected static ?string $modelLabel = 'Reserva de aula';

    protected static ?string $pluralModelLabel = 'Reservas Aula B202';

    public static function canViewAny(): bool
    {
        return RoleHelper::isLabStaff();
    }

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
                            ->default('B202')
                            ->maxLength(20)
                            ->readonly()
                            ->disabled()
                            ->required(),
                        Forms\Components\Select::make('requested_by')
                            ->relationship('requester', 'first_name', fn(Builder $query) => $query->role(['docente', 'superadmin', 'aux_admin']))
                            ->label('Docente solicitante')
                            ->default(Auth::id())
                            ->disabled(fn() => Auth::user()->hasRole('docente'))
                            ->dehydrated()
                            ->getOptionLabelFromRecordUsing(fn($record) => $record->name)
                            ->searchable(['first_name', 'middle_name', 'first_surname', 'second_surname', 'email'])
                            ->preload()
                            ->required()
                            ->rules(['exists:users,id'])
                            ->native(false),
                        Forms\Components\Select::make('approved_by')
                            ->relationship('approver', 'first_name', fn(Builder $query) => $query->role(['superadmin', 'aux_admin']))
                            ->label('Aprobado por')
                            ->default(fn() => Auth::user()->hasAnyRole(['superadmin', 'aux_admin']) ? Auth::id() : null)
                            ->nullable()
                            ->afterStateHydrated(function (Forms\Components\Select $component, $state) {
                                if (blank($state) && Auth::user()->hasAnyRole(['superadmin', 'aux_admin'])) {
                                    $component->state(Auth::id());
                                }
                            })
                            ->getOptionLabelFromRecordUsing(fn($record) => $record->name)
                            ->searchable(['first_name', 'middle_name', 'first_surname', 'second_surname', 'email'])
                            ->preload()
                            ->disabled(fn() => ! Auth::user()->hasAnyRole(['superadmin', 'aux_admin']))
                            ->required(fn() => Auth::user()->hasAnyRole(['superadmin', 'aux_admin']))
                            ->rules(['nullable', 'exists:users,id'])
                            ->native(false),
                        Forms\Components\TextInput::make('subject')
                            ->label('Asignatura/Sesión')
                            ->maxLength(120)
                            ->required(),
                        Forms\Components\TextInput::make('purpose')
                            ->label('Propósito')
                            ->maxLength(180)
                            ->nullable(),
                        Forms\Components\Select::make('status')
                            ->label('Estado')
                            ->options(function () {
                                $allOptions = static::statusOptions();
                                $teacherOptions = ['pendiente', 'en_uso', 'finalizado', 'cancelado'];

                                if (Auth::user()?->hasRole('docente')) {
                                    return collect($allOptions)->only($teacherOptions)->toArray();
                                }

                                return $allOptions;
                            })
                            ->disabled(fn() => ! Auth::user()->hasAnyRole(['superadmin', 'aux_admin', 'docente']))
                            ->default('pendiente')
                            ->required(fn() => Auth::user()->hasAnyRole(['superadmin', 'aux_admin', 'docente']))
                            ->rules([
                                fn() => Rule::in(
                                    Auth::user()?->hasRole('docente')
                                        ? ['pendiente', 'en_uso', 'finalizado', 'cancelado']
                                        : array_keys(static::statusOptions())
                                ),
                            ])
                            ->native(false),
                    ])
                    ->columns(2),
                Forms\Components\Section::make('Agenda')
                    ->schema([
                        Forms\Components\DateTimePicker::make('scheduled_start_at')
                            ->label('Inicio programado')
                            ->seconds(false)
                            ->required()
                            ->afterOrEqual(Carbon::now())
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set) {
                                if (! $state) {
                                    return;
                                }

                                $start = Carbon::parse($state);

                                $set('scheduled_end_time', $start->copy()->addHours(3)->format('Y-m-d H:i:s'));
                            }),
                        Forms\Components\TimePicker::make('scheduled_end_time')
                            ->label('Fin programado')
                            ->native(false)
                            ->displayFormat('H:i')
                            ->format('Y-m-d H:i:s')
                            ->seconds(false)
                            ->required()
                            ->after('scheduled_start_at'),
                    ])
                    ->columns(2),
                Forms\Components\Section::make('Control de PCs')
                    ->schema([
                        Forms\Components\TextInput::make('pc_required')
                            ->label('PCs requeridos')
                            ->numeric()
                            ->integer()
                            ->minValue(1)
                            ->maxValue(fn() => static::getAvailableComputerCount())
                            ->required()
                            ->dehydrated(),
                        Forms\Components\TextInput::make('pc_disponibles')
                            ->label('PCs disponibles')
                            ->numeric()
                            ->default(fn() => static::getAvailableComputerCount())
                            ->afterStateHydrated(function (Forms\Components\TextInput $component) {
                                $component->state(static::getAvailableComputerCount());
                            })
                            ->disabled()
                            ->dehydrated(false)
                            ->helperText(fn() => Auth::user()->hasRole('docente') ? 'Campo gestionado por el laboratorio.' : null),
                        Forms\Components\TextInput::make('pc_unavailable')
                            ->label('PCs no disponibles')
                            ->numeric()
                            ->default(fn() => Computer::query()->where('status', 'no_disponible')->count())
                            ->minValue(0)
                            ->disabled()
                            ->helperText(fn() => Auth::user()->hasRole('docente') ? 'Campo gestionado por el laboratorio.' : null)
                            ->dehydrated(),
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
                            ->maxLength(1000)
                            ->rows(3),
                        Forms\Components\Textarea::make('notes')
                            ->label('Notas internas')
                            ->maxLength(1000)
                            ->rows(5),
                    ])
                    ->columns(1)
                    ->collapsible(),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                InfoSection::make('Información general')
                    ->schema([
                        TextEntry::make('classroom_code')->label('Aula'),
                        TextEntry::make('requester.name')->label('Docente solicitante'),
                        TextEntry::make('approver.name')->label('Aprobado por')->placeholder('—'),
                        TextEntry::make('subject')->label('Asignatura/Sesión'),
                        TextEntry::make('purpose')->label('Propósito')->placeholder('—')->columnSpanFull(),
                        TextEntry::make('status')
                            ->label('Estado')
                            ->formatStateUsing(fn(string $state) => match ($state) {
                                'pendiente' => 'Pendiente',
                                'aprobado' => 'Aprobado',
                                'rechazado' => 'Rechazado',
                                'en_uso' => 'En uso',
                                'finalizado' => 'Finalizado',
                                'cancelado' => 'Cancelado',
                                default => ucfirst($state),
                            }),
                    ])
                    ->columns(2),
                InfoSection::make('Agenda')
                    ->schema([
                        TextEntry::make('scheduled_start_at')
                            ->label('Inicio programado')
                            ->dateTime('d/m/Y H:i'),
                        TextEntry::make('scheduled_end_at')
                            ->label('Fin programado')
                            ->dateTime('d/m/Y H:i')
                            ->placeholder('—'),

                    ])
                    ->columns(2),
                InfoSection::make('Control de PCs')
                    ->schema([
                        TextEntry::make('pc_required')->label('PCs requeridos'),
                        TextEntry::make('pc_disponibles')
                            ->label('PCs disponibles')
                            ->state(fn() => static::getAvailableComputerCount()),
                        TextEntry::make('pc_unavailable')->label('PCs no disponibles'),
                        TextEntry::make('workstations_snapshot')
                            ->label('Estado rápido de estaciones')
                            ->state(function (ClassroomLoan $record): string {
                                $snapshot = $record->workstations_snapshot;

                                if (! is_array($snapshot) || empty($snapshot)) {
                                    return '—';
                                }

                                return collect($snapshot)
                                    ->map(fn($value, $key) => e($key) . ': ' . e($value))
                                    ->implode('<br>');
                            })
                            ->columnSpanFull()
                            ->html(),
                    ])
                    ->columns(3),
                InfoSection::make('Notas')
                    ->schema([
                        TextEntry::make('access_instructions')
                            ->label('Instrucciones de acceso')
                            ->markdown()
                            ->placeholder('—'),
                        TextEntry::make('notes')
                            ->label('Notas internas')
                            ->markdown()
                            ->placeholder('—'),
                    ])
                    ->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordUrl(fn ($record) => static::getUrl('edit', ['record' => $record]))
            ->columns([
                Tables\Columns\TextColumn::make('subject')
                    ->label('Sesión')
                    ->description(fn(ClassroomLoan $record) => $record->purpose)
                    ->searchable()
                    ->wrap(),
                Tables\Columns\TextColumn::make('requester.name')
                    ->label('Docente')
                    ->searchable(['first_name', 'middle_name', 'first_surname', 'second_surname', 'email']),
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
                    ->formatStateUsing(fn(string $state) => match ($state) {
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
                    ->numeric(),
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
                    ->label('Estado')
                    ->native(false)
                    ->options(static::statusOptions()),
                Tables\Filters\Filter::make('fecha')
                    ->form([
                        Forms\Components\DatePicker::make('from')
                            ->label('Desde'),
                        Forms\Components\DatePicker::make('until')
                            ->label('Hasta'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        $from = $data['from'] ?? null;
                        $until = $data['until'] ?? null;

                        if ($from && $until) {
                            $fromDate = Carbon::parse($from);
                            $untilDate = Carbon::parse($until);

                            if ($fromDate->greaterThan($untilDate)) {
                                [$fromDate, $untilDate] = [$untilDate, $fromDate];
                            }

                            return $query
                                ->whereDate('scheduled_start_at', '>=', $fromDate->toDateString())
                                ->whereDate('scheduled_end_at', '<=', $untilDate->toDateString());
                        }

                        return $query
                            ->when($from, fn($q, $date) => $q->whereDate('scheduled_start_at', '>=', $date))
                            ->when($until, fn($q, $date) => $q->whereDate('scheduled_end_at', '<=', $date));
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
            'view' => Pages\ViewClassroomLoan::route('/{record}'),
            'edit' => Pages\EditClassroomLoan::route('/{record}/edit'),
        ];
    }

    protected static function getAvailableComputerCount(): int
    {
        return Computer::query()->where('status', 'disponible')->count();
    }

    protected static function statusOptions(): array
    {
        return [
            'pendiente' => 'Pendiente',
            'aprobado' => 'Aprobado',
            'rechazado' => 'Rechazado',
            'en_uso' => 'En uso',
            'finalizado' => 'Finalizado',
            'cancelado' => 'Cancelado',
        ];
    }
}
