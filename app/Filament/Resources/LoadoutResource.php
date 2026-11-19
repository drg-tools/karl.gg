<?php

namespace App\Filament\Resources;

use App\Loadout;
use App\User;
use Filament\Facades\Filament;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
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
