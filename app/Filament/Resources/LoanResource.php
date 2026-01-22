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
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use App\Models\Material;
use App\Models\User;
use Carbon\Carbon;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Notifications\Notification;
use Illuminate\Support\Str; // Added this line // Added this use statement

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
            Tabs::make('loan_flow')
                ->columnSpanFull()
                ->tabs([
                    Tab::make('Personas & Código')
                        ->icon('heroicon-o-identification')
                        ->schema([
                            Forms\Components\Section::make('Equipo involucrado')
                                ->description('Selecciona a las personas responsables para que el sistema emita notificaciones claras.')
                                ->schema([
                                    Forms\Components\Grid::make(12)
                                        ->schema([
                                            Forms\Components\Select::make('user_id')
                                                ->relationship('borrower', 'first_name')
                                                ->label('Solicitante')
                                                ->getOptionLabelFromRecordUsing(fn (User $record) => $record->name)
                                                ->searchable(['first_name', 'middle_name', 'first_surname', 'second_surname', 'email'])
                                                ->preload()
                                                ->required()
                                                ->helperText('Persona que recibirá los materiales.')
                                                ->columnSpan(6),
                                            Forms\Components\Select::make('issued_by')
                                                ->relationship('issuer', 'first_name')
                                                ->label('Entregado por')
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
                                ]),
                            Forms\Components\Section::make('Identificación del préstamo')
                                ->description('El código sirve como folio para seguimiento y comunicación con la persona solicitante.')
                                ->schema([
                                    Forms\Components\TextInput::make('loan_code')
                                        ->label('Código de Préstamo')
                                        ->default('P-' . strtoupper(Str::random(8)))
                                        ->readOnly()
                                        ->required()
                                        ->maxLength(32)
                                        ->helperText('Se genera automáticamente, pero puedes copiarlo o ajustarlo antes de guardar.'),
                                ]),
                        ]),
                    Tab::make('Agenda & Seguimiento')
                        ->icon('heroicon-o-calendar-days')
                        ->schema([
                            Forms\Components\Section::make('Estado y contexto')
                                ->description('Revisa la línea de tiempo para evitar préstamos con fechas inconsistentes.')
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
                                ]),
                            Forms\Components\Section::make('Fechas clave')
                                ->description('Controla los hitos del préstamo con precisión horaria.')
                                ->schema([
                                    Forms\Components\Grid::make(12)
                                        ->schema([
                                            Forms\Components\DateTimePicker::make('loan_at')
                                                ->label('Fecha de Préstamo')
                                                ->required()
                                                ->helperText('Inicio del retiro por parte del solicitante.')
                                                ->columnSpan(4),
                                            Forms\Components\DateTimePicker::make('due_at')
                                                ->label('Fecha de Devolución')
                                                ->required()
                                                ->helperText('Fecha comprometida para la devolución.')
                                                ->columnSpan(4),
                                            Forms\Components\DateTimePicker::make('return_at')
                                                ->label('Fecha de Devolución Real')
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
                        ]),
                    Tab::make('Materiales')
                        ->icon('heroicon-o-clipboard-document-list')
                        ->schema([
                            Forms\Components\Section::make('Ítems Prestados')
                                ->description('Cada registro ajusta automáticamente las existencias del inventario.')
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
                        ]),
                ]),
        ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Información del Préstamo')
                    ->schema([
                        Infolists\Components\TextEntry::make('borrower.name')->label('Solicitante'),
                        Infolists\Components\TextEntry::make('issuer.name')->label('Entregado por'),
                        Infolists\Components\TextEntry::make('loan_code')->label('Código de Préstamo'),
                        Infolists\Components\TextEntry::make('status')->label('Estado')->badge(),
                        Infolists\Components\TextEntry::make('loan_at')->label('Fecha de Préstamo')->dateTime(),
                        Infolists\Components\TextEntry::make('due_at')->label('Fecha de Devolución')->dateTime(),
                        Infolists\Components\TextEntry::make('return_at')->label('Fecha de Devolución Real')->dateTime(),
                    ])->columns(2),
                Infolists\Components\Section::make('Materiales Prestados')
                    ->schema([
                        Infolists\Components\RepeatableEntry::make('materials')
                            ->hiddenLabel()
                            ->schema([
                                Infolists\Components\TextEntry::make('name')
                                    ->label('Material')
                                    ->weight('bold'),
                                Infolists\Components\TextEntry::make('pivot.loan_qty')
                                    ->label('Cantidad Prestada'),
                                Infolists\Components\TextEntry::make('pivot.returned_qty')
                                    ->label('Cantidad Devuelta')
                                    ->badge()
                                    ->color(fn (string $state, $record) => $state < $record->pivot->loan_qty ? 'warning' : 'success'),
                            ])
                            ->columns(3),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('borrower.name')
                    ->label('Solicitante')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('issuer.name')
                    ->label('Entregado por')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('loan_code')
                    ->label('Código de Préstamo')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('loan_at')
                    ->label('Fecha de Préstamo')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('due_at')
                    ->label('Fecha de Devolución')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('return_at')
                    ->label('Fecha de Devolución Real')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Estado')
                    ->getStateUsing(function (Loan $record): string {
                        if ($record->return_at) {
                            return 'devuelto';
                        }
                        if (in_array($record->status, ['con_multa', 'perdido'])) {
                            return $record->status;
                        }
                        if ($record->due_at->isPast()) {
                            return 'vencido';
                        }
                        return 'abierto';
                    })
                    ->colors([
                        'primary' => 'abierto',
                        'success' => 'devuelto',
                        'danger' => 'vencido',
                        'warning' => ['con_multa', 'perdido'],
                    ]),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha de Creación')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Fecha de Actualización')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
                        ->actions([
                            Tables\Actions\EditAction::make()
                                ->label('Ver')
                                ->icon('heroicon-o-eye')
                                ->color('gray')
                                ->modalWidth('4xl')
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
                                    }
                                }),
                        ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageLoans::route('/'),
        ];
    }
}
