<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MaterialResource\Pages;
use App\Filament\Resources\MaterialResource\RelationManagers;
use App\Models\Category;
use App\Models\Material;
use App\Models\Unit;
use App\Support\CategoryIconResolver;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Validation\Rule;

class MaterialResource extends AppResource
{
    protected static ?string $model = Material::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube';
    protected static ?string $navigationGroup = 'Gestión de Inventario';
    protected static ?string $modelLabel = 'Material';
    protected static ?string $pluralModelLabel = 'Materiales';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Tabs::make('material_flow')
                ->columnSpanFull()
                ->tabs([
                    Tab::make('Identidad & Clasificación')
                        ->icon('heroicon-o-cube')
                        ->schema([
                            Forms\Components\Section::make('Ficha principal')
                                ->description('Define cómo se identificará este material en reportes y etiquetas internas.')
                                ->schema([
                                    Forms\Components\Grid::make(12)
                                        ->schema([
                                            TextInput::make('sku')
                                                ->label('Código SKU')
                                                ->prefixIcon('heroicon-o-identification')
                                                ->placeholder('Ej: CAB-00045')
                                                ->helperText('Usa un código consistente para búsquedas más rápidas.')
                                                ->unique(ignoreRecord: true)
                                                ->required()
                                                ->maxLength(255)
                                                ->columnSpan(4),
                                            TextInput::make('name')
                                                ->label('Nombre comercial')
                                                ->prefixIcon('heroicon-o-tag')
                                                ->placeholder('Cables HDMI 1.5m')
                                                ->helperText('Se mostrará en listados de préstamos e inventario.')
                                                ->required()
                                                ->maxLength(255)
                                                ->columnSpan(8),
                                            Select::make('category_id')
                                                ->label('Categoría')
                                                ->prefixIcon('heroicon-o-squares-2x2')
                                                ->relationship('category', 'name')
                                                ->searchable()
                                                ->preload()
                                                ->required()
                                                ->rules(['exists:categories,id'])
                                                ->helperText('Agrupa el material según su uso para facilitar filtros.')
                                                ->columnSpan(6),
                                            Select::make('unit_id')
                                                ->label('Unidad de medida')
                                                ->prefixIcon('heroicon-o-adjustments-horizontal')
                                                ->relationship('unit', 'name')
                                                ->searchable()
                                                ->preload()
                                                ->required()
                                                ->rules(['exists:units,id'])
                                                ->helperText('Define cómo se descuentan existencias (unidades, cajas, metros, etc.).')
                                                ->columnSpan(6),
                                        ]),
                                ]),
                        ]),
                    Tab::make('Stock & Operación')
                        ->icon('heroicon-o-chart-bar')
                        ->schema([
                            Forms\Components\Section::make('Parámetros de inventario')
                                ->description('Controla cuándo alertar por reposición y el saldo disponible actual.')
                                ->schema([
                                    Forms\Components\Grid::make(12)
                                        ->schema([
                                            TextInput::make('min_stock')
                                                ->label('Stock mínimo')
                                                ->prefixIcon('heroicon-o-arrow-trending-down')
                                                ->numeric()
                                                ->integer()
                                                ->default(0)
                                                ->minValue(0)
                                                ->helperText('Cuando el stock baje de este valor se considera en alerta.')
                                                ->columnSpan(4),
                                            TextInput::make('max_stock')
                                                ->label('Stock máximo')
                                                ->prefixIcon('heroicon-o-arrow-trending-up')
                                                ->numeric()
                                                ->integer()
                                                ->default(0)
                                                ->minValue(0)
                                                ->rule(fn (Forms\Get $get) => function (string $attribute, $value, \Closure $fail) use ($get) {
                                                    $minStock = (int) ($get('min_stock') ?? 0);

                                                    if ($value !== null && $value !== '' && (int) $value !== 0 && (int) $value < $minStock) {
                                                        $fail('El stock máximo debe ser mayor o igual al stock mínimo.');
                                                    }
                                                })
                                                ->helperText('Límite de seguridad para evitar sobreinventario.')
                                                ->columnSpan(4),
                                            TextInput::make('current_stock')
                                                ->label('Stock actual')
                                                ->prefixIcon('heroicon-o-archive-box')
                                                ->numeric()
                                                ->integer()
                                                ->default(0)
                                                ->minValue(0)
                                                ->helperText('Se ajusta automáticamente con préstamos y devoluciones.')
                                                ->columnSpan(4),
                                        ]),
                                    Forms\Components\Grid::make(12)
                                        ->schema([
                                            Forms\Components\Placeholder::make('stock_health')
                                                ->label('Estado de stock')
                                                ->content(function (Forms\Get $get) {
                                                    $current = (int) ($get('current_stock') ?? 0);
                                                    $min = (int) ($get('min_stock') ?? 0);
                                                    $max = (int) ($get('max_stock') ?? 0);

                                                    if ($current === 0) {
                                                        return 'Sin existencias. Reponer de inmediato.';
                                                    }

                                                    if ($min > 0 && $current <= $min) {
                                                        return 'En nivel crítico (≤ stock mínimo).';
                                                    }

                                                    if ($max > 0 && $current > $max) {
                                                        return 'Por encima del stock objetivo. Evalúa redistribuir.';
                                                    }

                                                    return 'Saldo saludable.';
                                                })
                                                ->columnSpan(6),
                                            Forms\Components\Placeholder::make('loan_overview')
                                                ->label('Reservas/Préstamos activos')
                                                ->content(function (Forms\Get $get, ?Material $record) {
                                                    if (!$record || !$record->exists) {
                                                        return 'Disponible al guardar el material.';
                                                    }

                                                    $onLoan = $record->quantity_on_loan ?? 0;

                                                    return $onLoan > 0
                                                        ? $onLoan . ' unidad' . ($onLoan === 1 ? '' : 'es') . ' actualmente prestada' . ($onLoan === 1 ? '' : 's') . '.'
                                                        : 'Sin préstamos pendientes.';
                                                })
                                                ->columnSpan(6),
                                        ]),
                                ]),
                        ]),

                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('sku')
                    ->label('Código SKU')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('category.name')
                    ->label('Categoría')
                    ->formatStateUsing(function (?string $state) {
                        $icon = CategoryIconResolver::resolve($state);

                        return new HtmlString(sprintf(
                            '<span class="flex items-center gap-2"><x-filament::icon icon="%s" class="h-4 w-4 text-primary-500" />%s</span>',
                            $icon,
                            e($state ?? 'Sin categoría')
                        ));
                    })
                    ->html()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('unit.name')
                    ->label('Unidad')
                    ->sortable(),
                TextColumn::make('current_stock')
                    ->label('Stock Actual')
                    ->numeric(),
                TextColumn::make('quantity_on_loan')
                    ->label('Cantidad Prestada')
                    ->numeric(),
                TextColumn::make('min_stock')
                    ->label('Stock Mín.')
                    ->numeric()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('max_stock')
                    ->label('Stock Máx.')
                    ->numeric()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->label('Categoría')
                    ->relationship('category', 'name'),
                Tables\Filters\SelectFilter::make('unit')
                    ->label('Unidad')
                    ->relationship('unit', 'name'),
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageMaterials::route('/'),
        ];
    }
}
