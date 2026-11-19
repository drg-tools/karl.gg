<?php

namespace App\Filament\Resources;

use App\Loadout;
use App\User;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Facades\Filament;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Auth\Access\Response;
use Illuminate\Database\Eloquent\Model;
use UnitEnum;

class LoadoutResource extends AdminResource
{
    protected static ?string $model = Loadout::class;

    protected static array $listColumns = ['name', 'creator.name', 'character.name'];

    protected static array $relationshipFilters = ['character' => 'name', 'creator' => 'name'];

    public static function canViewAny(): bool
    {
        $user = auth('backpack')->user();

        return $user instanceof User && $user->canAccessPanel(Filament::getPanel('admin'));
    }

    public static function getAuthorizationResponse(string|UnitEnum $action, ?Model $record = null): Response
    {
        return match ($action) {
            'update', 'delete' => static::canViewAny() ? Response::allow() : Response::deny(),
            default => parent::getAuthorizationResponse($action, $record),
        };
    }

    public static function table(Table $table): Table
    {
        return parent::table($table)->recordActions([
            EditAction::make(),
            DeleteAction::make(),
            Action::make('hide')
                ->label('Hide')
                ->icon(Heroicon::OutlinedEyeSlash)
                ->authorize(fn (Loadout $record): Response => static::getEditAuthorizationResponse($record))
                ->visible(fn (Loadout $record): bool => ! $record->is_hidden)
                ->action(fn (Loadout $record) => $record->setHiddenFromLists(true)),
            Action::make('restoreListing')
                ->label('Show in lists')
                ->authorize(fn (Loadout $record): Response => static::getEditAuthorizationResponse($record))
                ->visible(fn (Loadout $record): bool => $record->is_hidden)
                ->action(fn (Loadout $record) => $record->setHiddenFromLists(false)),
        ]);
    }

    protected static function filters(): array
    {
        return [
            ...parent::filters(),
            TernaryFilter::make('is_hidden')
                ->label('List visibility')
                ->trueLabel('Hidden from lists')
                ->falseLabel('Shown in lists'),
        ];
    }

    protected static function fields(): array
    {
        return [
            TextInput::make('name')->maxLength(255)->required(),
            Textarea::make('description')->columnSpanFull(),
            Select::make('user_id')->relationship('creator', 'name')->searchable(),
            Select::make('character_id')->relationship('character', 'name')->searchable()->preload()->required(),
            Select::make('mods')->relationship('mods', 'mod_name')->searchable()->preload()->multiple(),
            Select::make('overclocks')->relationship('overclocks', 'overclock_name')->searchable()->preload()->multiple(),
            Select::make('equipment_mods')->relationship('equipment_mods', 'mod_name')->searchable()->preload()->multiple(),
            Select::make('throwable_id')->relationship('throwable', 'name')->searchable()->preload(),
        ];
    }

    public static function getPages(): array
    {
        return ['index' => LoadoutResource\Pages\ManageLoadout::route('/')];
    }
}
