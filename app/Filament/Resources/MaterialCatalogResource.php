<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MaterialCatalogResource\Pages;
use App\Helpers\RoleHelper;
use App\Models\Material;
use App\Models\Loan;
use App\Models\User;
use App\Support\CategoryIconResolver;
use App\Notifications\NewLoanRequestNotification;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification as NotificationFacade;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use Filament\Notifications\Notification;
use Illuminate\Validation\ValidationException;

class MaterialCatalogResource extends AppResource
{
    protected static ?string $model = Material::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationGroup = 'Solicitudes de estudiantes';
    protected static ?string $modelLabel = 'Material disponible';
    protected static ?string $pluralModelLabel = 'Material para préstamo';

    public static function canViewAny(): bool
    {
        return RoleHelper::hasAnyRole(['estudiante', 'docente']);
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit(Model $record): bool
    {
        return false;
    }

    public static function canDelete(Model $record): bool
    {
        return false;
    }

    public static function canForceDelete(Model $record): bool
    {
        return false;
    }

    public static function canRestore(Model $record): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn ($query) => $query->with(['category', 'unit']))
            ->columns([
                TextColumn::make('sku')
                    ->label('Código')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('name')
                    ->label('Material')
                    ->description(fn (Material $record) => $record->category?->name)
                    ->formatStateUsing(function (string $state, Material $record) {
                        $icon = CategoryIconResolver::resolve($record->category?->name);

                        return new HtmlString(sprintf(
                            '<span class="flex items-center gap-2"><x-filament::icon icon="%s" class="h-4 w-4 text-primary-500" />%s</span>',
                            $icon,
                            e($state)
                        ));
                    })
                    ->html()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('unit.name')
                    ->label('Unidad')
                    ->sortable(),
                TextColumn::make('available_stock')
                    ->label('Disponible')
                    ->getStateUsing(fn (Material $record) => max($record->current_stock - $record->quantity_on_loan, 0))
                    ->numeric()
                    ->sortable(),
            ])
            ->filters([])
            ->headerActions([
                Action::make('createOrder')
                    ->label('Crear pedido de materiales')
                    ->icon('heroicon-o-plus-circle')
                    ->color('primary')
                    ->button()
                    ->modalWidth('2xl')
                    ->form([
                        Repeater::make('items')
                            ->label('Materiales a solicitar')
                            ->minItems(1)
                            ->maxItems(10)
                            ->addActionLabel('Agregar material')
                            ->columns(2)
                            ->schema([
                                Select::make('material_id')
                                    ->label('Material')
                                    ->prefixIcon('heroicon-o-cube')
                                    ->options(fn () => Material::orderBy('name')->pluck('name', 'id'))
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->columnSpan(1),
                                TextInput::make('quantity')
                                    ->label('Cantidad')
                                    ->prefixIcon('heroicon-o-hashtag')
                                    ->numeric()
                                    ->minValue(1)
                                    ->required()
                                    ->helperText(function (Forms\Get $get) {
                                        $materialId = $get('material_id');

                                        if (! $materialId) {
                                            return 'Selecciona un material para ver el stock disponible.';
                                        }

                                        $material = Material::find($materialId);

                                        if (! $material) {
                                            return 'El material ya no está disponible.';
                                        }

                                        $available = max($material->current_stock - $material->quantity_on_loan, 0);

                                        return "Stock disponible: {$available} unidad(es).";
                                    })
                                    ->rule(function (Forms\Get $get) {
                                        return function (string $attribute, $value, \Closure $fail) use ($get) {
                                            $materialId = $get('material_id');

                                            if (! $materialId) {
                                                return;
                                            }

                                            $material = Material::find($materialId);

                                            if (! $material) {
                                                $fail('El material seleccionado ya no existe.');

                                                return;
                                            }

                        

                                            $available = max($material->current_stock - $material->quantity_on_loan, 0);

                                            if ($available <= 0) {
                                                $fail("Actualmente no hay stock disponible de {$material->name}.");

                                                return;
                                            }

                                            if ((int) $value > $available) {
                                                $fail("Solo hay {$available} unidad(es) disponibles para {$material->name}.");
                                            }
                                        };
                                    })
                                    ->columnSpan(1),
                            ])
                            ->columnSpanFull(),
                        DateTimePicker::make('needed_at')
                            ->label('Fecha de retiro deseada')
                            ->prefixIcon('heroicon-o-calendar')
                            ->required()
                            ->minDate(now())
                            ->seconds(false)
                            ->native(false),
                        DateTimePicker::make('planned_return_at')
                            ->label('Fecha de devolución estimada')
                            ->prefixIcon('heroicon-o-clock')
                            ->required()
                            ->seconds(false)
                            ->native(false),
                        Textarea::make('notes')
                            ->label('Notas opcionales')
                            ->placeholder('Cuéntanos brevemente para qué necesitas estos materiales.')
                            ->helperText('Incluye detalles del proyecto o uso previsto para agilizar la autorización.')
                            ->rows(3)
                            ->columnSpanFull()
                            ->extraAttributes(['class' => 'pl-3 border-l-4 border-primary-200 bg-primary-50/30']),
                        Forms\Components\Placeholder::make('notes_hint')
                            ->content('Consejo: describe con claridad el uso del material para acelerar la aprobación.')
                            ->columnSpanFull(),
                    ])
                    ->modalSubmitActionLabel('Enviar solicitud')
                    ->action(function (array $data) {
                        $user = Auth::user();

                        if (! $user) {
                            throw ValidationException::withMessages([
                                'items' => 'Debes iniciar sesión para solicitar materiales.',
                            ]);
                        }

                        Notification::make()
                            ->title('Enviando notificaciones...')
                            ->body('Estamos avisando al laboratorio para que revise tu solicitud.')
                            ->icon('heroicon-o-bell-alert')
                            ->info()
                            ->send();

                        $items = collect($data['items'] ?? [])
                            ->filter(fn ($item) => ! empty($item['material_id']) && ! empty($item['quantity']));

                        if ($items->isEmpty()) {
                            throw ValidationException::withMessages([
                                'items' => 'Agrega al menos un material con su cantidad.',
                            ]);
                        }

                        if ($data['planned_return_at'] <= $data['needed_at']) {
                            throw ValidationException::withMessages([
                                'planned_return_at' => 'La devolución debe ser posterior a la fecha requerida.',
                            ]);
                        }

                        $loan = Loan::create([
                            'user_id' => $user->id,
                            'issued_by' => null,
                            'loan_code' => 'L-' . strtoupper(Str::random(8)),
                            'loan_at' => $data['needed_at'],
                            'due_at' => $data['planned_return_at'],
                            'status' => 'pendiente',
                            'notes' => $data['notes'] ?? null,
                        ]);

                        $pivotData = [];
                        foreach ($items as $index => $item) {
                            $material = Material::find($item['material_id']);

                            if (! $material) {
                                throw ValidationException::withMessages([
                                    "items.{$index}.material_id" => 'El material seleccionado ya no está disponible.',
                                ]);
                            }

                            $available = max($material->current_stock - $material->quantity_on_loan, 0);

                            if ($available <= 0) {
                                throw ValidationException::withMessages([
                                    "items.{$index}.quantity" => "Actualmente no hay stock disponible de {$material->name}.",
                                ]);
                            }

                            $quantity = (int) $item['quantity'];

                            if ($quantity > $available) {
                                throw ValidationException::withMessages([
                                    "items.{$index}.quantity" => "Solo hay {$available} unidad(es) disponibles para {$material->name}.",
                                ]);
                            }

                            $pivotData[$material->id] = [
                                'loan_qty' => $quantity,
                                'returned_qty' => 0,
                            ];
                        }

                        $loan->materials()->sync($pivotData);

                        $recipients = User::role(['superadmin', 'aux_admin'])->get();

                        if ($recipients->isNotEmpty()) {
                            NotificationFacade::send(
                                $recipients,
                                new NewLoanRequestNotification($loan->load('materials', 'borrower'))
                            );
                        }
                    })
                    ->successNotification(fn () => Notification::make()
                        ->title('¡Pedido enviado!')
                        ->body('Te avisaremos cuando el laboratorio revise tu solicitud.')
                        ->success()),
            ])
            ->actions([])
            ->bulkActions([])
            ->recordAction(null)
            ->emptyStateHeading('No hay materiales registrados')
            ->emptyStateDescription('Cuando el laboratorio publique materiales podrás solicitarlos aquí.');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\BrowseMaterials::route('/'),
        ];
    }

}
