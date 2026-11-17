<?php

namespace App\Filament\Resources;

use Filament\Forms\Components\{DateTimePicker, Hidden, RichEditor, Select, Textarea, TextInput, Toggle};
use Illuminate\Support\Facades\Hash;

class EquipmentResource extends AdminResource
{
    protected static ?string $model = \App\Equipment::class;
    protected static string $permission = 'manage-stats';
    protected static array $listColumns = ['name', 'character.name', 'eq_type'];
    protected static array $relationshipFilters = ['character' => 'name'];

    protected static function fields(): array
    {
        return [
            TextInput::make('name')->maxLength(255)->required(),
            Select::make('character_id')->relationship('character', 'name')->searchable()->preload()->required(),
            TextInput::make('icon')->maxLength(255)->required(),
            TextInput::make('eq_type')->maxLength(255)->required(),
            Textarea::make('json_stats')->default('{}')->rules(['json'])->required()->columnSpanFull(),
        ];
    }

    public static function getPages(): array
    {
        return ['index' => EquipmentResource\Pages\ManageEquipment::route('/')];
    }
}
