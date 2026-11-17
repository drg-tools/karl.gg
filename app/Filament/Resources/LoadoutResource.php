<?php

namespace App\Filament\Resources;

use App\Loadout;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;

class LoadoutResource extends AdminResource
{
    protected static ?string $model = Loadout::class;

    protected static string $permission = 'manage-stats';

    protected static array $listColumns = ['name', 'creator.name', 'character.name'];

    protected static array $relationshipFilters = ['character' => 'name', 'creator' => 'name'];

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
