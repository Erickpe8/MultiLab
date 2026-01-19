<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AssetResource\Pages;
use App\Filament\Resources\AssetResource\RelationManagers;
use App\Models\Asset;
use App\Models\DeviceModel;
use App\Models\Location;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AssetResource extends AppResource
{
    protected static ?string $model = Asset::class;

    protected static ?string $navigationIcon = 'heroicon-o-computer-desktop';
    protected static ?string $navigationGroup = 'Warehouse Management';
    protected static ?string $modelLabel = 'Asset';
    protected static ?string $pluralModelLabel = 'Assets';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Asset Details')
                    ->schema([
                        TextInput::make('asset_tag')
                            ->label('Asset Tag')
                            ->unique(ignoreRecord: true)
                            ->required()
                            ->maxLength(255),
                        TextInput::make('serial')
                            ->label('Serial Number')
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        Select::make('model_id')
                            ->label('Device Model')
                            ->relationship('model', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Select::make('location_id')
                            ->label('Location')
                            ->relationship('location', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Select::make('status')
                            ->label('Status')
                            ->options([
                                'available' => 'Available',
                                'in_use' => 'In Use',
                                'under_maintenance' => 'Under Maintenance',
                                'disposed' => 'Disposed',
                                'loaned' => 'Loaned',
                                'reserved' => 'Reserved',
                            ])
                            ->required()
                            ->native(false),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Purchase & Warranty')
                    ->schema([
                        DatePicker::make('purchase_date')
                            ->label('Purchase Date'),
                        DatePicker::make('warranty_until')
                            ->label('Warranty Until'),
                        TextInput::make('qr_text')
                            ->label('QR Text')
                            ->maxLength(255),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Additional Information')
                    ->schema([
                        Forms\Components\Textarea::make('notes')
                            ->label('Notes')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('asset_tag')
                    ->label('Asset Tag')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('serial')
                    ->label('Serial Number')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('model.name')
                    ->label('Device Model')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('location.name')
                    ->label('Location')
                    ->sortable()
                    ->searchable(),
                BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'success' => 'available',
                        'primary' => ['in_use', 'loaned', 'reserved'],
                        'warning' => 'under_maintenance',
                        'danger' => 'disposed',
                    ])
                    ->sortable(),
                TextColumn::make('purchase_date')
                    ->label('Purchase Date')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('warranty_until')
                    ->label('Warranty Until')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Updated At')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('model')
                    ->label('Device Model')
                    ->relationship('model', 'name'),
                Tables\Filters\SelectFilter::make('location')
                    ->label('Location')
                    ->relationship('location', 'name'),
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'available' => 'Available',
                        'in_use' => 'In Use',
                        'under_maintenance' => 'Under Maintenance',
                        'disposed' => 'Disposed',
                        'loaned' => 'Loaned',
                        'reserved' => 'Reserved',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ManageAssets::route('/'),
        ];
    }
}

