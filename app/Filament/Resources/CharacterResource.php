<?php

namespace App\Filament\Resources;

use Filament\Forms\Components\{DateTimePicker, Hidden, RichEditor, Select, Textarea, TextInput, Toggle};
use Illuminate\Support\Facades\Hash;

class CharacterResource extends AdminResource
{
    protected static ?string $model = \App\Character::class;
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
