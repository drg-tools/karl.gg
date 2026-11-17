<?php

namespace App\Filament\Resources;

use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Spatie\Permission\Models\Role;

class RoleResource extends AdminResource
{
    protected static ?string $model = Role::class;

    protected static string $permission = 'manage-users';

    protected static array $listColumns = ['name', 'guard_name'];

    protected static array $relationshipFilters = [];

    protected static function fields(): array
    {
        return [
            TextInput::make('name')->maxLength(255)->required(),
            Select::make('permissions')->relationship('permissions', 'name', modifyQueryUsing: fn ($query) => $query->where('guard_name', 'backpack'))->searchable()->preload()->multiple(),
            Hidden::make('guard_name')->default('backpack'),
        ];
    }

    public static function getPages(): array
    {
        return ['index' => RoleResource\Pages\ManageRole::route('/')];
    }
}
