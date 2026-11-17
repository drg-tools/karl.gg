<?php

namespace App\Filament\Resources;

use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Spatie\Permission\Models\Permission;

class PermissionResource extends AdminResource
{
    protected static ?string $model = Permission::class;

    protected static string $permission = 'manage-users';

    protected static array $listColumns = ['name', 'guard_name'];

    protected static array $relationshipFilters = [];

    protected static function fields(): array
    {
        return [
            TextInput::make('name')->maxLength(255)->required(),
            Hidden::make('guard_name')->default('backpack'),
        ];
    }

    public static function getPages(): array
    {
        return ['index' => PermissionResource\Pages\ManagePermission::route('/')];
    }
}
