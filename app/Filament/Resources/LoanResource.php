<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LoanResource\Pages;
use App\Filament\Resources\LoanResource\RelationManagers;
use App\Models\Loan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use App\Models\Asset;
use App\Models\Material;
use App\Models\User;
use Filament\Facades\Filament;
use Filament\Forms\Components\Hidden;
use Illuminate\Support\Str; // Added this line // Added this use statement

class LoanResource extends AppResource
{
    protected static ?string $model = Loan::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Bodega';
    protected static ?string $modelLabel = 'Préstamo de Bodega';
    protected static ?string $pluralModelLabel = 'Préstamos de Bodega';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Información del Préstamo')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->relationship('borrower', 'first_name')
                            ->label('Solicitante')
                            ->getOptionLabelFromRecordUsing(fn (User $record) => $record->name)
                            ->searchable(['first_name', 'middle_name', 'first_surname', 'second_surname', 'email'])
                            ->preload()
                            ->required(),
                        Forms\Components\Select::make('issued_by')
                            ->relationship('issuer', 'first_name')
                            ->label('Entregado por')
                            ->getOptionLabelFromRecordUsing(fn (User $record) => $record->name)
                            ->searchable(['first_name', 'middle_name', 'first_surname', 'second_surname', 'email'])
                            ->preload()
                            ->required(),
                        Forms\Components\TextInput::make('loan_code')
                            ->required()
                            ->maxLength(32),
                        Forms\Components\DateTimePicker::make('loan_at')
                            ->required(),
                        Forms\Components\DateTimePicker::make('due_at')
                            ->required(),
                        Forms\Components\DateTimePicker::make('return_at'),
                        Forms\Components\TextInput::make('status')
                            ->required(),
                        Forms\Components\Textarea::make('notes')
                            ->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Section::make('Items Prestados')
                    ->schema([
                        Forms\Components\Repeater::make('materials')
                            ->relationship()
                            ->label('Materiales')
                            ->schema([
                                Forms\Components\Select::make('material_id')
                                    ->label('Material')
                                    ->options(Material::query()->pluck('name', 'id'))
                                    ->searchable()
                                    ->required(),
                                Forms\Components\TextInput::make('loan_qty')
                                    ->label('Cantidad Prestada')
                                    ->numeric()
                                    ->required(),
                                Forms\Components\TextInput::make('returned_qty')
                                    ->label('Cantidad Devuelta')
                                    ->numeric()
                                    ->default(0),
                            ])
                            ->columns(3),

                        Forms\Components\Repeater::make('assets')
                            ->relationship()
                            ->label('Activos Únicos')
                            ->schema([
                                Forms\Components\Select::make('asset_id')
                                    ->label('Activo')
                                    ->options(Asset::query()->pluck('asset_tag', 'id'))
                                    ->getOptionLabelFromRecordUsing(fn ($value) => Asset::find($value)?->asset_tag . ' (' . Asset::find($value)?->model?->name . ')')
                                    ->searchable()
                                    ->required(),
                                Forms\Components\TextInput::make('condition_out')
                                    ->label('Condición de Salida'),
                                Forms\Components\TextInput::make('condition_in')
                                    ->label('Condición de Entrada'),
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
                    ->searchable(),
                Tables\Columns\TextColumn::make('loan_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('due_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('return_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('return')
                    ->label('Return Loan')
                    ->icon('heroicon-o-arrow-left-on-rectangle')
                    ->form([
                        Forms\Components\Section::make('Items to Return')
                            ->schema([
                                Forms\Components\Repeater::make('returned_materials')
                                    ->label('Materials to Return')
                                    ->schema([
                                        Forms\Components\Select::make('material_id')
                                            ->label('Material')
                                            ->options(Material::query()->pluck('name', 'id'))
                                            ->disabled()
                                            ->dehydrated(false),
                                        Forms\Components\TextInput::make('loan_qty')
                                            ->label('Loaned Quantity')
                                            ->numeric()
                                            ->disabled()
                                            ->dehydrated(false),
                                        Forms\Components\TextInput::make('returned_qty')
                                            ->label('Quantity Returned')
                                            ->numeric()
                                            ->minValue(0)
                                            ->maxValue(fn (Forms\Get $get) => $get('loan_qty'))
                                            ->default(fn (Forms\Get $get) => $get('returned_qty_from_pivot') ?? 0)
                                            ->required(),
                                        Forms\Components\Hidden::make('pivot_id')
                                            ->dehydrated(true),
                                        Forms\Components\Hidden::make('returned_qty_from_pivot')
                                            ->dehydrated(false),
                                    ])
                                    ->columns(3)
                                    ->default(function (Loan $record) {
                                        return $record->materials->map(function ($material) {
                                            return [
                                                'material_id' => $material->id,
                                                'loan_qty' => $material->pivot->loan_qty,
                                                'returned_qty' => $material->pivot->returned_qty,
                                                'returned_qty_from_pivot' => $material->pivot->returned_qty,
                                                'pivot_id' => $material->pivot->id,
                                            ];
                                        })->toArray();
                                    })
                                    ->dehydrated(true)
                                    ->columnSpanFull(),

                                Forms\Components\Repeater::make('returned_assets')
                                    ->label('Assets to Return')
                                    ->schema([
                                        Forms\Components\Select::make('asset_id')
                                            ->label('Asset')
                                            ->options(Asset::query()->pluck('asset_tag', 'id'))
                                            ->disabled()
                                            ->dehydrated(false),
                                        Forms\Components\TextInput::make('condition_out')
                                            ->label('Condition Out')
                                            ->disabled()
                                            ->dehydrated(false),
                                        Forms\Components\TextInput::make('condition_in')
                                            ->label('Condition In')
                                            ->required(),
                                        Forms\Components\Hidden::make('pivot_id')
                                            ->dehydrated(true),
                                    ])
                                    ->columns(3)
                                    ->default(function (Loan $record) {
                                        return $record->assets->map(function ($asset) {
                                            return [
                                                'asset_id' => $asset->id,
                                                'condition_out' => $asset->pivot->condition_out,
                                                'condition_in' => $asset->pivot->condition_in,
                                                'pivot_id' => $asset->pivot->id,
                                            ];
                                        })->toArray();
                                    })
                                    ->dehydrated(true)
                                    ->columnSpanFull(),
                            ]),
                        Forms\Components\Textarea::make('return_notes')
                            ->label('Return Notes')
                            ->columnSpanFull(),
                    ])
                    ->action(function (Loan $record, array $data) {
                        $allReturned = true;

                        // Process returned materials
                        foreach ($data['returned_materials'] as $materialData) {
                            $pivot = $record->materials()->wherePivot('id', $materialData['pivot_id'])->first()->pivot;
                            $oldReturnedQty = $pivot->returned_qty;
                            $newReturnedQty = $materialData['returned_qty'];

                            if ($newReturnedQty > $oldReturnedQty) {
                                $returnedDiff = $newReturnedQty - $oldReturnedQty;
                                $pivot->update(['returned_qty' => $newReturnedQty]);
                                Material::find($materialData['material_id'])->increment('current_stock', $returnedDiff);
                            }

                            if ($newReturnedQty < $pivot->loan_qty) {
                                $allReturned = false;
                            }
                        }

                        // Process returned assets
                        foreach ($data['returned_assets'] as $assetData) {
                            $pivot = $record->assets()->wherePivot('id', $assetData['pivot_id'])->first()->pivot;
                            $pivot->update(['condition_in' => $assetData['condition_in']]);
                            if (empty($assetData['condition_in'])) { // If condition_in is empty, assume not fully returned
                                $allReturned = false;
                            }
                            // Optionally, update asset status here if the asset is fully returned
                        }

                        // Update loan status
                        $status = 'partially_returned';
                        if ($allReturned) {
                            $status = 'returned';
                        } elseif ($record->status === 'returned') { // If it was fully returned and now isn't due to some asset not having condition_in
                            $status = 'partially_returned';
                        }


                        $record->update([
                            'status' => $status,
                            'return_at' => ($allReturned && $record->return_at === null) ? now() : $record->return_at,
                            'notes' => $record->notes . "\nReturn Notes (" . now()->format('Y-m-d H:i') . "): " . $data['return_notes'],
                        ]);

                        Filament::notify('success', 'Loan updated successfully.');
                    })
                    ->visible(fn (Loan $record): bool => $record->status !== 'returned' && $record->status !== 'cancelled'),
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
