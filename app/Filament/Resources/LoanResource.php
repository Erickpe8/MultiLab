<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LoanResource\Pages;
use App\Filament\Resources\LoanResource\RelationManagers;
use App\Models\Loan;
use Filament\Forms;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action as TableAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use App\Models\Material;
use App\Models\User;
use Carbon\Carbon;
use Filament\Notifications\Notification;
use App\Helpers\RoleHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class LoanResource extends AppResource
{
    protected static ?string $model = Loan::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Gestión de Inventario';
    protected static ?string $modelLabel = 'Préstamo';
    protected static ?string $pluralModelLabel = 'Préstamos';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Personas y código')
                ->schema([
                    Forms\Components\Grid::make(12)
                        ->schema([
                            Forms\Components\Select::make('user_id')
                                ->relationship('borrower', 'first_name')
                                ->label('Solicitante')
                                ->prefixIcon('heroicon-o-user-circle')
                                ->getOptionLabelFromRecordUsing(fn (User $record) => $record->name)
                                ->searchable(['first_name', 'middle_name', 'first_surname', 'second_surname', 'email'])
                                ->preload()
                                ->required()
                                ->rules(['exists:users,id'])
                                ->helperText('Persona que recibirá los materiales.')
                                ->columnSpan(6),
                            Forms\Components\Select::make('issued_by')
                                ->relationship('issuer', 'first_name')
                                ->label('Entregado por')
                                ->prefixIcon('heroicon-o-clipboard-document-check')
                                ->getOptionLabelFromRecordUsing(fn (User $record) => $record->name)
                                ->searchable(['first_name', 'middle_name', 'first_surname', 'second_surname', 'email'])
                                ->preload()
                                ->required()
                                ->rules(['exists:users,id'])
                                ->helperText('Responsable de la entrega en laboratorio.')
                                ->columnSpan(6),
                            Forms\Components\Placeholder::make('borrower_contact')
                                ->label('Contacto del solicitante')
                                ->content(function (Forms\Get $get) {
                                    $userId = $get('user_id');
                                    $user = $userId ? User::find($userId) : null;

                                    return $user
                                        ? ($user->email ?? 'Sin correo registrado')
                                        : 'Selecciona un solicitante para ver su correo.';
                                })
                                ->columnSpan(6)
                                ->visible(fn (Forms\Get $get) => (bool) $get('user_id')),
                            Forms\Components\Placeholder::make('issuer_contact')
                                ->label('Contacto de entrega')
                                ->content(function (Forms\Get $get) {
                                    $issuerId = $get('issued_by');
                                    $issuer = $issuerId ? User::find($issuerId) : null;

                                    return $issuer
                                        ? ($issuer->email ?? 'Sin correo registrado')
                                        : 'Selecciona a la persona que entrega.';
                                })
                                ->columnSpan(6)
                                ->visible(fn (Forms\Get $get) => (bool) $get('issued_by')),
                        ]),
                    Forms\Components\TextInput::make('loan_code')
                        ->label('Código de Préstamo')
                        ->prefixIcon('heroicon-o-hashtag')
                        ->default('P-' . strtoupper(Str::random(8)))
                        ->readOnly()
                        ->required()
                        ->maxLength(32)
                        ->unique(ignoreRecord: true)
                        ->helperText('Se genera automáticamente, pero puedes copiarlo antes de guardar.'),
                ]),
            Forms\Components\Section::make('Agenda y seguimiento')
                ->schema([
                    Forms\Components\Grid::make(12)
                        ->schema([
                            Forms\Components\Placeholder::make('status_preview')
                                ->label('Estado estimado')
                                ->content(fn (?Loan $record) => $record?->status ? Str::headline($record->status) : 'Se definirá cuando guardes el préstamo.')
                                ->columnSpan(4),
                            Forms\Components\Placeholder::make('planned_duration')
                                ->label('Duración programada')
                                ->content(function (Forms\Get $get) {
                                    $loanAt = $get('loan_at');
                                    $dueAt = $get('due_at');

                                    if (!$loanAt || !$dueAt) {
                                        return 'Selecciona las fechas para calcular la duración.';
                                    }

                                    $start = $loanAt instanceof Carbon ? $loanAt : Carbon::parse($loanAt);
                                    $end = $dueAt instanceof Carbon ? $dueAt : Carbon::parse($dueAt);

                                    if ($end->lessThanOrEqualTo($start)) {
                                        return 'La devolución debe ser posterior al préstamo.';
                                    }

                                    $days = $start->diffInDays($end);
                                    $hours = $start->diffInHours($end) % 24;

                                    $segments = [];
                                    if ($days > 0) {
                                        $segments[] = $days . ' día' . ($days === 1 ? '' : 's');
                                    }
                                    if ($hours > 0) {
                                        $segments[] = $hours . ' h';
                                    }

                                    return $segments ? implode(' · ', $segments) : 'Menos de 1 hora';
                                })
                                ->columnSpan(4),
                            Forms\Components\Placeholder::make('due_preview')
                                ->label('Tiempo restante')
                                ->content(function (Forms\Get $get) {
                                    $dueAt = $get('due_at');
                                    if (!$dueAt) {
                                        return 'Falta definir la fecha de devolución.';
                                    }

                                    $due = $dueAt instanceof Carbon ? $dueAt : Carbon::parse($dueAt);

                                    return $due->isPast()
                                        ? 'Vencido hace ' . $due->diffForHumans()
                                        : 'Quedan ' . now()->diffForHumans($due);
                                })
                                ->columnSpan(4),
                        ]),
                    Forms\Components\Grid::make(12)
                        ->schema([
                            Forms\Components\DateTimePicker::make('loan_at')
                                ->label('Fecha de Préstamo')
                                ->prefixIcon('heroicon-o-calendar')
                                ->required()
                                ->native(false)
                                ->minDate(now()->startOfDay())
                                ->displayFormat('d/m/Y H:i')
                                ->format('Y-m-d H:i')
                                ->timezone(config('app.timezone'))
                                ->seconds(false)
                                ->helperText('Inicio del retiro por parte del solicitante.')
                                ->columnSpan(4),
                            Forms\Components\DateTimePicker::make('due_at')
                                ->label('Fecha de Devolución')
                                ->prefixIcon('heroicon-o-clock')
                                ->required()
                                ->native(false)
                                ->maxDate(now()->addYears(5))
                                ->displayFormat('d/m/Y H:i')
                                ->format('Y-m-d H:i')
                                ->timezone(config('app.timezone'))
                                ->seconds(false)
                                ->afterOrEqual('loan_at')
                                ->validationMessages([
                                    'after_or_equal' => 'La fecha de devolución no puede ser anterior a la fecha de préstamo.',
                                ])
                                ->helperText('Fecha comprometida para la devolución.')
                                ->columnSpan(4),
                            Forms\Components\DateTimePicker::make('return_at')
                                ->label('Fecha de Devolución Real')
                                ->prefixIcon('heroicon-o-arrow-uturn-left')
                                ->hiddenOn('create')
                                ->readOnly()
                                ->helperText('Se registra automáticamente cuando todos los materiales se devuelven.')
                                ->columnSpan(4),
                        ]),
                    Forms\Components\Textarea::make('notes')
                        ->label('Notas internas')
                        ->placeholder('Observaciones relevantes, condiciones especiales o daños detectados...')
                        ->rows(4)
                        ->maxLength(1500)
                        ->columnSpanFull(),
                ]),
            Forms\Components\Section::make('Estado del préstamo')
                ->description('Actualiza el estado operativo del préstamo y aplica acciones rápidas para devoluciones controladas.')
                ->schema([
                    Forms\Components\Grid::make(12)
                        ->schema([
                            Forms\Components\Select::make('status')
                                ->label('Estado actual')
                                ->options(self::statusOptions())
                                ->default('pendiente')
                                ->helperText('Utiliza este campo para marcar devoluciones completas, con multa o estados administrativos como rechazado/cancelado.')
                                ->required()
                                ->rules([Rule::in(array_keys(self::statusOptions()))])
                                ->columnSpan(6),
                            Forms\Components\ToggleButtons::make('mass_return_action')
                                ->label('Acciones rápidas')
                                ->options([
                                    'return_all' => 'Marcar todo devuelto',
                                ])
                                ->icons([
                                    'return_all' => 'heroicon-o-arrow-uturn-left',
                                ])
                                ->colors([
                                    'return_all' => 'success',
                                ])
                                ->visibleOn('edit')
                                ->dehydrated(false)
                                ->helperText('Disponible solo en edición: aplica el estado a todos los materiales y actualiza el préstamo.')
                                ->columnSpan(6)
                                ->live()
                                ->afterStateUpdated(function (?string $state, Forms\Set $set, Forms\Get $get) {
                                    if (!$state) {
                                        return;
                                    }

                                    $materials = collect($get('materials') ?? [])->map(function ($item) use ($state) {
                                        $loanQty = (int) ($item['loan_qty'] ?? 0);

                                        if ($state === 'return_all') {
                                            $item['returned_qty'] = $loanQty;
                                        }

                                        return $item;
                                    })->toArray();

                                    $set('materials', $materials);

                                    if ($state === 'return_all') {
                                        $set('status', 'devuelto');
                                        $set('return_at', now());
                                    }

                                    $set('mass_return_action', null);
                                }),
                        ]),
                ]),
            Forms\Components\Section::make('Materiales del préstamo')
                ->schema([
                    Forms\Components\Repeater::make('materials')
                        ->relationship()
                        ->label('Materiales en este préstamo')
                        ->helperText('Agrega cada material con su cantidad prestada. El stock disponible se recalcula al guardar.')
                        ->addActionLabel('Agregar material al préstamo')
                        ->columnSpanFull()
                        ->schema([
                            Forms\Components\Grid::make(12)
                                ->schema([
                                    Forms\Components\Select::make('material_id')
                                        ->searchable()
                                        ->label('Material')
                                        ->prefixIcon('heroicon-o-cube')
                                        ->options(Material::query()->pluck('name', 'id'))
                                        ->required()
                                        ->rules(['exists:materials,id'])
                                        ->disabledOn('edit')
                                        ->live()
                                        ->afterStateUpdated(function (Forms\Set $set) {
                                            $set('loan_qty', null);
                                            $set('is_returned', false);
                                        })
                                        ->columnSpan(5),
                                    Forms\Components\Placeholder::make('current_stock_display')
                                        ->label('Stock disponible')
                                        ->content(function (Forms\Get $get) {
                                            $materialId = $get('material_id');
                                            if ($materialId) {
                                                $material = Material::find($materialId);
                                                return $material ? (string) $material->current_stock : 'N/A';
                                            }

                                            return 'Selecciona un material';
                                        })
                                        ->visible(fn (Forms\Get $get) => (bool) $get('material_id'))
                                        ->columnSpan(3),
                                    Forms\Components\TextInput::make('loan_qty')
                                        ->label('Cantidad prestada')
                                        ->prefixIcon('heroicon-o-clipboard-document-list')
                                        ->numeric()
                                        ->minValue(1)
                                        ->required()
                                        ->rule(function (Forms\Get $get) {
                                            return function (string $attribute, $value, \Closure $fail) use ($get) {
                                                $materialId = $get('material_id');

                                                // If no material is selected, or material not found, defer to other rules or stop.
                                                if (!$materialId || !($material = Material::find($materialId))) {
                                                    return;
                                                }

                                                $currentStock = $material->current_stock;

                                                // Get all materials currently in the repeater for this form
                                                // Access the parent repeater's state. '..' goes up one level in the component tree.
                                                $allRepeaterItems = $get('../../materials');

                                                $totalLoanedInCurrentForm = 0;
                                                foreach ($allRepeaterItems as $item) {
                                                    if (($item['material_id'] ?? null) === $materialId) {
                                                        // Sum up loan_qty for the same material across all repeater items
                                                        $totalLoanedInCurrentForm += (int) ($item['loan_qty'] ?? 0);
                                                    }
                                                }

                                                // Check if the total loan quantity for this material in the current form
                                                // exceeds the available stock.
                                                if ($totalLoanedInCurrentForm > $currentStock) {
                                                    $fail("La cantidad total prestada para '{$material->name}' (actualmente {$totalLoanedInCurrentForm}) excede el stock disponible ({$currentStock}).");
                                                }
                                            };
                                        })
                                        ->disabledOn('edit')
                                        ->columnSpan(4),
                                    Forms\Components\Placeholder::make('pending_qty')
                                        ->label('Cantidad pendiente')
                                        ->content(function (Forms\Get $get) {
                                            $loanQty = (int) ($get('loan_qty') ?? 0);
                                            $returnedQty = (int) ($get('returned_qty') ?? 0);

                                            if ($loanQty === 0) {
                                                return 'Define una cantidad para ver el saldo.';
                                            }

                                            $pending = max($loanQty - $returnedQty, 0);

                                            return $pending === 0
                                                ? 'Sin pendientes'
                                                : $pending . ' unidad' . ($pending === 1 ? '' : 'es') . ' por devolver';
                                        })
                                        ->visible(fn (Forms\Get $get) => filled($get('loan_qty')))
                                        ->columnSpan(4),
                                    Forms\Components\TextInput::make('returned_qty')
                                        ->label('Cantidad devuelta')
                                        ->numeric()
                                        ->default(0)
                                        ->minValue(0)
                                        ->maxValue(fn (Forms\Get $get) => (int) ($get('loan_qty') ?? 0))
                                        ->helperText('Permite registrar devoluciones parciales durante la edición.')
                                        ->dehydrated(true)
                                        ->columnSpan(4),
                                    Forms\Components\ToggleButtons::make('material_state')
                                        ->label('Estado del material')
                                        ->options([
                                            'pending' => 'En préstamo',
                                            'returned' => 'Devuelto',
                                        ])
                                        ->icons([
                                            'pending' => 'heroicon-o-ellipsis-horizontal',
                                            'returned' => 'heroicon-o-check-circle',
                                        ])
                                        ->colors([
                                            'pending' => 'warning',
                                            'returned' => 'success',
                                        ])
                                        ->visibleOn('edit')
                                        ->dehydrated(false)
                                        ->inline()
                                        ->afterStateHydrated(function (Forms\Components\ToggleButtons $component, Forms\Get $get) {
                                            $loanQty = (int) ($get('loan_qty') ?? 0);
                                            $returnedQty = (int) ($get('returned_qty') ?? 0);
                                            $component->state(($loanQty > 0 && $returnedQty >= $loanQty) ? 'returned' : 'pending');
                                        })
                                        ->afterStateUpdated(function (?string $state, Forms\Set $set, Forms\Get $get) {
                                            if (!$state) {
                                                return;
                                            }

                                            $loanQty = (int) ($get('loan_qty') ?? 0);
                                            $set('returned_qty', $state === 'returned' ? $loanQty : 0);
                                        })
                                        ->columnSpan(4),
                                ]),
                        ])
                        ->saveRelationshipsUsing(function (Loan $record, $state) {
                            $existingMaterials = $record->materials()->get()->keyBy('id');
                            $materialsToSync = [];
                            $newState = collect($state)->keyBy('material_id');

                            $allMaterialIds = $existingMaterials->keys()->union($newState->keys());

                            foreach ($allMaterialIds as $materialId) {
                                if (!$materialId) {
                                    continue;
                                }

                                $existingPivot = $existingMaterials->get($materialId)?->pivot;
                                $newItemState = $newState->get($materialId);

                                $oldOnLoan = $existingPivot ? ((int) $existingPivot->loan_qty - (int) $existingPivot->returned_qty) : 0;
                                $newLoanQty = $newItemState ? (int) $newItemState['loan_qty'] : 0;
                                $newReturnedQty = $newItemState ? min((int) ($newItemState['returned_qty'] ?? 0), $newLoanQty) : 0;
                                $newOnLoan = $newItemState ? ($newLoanQty - $newReturnedQty) : 0;

                                $stockChange = $oldOnLoan - $newOnLoan;

                                if ($stockChange !== 0) {
                                    $materialModel = Material::find($materialId);
                                    if ($materialModel) {
                                        $materialModel->increment('current_stock', $stockChange);
                                    }
                                }

                                if ($newItemState) {
                                    $materialsToSync[$materialId] = [
                                        'loan_qty' => $newLoanQty,
                                        'returned_qty' => $newReturnedQty,
                                    ];
                                }
                            }

                            $record->materials()->sync($materialsToSync);
                        }),
                ]),
        ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                if (RoleHelper::isEstudiante() || RoleHelper::isDocente()) {
                    $query->where('user_id', Auth::id());
                }
            })
            ->columns([
                Tables\Columns\TextColumn::make('borrower.name')
                    ->label('Solicitante')
                    ->formatStateUsing(fn (?string $state) => self::renderIconCell('heroicon-o-user-circle', $state ?? 'Sin solicitante'))
                    ->html()
                    ->searchable(['first_name', 'middle_name', 'first_surname', 'second_surname', 'email'])
                    ->sortable(),
                Tables\Columns\TextColumn::make('issuer.name')
                    ->label('Entregado por')
                    ->formatStateUsing(fn (?string $state) => self::renderIconCell('heroicon-o-clipboard-document-check', $state ?? 'Sin asignar'))
                    ->html()
                    ->searchable(['first_name', 'middle_name', 'first_surname', 'second_surname', 'email'])
                    ->sortable(),
                Tables\Columns\TextColumn::make('loan_code')
                    ->label('Código de Préstamo')
                    ->formatStateUsing(fn (?string $state) => self::renderIconCell('heroicon-o-hashtag', $state ?? 'N/A'))
                    ->html()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('loan_at')
                    ->label('Inicio del prestamo')
                    ->formatStateUsing(fn ($state) => self::renderIconCell('heroicon-o-calendar', $state ? $state->format('d/m/Y H:i') : 'Sin definir'))
                    ->html()
                    ->sortable(),
                Tables\Columns\TextColumn::make('due_at')
                    ->label('Fin del prestamo')
                    ->formatStateUsing(fn ($state) => self::renderIconCell('heroicon-o-clock', $state ? $state->format('d/m/Y H:i') : 'Sin definir'))
                    ->html()
                    ->sortable(),
                Tables\Columns\TextColumn::make('return_at')
                    ->label('Entrega del prestamo')
                    ->formatStateUsing(fn ($state) => self::renderIconCell('heroicon-o-arrow-uturn-left', $state ? $state->format('d/m/Y H:i') : 'Pendiente'))
                    ->html()
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Estado')
                    ->getStateUsing(fn (Loan $record): string => self::resolveStatus($record))
                    ->colors([
                        'primary' => fn (string $state): bool => in_array($state, ['pendiente', 'en_curso'], true),
                        'success' => fn (string $state): bool => $state === 'devuelto',
                        'warning' => fn (string $state): bool => in_array($state, ['devuelto_con_multa', 'vencido'], true),
                        'danger' => fn (string $state): bool => in_array($state, ['rechazado', 'cancelado'], true),
                    ])
                    ->formatStateUsing(fn (string $state) => Str::of($state)->replace('_', ' ')->title()),
                Tables\Columns\TextColumn::make('materials_summary')
                    ->label('Devolución')
                    ->getStateUsing(fn (Loan $record) => self::renderReturnSummary($record))
                    ->html()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Estado')
                    ->options(self::getStatusFilterOptions())
                    ->searchable()
                    ->placeholder('Todos los estados')
                    ->preload(),
                Tables\Filters\Filter::make('pending_unattended')
                    ->label('Pendientes sin atender')
                    ->query(fn (Builder $query) => $query->where('status', 'pendiente')->whereNull('issued_by')),
                Tables\Filters\Filter::make('overdue_loans')
                    ->label('Préstamos vencidos')
                    ->query(fn (Builder $query) => $query->whereNull('return_at')->whereDate('due_at', '<', now())->where('status', '!=', 'devuelto')),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Ver detalle')
                    ->icon('heroicon-o-eye')
                    ->modalHeading('Detalle del préstamo')
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Cerrar')
                    ->modalWidth('7xl')
                    ->modalActions(fn (): array => array_filter([
                        TableAction::make('approveLoan')
                            ->label('Aprobar')
                            ->icon('heroicon-o-check-badge')
                            ->color('success')
                            ->visible(fn (Loan $record) => RoleHelper::hasAnyRole(['superadmin', 'aux_admin']) && in_array(self::resolveStatus($record), ['pendiente', 'rechazado', 'cancelado']))
                            ->requiresConfirmation()
                            ->action(function (Loan $record) {
                                $record->update([
                                    'status' => 'en_curso',
                                    'issued_by' => $record->issued_by ?? Auth::id(),
                                ]);

                                Notification::make()
                                    ->title('Préstamo aprobado')
                                    ->body('El préstamo ' . ($record->loan_code ?? '#') . ' fue aprobado correctamente.')
                                    ->success()
                                    ->send();

                                self::notifyBorrower(
                                    $record,
                                    'Tu préstamo fue aprobado',
                                    sprintf('El préstamo %s ya está en curso. Coordina el retiro y devuelve el material antes del %s.', $record->loan_code ?? '#', optional($record->due_at)?->format('d/m/Y H:i') ?? 'plazo acordado'),
                                    'success',
                                    self::returnSummaryText($record)
                                );
                            }),
                        TableAction::make('rejectLoan')
                            ->label('Rechazar')
                            ->icon('heroicon-o-x-circle')
                            ->color('danger')
                            ->visible(fn (Loan $record) => RoleHelper::hasAnyRole(['superadmin', 'aux_admin']) && !in_array(self::resolveStatus($record), ['rechazado', 'cancelado', 'devuelto', 'devuelto_con_multa', 'vencido', 'en_curso'], true))
                            ->requiresConfirmation()
                            ->action(function (Loan $record) {
                                $record->update([
                                    'status' => 'rechazado',
                                    'return_at' => null,
                                ]);

                                Notification::make()
                                    ->title('Préstamo rechazado')
                                    ->body('El préstamo ' . ($record->loan_code ?? '#') . ' fue marcado como rechazado.')
                                    ->danger()
                                    ->send();

                                self::notifyBorrower(
                                    $record,
                                    'Tu préstamo fue rechazado',
                                    'El laboratorio no pudo aprobar tu solicitud. Revisa las observaciones o solicita nuevamente.',
                                    'danger',
                                    self::returnSummaryText($record)
                                );
                            }),
                        TableAction::make('cancelLoan')
                            ->label('Cancelar')
                            ->icon('heroicon-o-no-symbol')
                            ->color('gray')
                            ->visible(fn (Loan $record) => RoleHelper::hasAnyRole(['superadmin', 'aux_admin']) && in_array(self::resolveStatus($record), ['pendiente', 'en_curso'], true))
                            ->requiresConfirmation()
                            ->action(function (Loan $record) {
                                $record->update([
                                    'status' => 'cancelado',
                                    'return_at' => null,
                                ]);

                                Notification::make()
                                    ->title('Préstamo cancelado')
                                    ->body('El préstamo ' . ($record->loan_code ?? '#') . ' fue cancelado.')
                                    ->warning()
                                    ->send();

                                self::notifyBorrower(
                                    $record,
                                    'Préstamo cancelado',
                                    'El laboratorio canceló tu solicitud. Comunícate con el equipo para más detalles.',
                                    'warning',
                                    self::returnSummaryText($record)
                                );
                            }),

                    ])),
                Tables\Actions\EditAction::make()
                    ->visible(fn () => RoleHelper::hasAnyRole(['superadmin', 'aux_admin']))
                    ->after(function (Loan $record) {
                        $record->refresh();

                        $stats = self::materialReturnSnapshot($record);
                        $totalLoaned = $stats['total'];
                        $totalReturned = $stats['returned'];

                        if ($totalLoaned === 0) {
                            return;
                        }

                        $allReturned = $totalReturned >= $totalLoaned;
                        $anyReturned = $totalReturned > 0;
                        $isLate = $record->due_at && $record->due_at->isPast();

                        if ($allReturned) {
                            $newStatus = $isLate ? 'devuelto_con_multa' : 'devuelto';
                            $record->update([
                                'status' => $newStatus,
                                'return_at' => $record->return_at ?? now(),
                            ]);

                            Notification::make()
                                ->title($newStatus === 'devuelto' ? 'Préstamo devuelto' : 'Préstamo devuelto con multa')
                                ->body($newStatus === 'devuelto'
                                    ? 'Todos los materiales han sido devueltos a tiempo.'
                                    : 'La devolución se registró con atraso o con faltantes. Se aplicó multa.')
                                ->{$newStatus === 'devuelto' ? 'success' : 'warning'}()
                                ->send();

                            self::notifyBorrower(
                                $record,
                                $newStatus === 'devuelto' ? 'Hemos registrado tu devolución' : 'Registramos tu devolución con multa',
                                $newStatus === 'devuelto'
                                    ? 'Gracias por devolver todos los materiales dentro del plazo acordado.'
                                    : 'Detectamos atraso o faltantes en tu devolución. Acércate al laboratorio para completar el proceso.',
                                $newStatus === 'devuelto' ? 'success' : 'warning',
                                self::returnSummaryText($record)
                            );

                            return;
                        }

                        if ($isLate) {
                            if ($anyReturned) {
                                $record->update(['status' => 'devuelto_con_multa']);

                                Notification::make()
                                    ->title('Devolución incompleta o tardía')
                                    ->body('El préstamo ' . ($record->loan_code ?? '#') . ' se marcó con multa por atraso o faltantes.')
                                    ->warning()
                                    ->send();

                                self::notifyBorrower(
                                    $record,
                                    'Tu devolución tiene observaciones',
                                    sprintf('Registramos tu devolución del préstamo %s, pero quedó con multa por atraso o materiales faltantes.', $record->loan_code ?? '#'),
                                    'warning',
                                    self::returnSummaryText($record)
                                );
                            } else {
                                if ($record->status !== 'vencido') {
                                    $record->update(['status' => 'vencido']);
                                }

                                Notification::make()
                                    ->title('Préstamo vencido')
                                    ->body('El préstamo ' . ($record->loan_code ?? '#') . ' superó la fecha límite y no registra devoluciones.')
                                    ->danger()
                                    ->send();

                                self::notifyBorrower(
                                    $record,
                                    'Tu préstamo está vencido',
                                    sprintf('No hemos recibido devoluciones del préstamo %s y el plazo terminó el %s. Comunícate con el laboratorio a la brevedad.', $record->loan_code ?? '#', optional($record->due_at)?->format('d/m/Y H:i') ?? 'plazo indicado'),
                                    'danger',
                                    self::returnSummaryText($record)
                                );
                            }
                        }
                    }),
            ])
            ->bulkActions((RoleHelper::isEstudiante() || RoleHelper::isDocente()) ? [] : [
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageLoans::route('/'),
            'create' => Pages\CreateLoan::route('/create'),
        ];
    }

    public static function getStatusFilterOptions(): array
    {
        return self::statusOptions();
    }

    protected static function statusOptions(): array
    {
        return [
            'pendiente' => 'Pendiente',
            'en_curso' => 'En curso',
            'rechazado' => 'Rechazado',
            'cancelado' => 'Cancelado',
            'devuelto' => 'Devuelto',
            'devuelto_con_multa' => 'Devuelto con multa',
            'vencido' => 'Vencido',
        ];
    }

    protected static function renderIconCell(string $icon, string $content): HtmlString
    {
        return new HtmlString(sprintf(
            '<span class="flex items-center gap-2"><x-filament::icon icon="%s" class="h-4 w-4 text-primary-500" />%s</span>',
            $icon,
            e($content)
        ));
    }

    protected static function resolveStatus(Loan $record): string
    {
        $status = self::normalizeStatus($record->status ?? 'pendiente');

        $legacyMap = [
            'abierto' => 'pendiente',
            'aprobado' => 'en_curso',
            'en_curso' => 'en_curso',
            'con_multa' => 'devuelto_con_multa',
            'devuelto_en_fecha' => 'devuelto',
            'perdido' => 'devuelto_con_multa',
        ];

        if (array_key_exists($status, $legacyMap)) {
            $status = $legacyMap[$status];
        }

        if ($record->return_at) {
            $returnedLate = $record->due_at && $record->return_at->greaterThan($record->due_at);
            return $returnedLate ? 'devuelto_con_multa' : 'devuelto';
        }

        if (! in_array($status, array_keys(self::statusOptions()), true)) {
            if ($record->due_at && $record->due_at->isPast()) {
                return 'vencido';
            }

            return 'pendiente';
        }

        if ($status === 'devuelto') {
            $record->updateQuietly(['return_at' => $record->return_at ?? now()]);
        }

        return $status;
    }

    protected static function normalizeStatus(?string $status): string
    {
        return Str::of($status ?? 'pendiente')->snake()->lower()->value();
    }

    protected static function renderReturnSummary(Loan $record): string
    {
        $snapshot = self::materialReturnSnapshot($record);
        $total = $snapshot['total'];
        $returned = $snapshot['returned'];
        $state = self::resolveStatus($record);

        if ($total === 0) {
            return '<span class="text-slate-500 text-xs">Sin materiales</span>';
        }

        $badgeClass = 'text-xs rounded-full px-3 py-1 inline-flex items-center gap-1 ';

        if ($state === 'devuelto') {
            $badgeClass .= 'bg-emerald-100 text-emerald-700';
            return sprintf('<span class="%s"><x-filament::icon icon="heroicon-o-check-circle" class="h-4 w-4" />%d/%d devueltos</span>', $badgeClass, $returned, $total);
        }

        if ($state === 'devuelto_con_multa') {
            $badgeClass .= 'bg-amber-100 text-amber-800';
            return sprintf('<span class="%s"><x-filament::icon icon="heroicon-o-exclamation-circle" class="h-4 w-4" />%d/%d devueltos</span>', $badgeClass, $returned, $total);
        }

        if ($returned === 0) {
            return '<span class="text-xs text-rose-600">0 devueltos</span>';
        }

        $badgeClass .= 'bg-cyan-100 text-cyan-800';

        return sprintf('<span class="%s">%d/%d devueltos</span>', $badgeClass, $returned, $total);
    }

    protected static function returnSummaryText(Loan $record): string
    {
        $record->loadMissing('materials', 'materials.pivot');
        $parts = [];

        foreach ($record->materials as $material) {
            $parts[] = sprintf('%s: %d/%d devueltos',
                $material->name,
                min((int) $material->pivot->returned_qty, (int) $material->pivot->loan_qty),
                (int) $material->pivot->loan_qty,
            );
        }

        return implode(PHP_EOL, $parts);
    }

    protected static function materialReturnSnapshot(Loan $record): array
    {
        $record->loadMissing('materials');
        $total = 0;
        $returned = 0;

        foreach ($record->materials as $material) {
            $loanQty = (int) $material->pivot->loan_qty;
            $total += $loanQty;
            $returned += min((int) $material->pivot->returned_qty, $loanQty);
        }

        return [
            'total' => $total,
            'returned' => $returned,
        ];
    }

    protected static function notifyBorrower(Loan $record, string $title, string $message, string $type = 'info', ?string $footer = null): void
    {
        $record->loadMissing('borrower');

        if (! $record->borrower) {
            return;
        }

        $notification = Notification::make()
            ->title($title)
            ->body($footer ? $message . PHP_EOL . PHP_EOL . $footer : $message)
            ->duration('persistent');

        match ($type) {
            'success' => $notification->success(),
            'warning' => $notification->warning(),
            'danger' => $notification->danger(),
            default => $notification->info(),
        };

        $notification->sendToDatabase($record->borrower);
    }

    public static function canCreate(): bool
    {
        return RoleHelper::hasAnyRole(['superadmin', 'aux_admin']);
    }

    public static function canDelete($record): bool
    {
        if (RoleHelper::isEstudiante() || RoleHelper::isDocente()) {
            return false;
        }

        return parent::canDelete($record);
    }

    public static function canDeleteAny(): bool
    {
        if (RoleHelper::isEstudiante() || RoleHelper::isDocente()) {
            return false;
        }

        return parent::canDeleteAny();
    }

    public static function canEdit($record): bool
    {
        return RoleHelper::hasAnyRole(['superadmin', 'aux_admin']);
    }

    public static function canEditAny(): bool
    {
        return RoleHelper::hasAnyRole(['superadmin', 'aux_admin']);
    }
}
