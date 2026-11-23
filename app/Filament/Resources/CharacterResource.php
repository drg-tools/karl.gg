<?php

namespace App\Filament\Resources;

use App\Models\Character;
use Filament\Forms\Components\TextInput;
use UnitEnum;

class CharacterResource extends AdminResource
{
    protected static ?string $model = Character::class;

    protected static string|UnitEnum|null $navigationGroup = 'Game Data';

    protected static string $permission = 'manage-stats';

    protected static array $listColumns = ['name'];

    protected static array $relationshipFilters = [];

    protected static function fields(): array
    {
        return [
            TextInput::make('name')->maxLength(255)->required(),
            TextInput::make('image')->maxLength(255),
        ];
    }

    public static function getPages(): array
    {
        return ['index' => CharacterResource\Pages\ManageCharacter::route('/')];
    }
}
