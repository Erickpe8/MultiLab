<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MaterialRequestResource\Pages;
use App\Helpers\RoleHelper;
use App\Models\MaterialRequest;
use Filament\Forms;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class MaterialRequestResource extends AppResource
{
    protected static ?string $model = MaterialRequest::class;

    protected static ?string $navigationGroup = 'Solicitudes de estudiantes';
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationLabel = 'Solicitudes de materiales';
    protected static ?int $navigationSort = 2;

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form;
    }

    public static function shouldRegisterNavigation(): bool
    {
        return RoleHelper::isEstudiante();
    }

    public static function canViewAny(): bool
    {
        return RoleHelper::hasAnyRole(['superadmin', 'aux_admin', 'estudiante']);
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return false;
    }

    public static function canDelete(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return false;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                if (RoleHelper::isEstudiante()) {
                    $query->where('user_id', Auth::id());
                }
            })
            ->columns([
                TextColumn::make('material.name')
                    ->label('Material')
                    ->description(fn (MaterialRequest $record) => $record->material?->sku)
                    ->sortable()
                    ->searchable(),
                TextColumn::make('requester.name')
                    ->label('Solicitante')
                    ->sortable()
                    ->visible(fn () => ! RoleHelper::isEstudiante())
                    ->description(fn (MaterialRequest $record) => $record->requester?->email),
                TextColumn::make('quantity')
                    ->label('Cantidad')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('needed_at')
                    ->label('Retiro')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                TextColumn::make('planned_return_at')
                    ->label('Devolución')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                BadgeColumn::make('status')
                    ->label('Estado')
                    ->colors([
                        'warning' => 'pendiente',
                        'success' => 'aprobada',
                        'danger' => 'rechazada',
                    ])
                    ->formatStateUsing(fn (string $state) => ucfirst($state))
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Creada')
                    ->since()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([])
            ->bulkActions([]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Detalles de la solicitud')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('material.name')
                            ->label('Material'),
                        TextEntry::make('material.sku')
                            ->label('Código'),
                        TextEntry::make('quantity')
                            ->label('Cantidad solicitada'),
                        TextEntry::make('needed_at')
                            ->label('Fecha de retiro deseada')
                            ->dateTime('d/m/Y H:i'),
                        TextEntry::make('planned_return_at')
                            ->label('Fecha estimada de devolución')
                            ->dateTime('d/m/Y H:i'),
                        TextEntry::make('status')
                            ->label('Estado')
                            ->formatStateUsing(fn (string $state) => ucfirst($state)),
                        TextEntry::make('notes')
                            ->label('Notas del solicitante')
                            ->columnSpanFull(),
                    ]),
                Section::make('Solicitante')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('requester.name')
                            ->label('Nombre'),
                        TextEntry::make('requester.email')
                            ->label('Correo'),
                        TextEntry::make('created_at')
                            ->label('Creada')
                            ->dateTime('d/m/Y H:i'),
                    ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMaterialRequests::route('/'),
            'view' => Pages\ViewMaterialRequest::route('/{record}'),
        ];
    }
}
