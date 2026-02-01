<?php

namespace App\Filament\Resources\ClassroomLoanResource\RelationManagers;

use App\Models\ClassroomObservation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ObservationsRelationManager extends RelationManager
{
    protected static string $relationship = 'observations';

    protected static ?string $recordTitleAttribute = 'type';

    protected static ?string $title = 'Observaciones';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Hidden::make('recorded_by')
                    ->default(fn () => auth()->id())
                    ->required(),
                Forms\Components\Select::make('type')
                    ->label('Tipo')
                    ->options([
                        'inicio' => 'Inicio de sesión',
                        'durante' => 'Durante sesión',
                        'cierre' => 'Cierre',
                        'incidente' => 'Incidente',
                        'reporte' => 'Reporte general',
                    ])
                    ->required()
                    ->native(false),
                Forms\Components\Select::make('severity')
                    ->label('Severidad')
                    ->options([
                        1 => 'Baja',
                        2 => 'Media-baja',
                        3 => 'Media',
                        4 => 'Alta',
                        5 => 'Crítica',
                    ])
                    ->default(1)
                    ->required()
                    ->native(false),
                Forms\Components\Textarea::make('description')
                    ->label('Descripción')
                    ->rows(4)
                    ->required(),
                Forms\Components\KeyValue::make('metadata')
                    ->label('Datos')
                    ->keyLabel('Descripción')
                    ->valueLabel('Valor')
                    ->columnSpanFull(),
            ])
            ->columns(2);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\BadgeColumn::make('type')
                    ->label('Tipo')
                    ->colors([
                        'info' => ['inicio', 'durante', 'cierre', 'reporte'],
                        'danger' => 'incidente',
                    ])
                    ->icons([
                        'heroicon-o-play' => 'inicio',
                        'heroicon-o-pause' => 'durante',
                        'heroicon-o-stop' => 'cierre',
                        'heroicon-o-exclamation-triangle' => 'incidente',
                        'heroicon-o-document-text' => 'reporte',
                    ])
                    ->formatStateUsing(fn ($state) => ucfirst(str_replace('_', ' ', $state))),
                Tables\Columns\BadgeColumn::make('severity')
                    ->label('Severidad')
                    ->colors([
                        'gray' => 1,
                        'warning' => [2, 3],
                        'danger' => [4, 5],
                    ]),
                Tables\Columns\TextColumn::make('author.name')
                    ->label('Registrado por')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('description')
                    ->label('Descripción')
                    ->limit(60)
                    ->wrap(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Crear Nueva observación')
                    ->modalHeading('Crear Nueva Observación'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
