<?php

namespace App\Filament\Resources;

use Filament\Forms\Components\{DateTimePicker, Hidden, RichEditor, Select, Textarea, TextInput, Toggle};
use Illuminate\Support\Facades\Hash;

class PermissionResource extends AdminResource
{
    protected static ?string $model = \Spatie\Permission\Models\Permission::class;
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
