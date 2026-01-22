<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MaterialCatalogResource\Pages;
use App\Helpers\RoleHelper;
use App\Models\Material;
use App\Models\MaterialRequest;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
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
        return RoleHelper::isEstudiante();
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
                    ->searchable()
                    ->sortable(),
                TextColumn::make('unit.name')
                    ->label('Unidad')
                    ->sortable(),
                TextColumn::make('current_stock')
                    ->label('Stock actual')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('quantity_on_loan')
                    ->label('Prestado')
                    ->numeric()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('available_stock')
                    ->label('Disponible')
                    ->getStateUsing(fn (Material $record) => max($record->current_stock - $record->quantity_on_loan, 0))
                    ->numeric()
                    ->sortable(),
            ])
            ->filters([])
            ->actions([
                Action::make('requestMaterial')
                    ->label('Solicitar')
                    ->icon('heroicon-o-paper-airplane')
                    ->color('primary')
                    ->modalWidth('lg')
                    ->visible(fn (Material $record) => $record->current_stock > 0)
                    ->form([
                        TextInput::make('quantity')
                            ->label('Cantidad requerida')
                            ->numeric()
                            ->minValue(1)
                            ->required()
                            ->helperText('Debe ser menor o igual al stock disponible.')
                            ->columnSpanFull(),
                        DateTimePicker::make('needed_at')
                            ->label('Fecha de retiro deseada')
                            ->required()
                            ->minDate(now())
                            ->seconds(false)
                            ->native(false),
                        DateTimePicker::make('planned_return_at')
                            ->label('Fecha de devolución estimada')
                            ->required()
                            ->seconds(false)
                            ->native(false),
                        Textarea::make('notes')
                            ->label('Notas opcionales')
                            ->placeholder('Cuéntanos brevemente para qué necesitas este material.')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->modalSubmitActionLabel('Enviar solicitud')
                    ->action(function (Material $record, array $data) {
                        $user = Auth::user();

                        if (!$user) {
                            throw ValidationException::withMessages([
                                'quantity' => 'Debes iniciar sesión para solicitar materiales.',
                            ]);
                        }

                        $available = max($record->current_stock - $record->quantity_on_loan, 0);

                        if ($available <= 0) {
                            throw ValidationException::withMessages([
                                'quantity' => 'Actualmente no hay stock disponible de este material.',
                            ]);
                        }

                        if ($data['quantity'] > $available) {
                            throw ValidationException::withMessages([
                                'quantity' => "Solo hay {$available} unidad(es) disponible(s) para préstamo.",
                            ]);
                        }

                        if ($data['planned_return_at'] <= $data['needed_at']) {
                            throw ValidationException::withMessages([
                                'planned_return_at' => 'La devolución debe ser posterior a la fecha requerida.',
                            ]);
                        }

                        MaterialRequest::create([
                            'material_id' => $record->id,
                            'user_id' => $user->id,
                            'quantity' => $data['quantity'],
                            'needed_at' => $data['needed_at'],
                            'planned_return_at' => $data['planned_return_at'],
                            'status' => 'pendiente',
                            'notes' => $data['notes'] ?? null,
                        ]);
                    })
                    ->successNotificationTitle('Solicitud enviada'),
            ])
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
