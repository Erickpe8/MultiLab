<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ComputerResource\Pages;
use App\Models\Computer;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Validation\Rule;

class ComputerResource extends Resource
{
    protected static ?string $model = Computer::class;

    protected static ?string $navigationIcon = 'heroicon-o-computer-desktop';

    protected static ?string $navigationGroup = 'Laboratorio';

    protected static ?string $modelLabel = 'Computador';

    protected static ?string $pluralModelLabel = 'Computadores';

    public static function canViewAny(): bool
    {
        return \App\Helpers\RoleHelper::canManageInventory();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Datos Principales')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nombre o Etiqueta')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                        Forms\Components\TextInput::make('serial_number')
                            ->label('Número de Serie')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        Forms\Components\TextInput::make('marca')
                            ->label('Marca')
                            ->maxLength(120)
                            ->nullable(),
                    ])->columns(2),
                Forms\Components\Section::make('Estado y Notas')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->label('Estado')
                            ->options(static::statusOptions())
                            ->required()
                            ->rules([Rule::in(array_keys(static::statusOptions()))])
                            ->native(false),
                        Forms\Components\Textarea::make('notes')
                            ->label('Notas')
                            ->maxLength(1000)
                            ->columnSpanFull(),
                    ]),
                Forms\Components\Section::make('Componentes')
                    ->schema([
                        Forms\Components\TextInput::make('main_card')
                            ->label('Tarjeta Principal')
                            ->maxLength(150)
                            ->nullable(),
                        Forms\Components\TextInput::make('processor')
                            ->label('Procesador')
                            ->maxLength(150)
                            ->nullable(),
                        Forms\Components\TextInput::make('ram')
                            ->label('RAM')
                            ->maxLength(100)
                            ->nullable(),
                        Forms\Components\TextInput::make('hard_drive')
                            ->label('Disco Duro')
                            ->maxLength(150)
                            ->nullable(),
                        Forms\Components\TextInput::make('network_card')
                            ->label('Tarjeta de Red')
                            ->maxLength(150)
                            ->nullable(),
                        Forms\Components\TextInput::make('graphics_card')
                            ->label('Tarjeta Gráfica')
                            ->maxLength(150)
                            ->nullable(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nombre o Etiqueta')
                    ->searchable(),
                Tables\Columns\TextColumn::make('serial_number')
                    ->label('Número de Serie')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->colors([
                        'success' => 'disponible',
                        'danger' => 'no_disponible',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'disponible' => 'Disponible',
                        'no_disponible' => 'No Disponible',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('marca')
                    ->label('Marca')
                    ->searchable(),
                Tables\Columns\TextColumn::make('main_card')
                    ->label('Tarjeta Principal')
                    ->searchable(),
                Tables\Columns\TextColumn::make('processor')
                    ->label('Procesador')
                    ->searchable(),
                Tables\Columns\TextColumn::make('ram')
                    ->label('RAM')
                    ->searchable(),
                Tables\Columns\TextColumn::make('hard_drive')
                    ->label('Disco Duro')
                    ->searchable(),
                Tables\Columns\TextColumn::make('network_card')
                    ->label('Tarjeta de Red')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('graphics_card')
                    ->label('Tarjeta Gráfica')
                    ->searchable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options(static::statusOptions())
                    ->label('Estado')
                    ->native(false),
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
            'index' => Pages\ListComputers::route('/'),
            'create' => Pages\CreateComputer::route('/create'),
            'edit' => Pages\EditComputer::route('/{record}/edit'),
        ];
    }
}
