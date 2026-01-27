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
use Filament\Forms\Components\Hidden;
use Filament\Notifications\Notification;
use App\Helpers\RoleHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

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
                                ->helperText('Inicio del retiro por parte del solicitante.')
                                ->columnSpan(4),
                            Forms\Components\DateTimePicker::make('due_at')
                                ->label('Fecha de Devolución')
                                ->prefixIcon('heroicon-o-clock')
                                ->required()
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
                        ->columnSpanFull(),
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
                                        ->label('Material')
                                        ->prefixIcon('heroicon-o-cube')
                                        ->options(Material::query()->pluck('name', 'id'))
                                        ->required()
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
                                        ->required()
                                        ->rule(function (Forms\Get $get, Forms\Components\Component $component) {
                                            return function (string $attribute, $value, \Closure $fail) use ($get, $component) {
                                                $livewirePage = $component->getLivewire();
                                                $operation = 'edit';

                                                if (method_exists($livewirePage, 'getOperation')) {
                                                    $operation = $livewirePage->getOperation();
                                                } elseif (property_exists($livewirePage, 'operation')) {
                                                    $operation = $livewirePage->operation;
                                                }

                                                if ($operation !== 'create') {
                                                    return;
                                                }

                                                $material = Material::find($get('material_id'));
                                                if ($material && $value > $material->current_stock) {
                                                    $fail("No se puede prestar más de la cantidad disponible en stock ({$material->current_stock}).");
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
                                    Hidden::make('returned_qty'),
                                    Forms\Components\Checkbox::make('is_returned')
                                        ->label('Marcar como devuelto')
                                        ->helperText('Actualiza automáticamente la cantidad devuelta y el stock disponible.')
                                        ->live()
                                        ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                                            $set('returned_qty', $state ? $get('loan_qty') : 0);
                                        })
                                        ->afterStateHydrated(function (Forms\Components\Checkbox $component, Forms\Get $get) {
                                            $loanQty = (int) $get('loan_qty');
                                            $returnedQty = (int) $get('returned_qty');
                                            $component->state($loanQty > 0 && $loanQty === $returnedQty);
                                        })
                                        ->dehydrated(false)
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
                                $newReturnedQty = ($newItemState && isset($newItemState['is_returned']) && $newItemState['is_returned']) ? $newLoanQty : 0;
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
                        'primary' => ['pendiente', 'aprobado', 'en_curso'],
                        'success' => ['devuelto'],
                        'warning' => ['con_multa', 'vencido'],
                        'danger' => ['rechazado', 'cancelado', 'perdido'],
                    ])
                    ->formatStateUsing(fn (string $state) => ucfirst(str_replace('_', ' ', $state))),
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
                    ->query(fn (Builder $query) => $query->whereNull('return_at')->whereDate('due_at', '<', now())),
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
                                    sprintf('El préstamo %s está en curso. Recoge y devuelve el material antes del %s.', $record->loan_code ?? '#', optional($record->due_at)?->format('d/m/Y H:i') ?? 'plazo acordado'),
                                    'success'
                                );
                            }),
                        TableAction::make('rejectLoan')
                            ->label('Rechazar')
                            ->icon('heroicon-o-x-circle')
                            ->color('danger')
                            ->visible(fn (Loan $record) => RoleHelper::hasAnyRole(['superadmin', 'aux_admin']) && !in_array(self::resolveStatus($record), ['rechazado', 'en_curso', 'aprobado', 'devuelto'], true))
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
                                    'danger'
                                );
                            }),
                        TableAction::make('cancelLoan')
                            ->label('Cancelar')
                            ->icon('heroicon-o-no-symbol')
                            ->color('gray')
                            ->visible(fn (Loan $record) => RoleHelper::hasAnyRole(['superadmin', 'aux_admin']) && in_array(self::resolveStatus($record), ['aprobado', 'en_curso'], true))
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
                                    'warning'
                                );
                            }),

                    ])),

                Tables\Actions\EditAction::make()
                    ->after(function (Loan $record) {
                        // Do nothing if the loan is already marked as returned
                        if ($record->status === 'devuelto') {
                            return;
                        }

                        $allReturned = true;

                        // A loan with no items cannot be considered "returned"
                        if ($record->materials()->count() === 0) {
                            $allReturned = false;
                        } else {
                            // Use fresh() to get the latest pivot data just saved by the form
                            foreach ($record->fresh()->materials as $material) {
                                if ($material->pivot->returned_qty < $material->pivot->loan_qty) {
                                    $allReturned = false;
                                    break;
                                }
                            }
                        }

                        if ($allReturned) {
                            // If all items are fully returned, update the record
                            $record->update(['status' => 'devuelto', 'return_at' => $record->return_at ?? now()]);

                            Notification::make()
                                ->title('Préstamo Completado')
                                ->body('Todos los materiales han sido devueltos y el préstamo se ha marcado como "devuelto".')
                                ->success()
                                ->send();

                            self::notifyBorrower(
                                $record,
                                'Hemos registrado tu devolución',
                                'Gracias por devolver los materiales. El préstamo quedó cerrado sin novedades.',
                                'success'
                            );
                        }

                        if (!$allReturned && $record->due_at && $record->due_at->isPast() && self::resolveStatus($record) !== 'con_multa') {
                            $record->update(['status' => 'con_multa']);

                            Notification::make()
                                ->title('Préstamo con multa')
                                ->body('El préstamo ' . ($record->loan_code ?? '#') . ' superó la fecha límite y se marcó con multa.')
                                ->warning()
                                ->send();

                            self::notifyBorrower(
                                $record,
                                'Tu préstamo tiene multa por atraso',
                                sprintf('El préstamo %s venció el %s. Por favor devuelve los materiales o comunícate con el laboratorio.', $record->loan_code ?? '#', optional($record->due_at)?->format('d/m/Y H:i') ?? 'plazo indicado'),
                                'danger'
                            );
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

    protected static function resolveStatus(Loan $record): string
    {
        $status = self::normalizeStatus($record->status ?? 'pendiente');

        if ($record->return_at) {
            return 'devuelto';
        }

        if ($status === 'abierto') {
            $status = 'en_curso';
        }

        if (in_array($status, ['pendiente', 'aprobado', 'rechazado', 'cancelado', 'con_multa', 'perdido', 'devuelto', 'vencido', 'en_curso'], true)) {
            return $status;
        }

        if ($record->due_at && $record->due_at->isPast()) {
            return 'vencido';
        }

        return $status ?: 'pendiente';
    }

    protected static function getStatusFilterOptions(): array
    {
        return [
            'pendiente' => 'Pendiente',
            'aprobado' => 'Aprobado',
            'en_curso' => 'En curso',
            'rechazado' => 'Rechazado',
            'cancelado' => 'Cancelado',
            'devuelto' => 'Devuelto',
            'con_multa' => 'Con multa',
            'perdido' => 'Perdido',
        ];
    }

    protected static function normalizeStatus(?string $status): string
    {
        return Str::of($status ?? 'pendiente')->snake()->lower()->value();
    }

    protected static function renderIconCell(string $icon, string $content): HtmlString
    {
        return new HtmlString(sprintf(
            '<span class="flex items-center gap-2"><x-filament::icon icon="%s" class="h-4 w-4 text-primary-500" />%s</span>',
            $icon,
            e($content)
        ));
    }

    protected static function notifyBorrower(Loan $record, string $title, string $message, string $type = 'info'): void
    {
        $record->loadMissing('borrower');

        if (!$record->borrower) {
            return;
        }

        $notification = Notification::make()
            ->title($title)
            ->body($message)
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
}
