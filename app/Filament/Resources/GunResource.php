<?php

namespace App\Filament\Resources;

use Filament\Forms\Components\{DateTimePicker, Hidden, RichEditor, Select, Textarea, TextInput, Toggle};
use Illuminate\Support\Facades\Hash;

class GunResource extends AdminResource
{
    protected static ?string $model = \App\Gun::class;
    protected static string $permission = 'manage-stats';
    protected static array $listColumns = ['name', 'character.name', 'gun_class'];
    protected static array $relationshipFilters = ['character' => 'name'];

    protected static function fields(): array
    {
        return [
            TextInput::make('name')->maxLength(255)->required(),
            TextInput::make('image')->maxLength(255),
            TextInput::make('gun_class')->maxLength(255)->required(),
            TextInput::make('character_slot')->numeric()->integer()->minValue(1)->required(),
            Select::make('character_id')->relationship('character', 'name')->searchable()->preload()->required(),
            Textarea::make('json_stats')->default('{}')->rules(['json'])->required()->columnSpanFull(),
        ];
    }

    public static function getPages(): array
    {
        return ['index' => GunResource\Pages\ManageGun::route('/')];
    }
}
