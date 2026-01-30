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
use Filament\Tables\Filters\SelectFilter;
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
        return RoleHelper::hasAnyRole(['estudiante', 'docente']);
    }

    public static function canViewAny(): bool
    {
        return RoleHelper::hasAnyRole(['superadmin', 'aux_admin', 'estudiante', 'docente']);
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
                    ->label('Material solicitado')
                    ->icon('heroicon-o-cube')
                    ->weight('semibold')
                    ->description(fn (MaterialRequest $record) => $record->material?->sku ? 'Código: ' . $record->material->sku : 'Sin código')
                    ->wrap()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('requester_preview')
                    ->label('Solicitante')
                    ->visible(fn () => ! RoleHelper::isEstudiante())
                    ->state(fn (MaterialRequest $record) => $record->requester?->name ?? '—')
                    ->description(fn (MaterialRequest $record) => $record->requester?->email)
                    ->icon('heroicon-o-user-circle')
                    ->wrap()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('quantity')
                    ->label('Cantidad')
                    ->badge()
                    ->color('primary')
                    ->alignCenter()
                    ->sortable(),
                TextColumn::make('agenda')
                    ->label('Agenda estimada')
                    ->icon('heroicon-o-calendar-days')
                    ->state(function (MaterialRequest $record) {
                        $pickup = $record->needed_at
                            ? sprintf('<span class="font-semibold">Retiro:</span> %s <span class="text-xs text-gray-500">(%s)</span>',
                                $record->needed_at->format('d/m/Y H:i'),
                                $record->needed_at->diffForHumans()
                            )
                            : '<span class="font-semibold">Retiro:</span> Sin definir';

                        $return = $record->planned_return_at
                            ? sprintf('<span class="font-semibold">Devolución:</span> %s <span class="text-xs text-gray-500">(%s)</span>',
                                $record->planned_return_at->format('d/m/Y H:i'),
                                $record->planned_return_at->diffForHumans()
                            )
                            : '<span class="font-semibold">Devolución:</span> Sin definir';

                        return '<div class="space-y-1">' . $pickup . '<br>' . $return . '</div>';
                    })
                    ->html()
                    ->wrap()
                    ->toggleable(isToggledHiddenByDefault: true),
                BadgeColumn::make('status')
                    ->label('Estado')
                    ->colors([
                        'warning' => 'pendiente',
                        'success' => 'aprobada',
                        'danger' => 'rechazada',
                    ])
                    ->icon(fn (string $state) => match ($state) {
                        'aprobada' => 'heroicon-o-check-circle',
                        'rechazada' => 'heroicon-o-x-circle',
                        default => 'heroicon-o-clock',
                    })
                    ->formatStateUsing(fn (string $state) => static::statusOptions()[$state] ?? ucfirst($state))
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Recibida')
                    ->since()
                    ->description(fn (MaterialRequest $record) => $record->created_at?->format('d/m/Y H:i'))
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->recordUrl(fn (MaterialRequest $record) => static::getUrl('view', ['record' => $record]))
            ->recordClasses(fn (MaterialRequest $record) => match ($record->status) {
                'aprobada' => 'border-l-4 border-emerald-400/90 dark:border-emerald-500/70',
                'rechazada' => 'border-l-4 border-rose-400/90 dark:border-rose-500/70',
                default => 'border-l-4 border-amber-300/80 dark:border-amber-500/70',
            })
            ->filters([
                SelectFilter::make('status')
                    ->label('Estado')
                    ->options(static::statusOptions())
                    ->indicator('Estado'),
                SelectFilter::make('material_id')
                    ->label('Material')
                    ->relationship('material', 'name')
                    ->searchable()
                    ->indicator('Material')
                    ->visible(fn () => ! RoleHelper::isEstudiante()),
            ])
            ->emptyStateHeading('Sin solicitudes registradas')
            ->emptyStateDescription('Cuando un estudiante solicite un material, la solicitud aparecerá aquí con toda la información necesaria.')
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Detalles')
                    ->icon('heroicon-o-magnifying-glass')
                    ->color('primary'),
            ])
            ->bulkActions([]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Resumen de la solicitud')
                    ->columns(3)
                    ->schema([
                        TextEntry::make('status')
                            ->label('Estado actual')
                            ->badge()
                            ->color(fn (string $state) => match ($state) {
                                'aprobada' => 'success',
                                'rechazada' => 'danger',
                                default => 'warning',
                            })
                            ->formatStateUsing(fn (string $state) => static::statusOptions()[$state] ?? ucfirst($state))
                            ->icon(fn (string $state) => match ($state) {
                                'aprobada' => 'heroicon-o-check-circle',
                                'rechazada' => 'heroicon-o-x-circle',
                                default => 'heroicon-o-clock',
                            }),
                        TextEntry::make('created_at')
                            ->label('Solicitud recibida')
                            ->dateTime('d/m/Y H:i')
                            ->helperText(fn (MaterialRequest $record) => $record->created_at?->diffForHumans()),
                        TextEntry::make('requester.name')
                            ->label('Solicitante')
                            ->icon('heroicon-o-user'),
                    ]),
                Section::make('Material y cantidades')
                    ->columns(3)
                    ->schema([
                        TextEntry::make('material.name')
                            ->label('Material solicitado')
                            ->icon('heroicon-o-cube'),
                        TextEntry::make('quantity')
                            ->label('Cantidad requerida')
                            ->badge()
                            ->color('primary')
                            ->alignCenter(),
                        TextEntry::make('material.current_stock')
                            ->label('Stock disponible')
                            ->placeholder('N/D')
                            ->helperText('En el momento de la consulta.'),
                    ]),
                Section::make('Agenda estimada')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('needed_at')
                            ->label('Retiro solicitado')
                            ->icon('heroicon-o-arrow-down-tray')
                            ->dateTime('d/m/Y H:i')
                            ->helperText(fn (MaterialRequest $record) => $record->needed_at?->diffForHumans()),
                        TextEntry::make('planned_return_at')
                            ->label('Devolución estimada')
                            ->icon('heroicon-o-arrow-up-tray')
                            ->dateTime('d/m/Y H:i')
                            ->helperText(fn (MaterialRequest $record) => $record->planned_return_at?->diffForHumans()),
                        TextEntry::make('notes')
                            ->label('Notas del solicitante')
                            ->columnSpanFull()
                            ->formatStateUsing(fn (?string $state) => $state ?: 'El solicitante no agregó comentarios adicionales.')
                            ->hintIcon('heroicon-o-chat-bubble-bottom-center-text'),
                    ])
                    ->collapsible(),
                Section::make('Datos del solicitante')
                    ->columns(3)
                    ->schema([
                        TextEntry::make('requester.email')
                            ->label('Correo de contacto')
                            ->copyable()
                            ->copyableState(fn ($state) => $state)
                            ->icon('heroicon-o-envelope'),
                        TextEntry::make('requester.phone')
                            ->label('Teléfono')
                            ->placeholder('Sin teléfono registrado'),
                        TextEntry::make('requester.created_at')
                            ->label('Registrado en plataforma')
                            ->dateTime('d/m/Y')
                            ->placeholder('N/D'),
                    ])
                    ->visible(fn (MaterialRequest $record) => ! RoleHelper::isEstudiante()),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMaterialRequests::route('/'),
            'view' => Pages\ViewMaterialRequest::route('/{record}'),
        ];
    }

    protected static function statusOptions(): array
    {
        return [
            'pendiente' => 'Pendiente',
            'aprobada' => 'Aprobada',
            'rechazada' => 'Rechazada',
        ];
    }
}
