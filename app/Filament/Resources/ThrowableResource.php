<?php

namespace App\Filament\Resources;

use App\Models\Throwable;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use UnitEnum;

class ThrowableResource extends AdminResource
{
    protected static ?string $model = Throwable::class;

    protected static string|UnitEnum|null $navigationGroup = 'Game Data';

    protected static string $permission = 'manage-stats';

    protected static array $listColumns = ['name', 'character.name'];

    protected static array $relationshipFilters = ['character' => 'name'];

    protected static function fields(): array
    {
        return [
            TextInput::make('name')->maxLength(255)->required(),
            Select::make('character_id')->relationship('character', 'name')->searchable()->preload()->required(),
            Textarea::make('description')->columnSpanFull(),
            TextInput::make('icon')->maxLength(255)->required(),
            TextInput::make('credits_cost')->numeric()->integer()->minValue(0)->default(0)->required(),
            TextInput::make('magnite_cost')->numeric()->integer()->minValue(0)->default(0)->required(),
            TextInput::make('bismor_cost')->numeric()->integer()->minValue(0)->default(0)->required(),
            TextInput::make('umanite_cost')->numeric()->integer()->minValue(0)->default(0)->required(),
            TextInput::make('croppa_cost')->numeric()->integer()->minValue(0)->default(0)->required(),
            TextInput::make('enor_pearl_cost')->numeric()->integer()->minValue(0)->default(0)->required(),
            TextInput::make('jadiz_cost')->numeric()->integer()->minValue(0)->default(0)->required(),
            Textarea::make('json_stats')->default('{}')->rules(['json'])->required()->columnSpanFull(),
        ];
    }

    public static function getPages(): array
    {
        return ['index' => ThrowableResource\Pages\ManageThrowable::route('/')];
    }
}
