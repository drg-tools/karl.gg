<?php

namespace App\Filament\Resources;

use Filament\Forms\Components\{DateTimePicker, Hidden, RichEditor, Select, Textarea, TextInput, Toggle};
use Illuminate\Support\Facades\Hash;

class UserResource extends AdminResource
{
    protected static ?string $model = \App\User::class;
    protected static string $permission = 'manage-users';
    protected static array $listColumns = ['name', 'email'];
    protected static array $relationshipFilters = [];

    protected static function fields(): array
    {
        return [
            TextInput::make('name')->maxLength(255)->required(),
            TextInput::make('email')->email()->required()->maxLength(255)->unique(ignoreRecord: true),
            TextInput::make('password')->password()->autocomplete('new-password')->dehydrateStateUsing(fn ($state) => Hash::make($state))->dehydrated(fn ($state) => filled($state))->required(fn (string $operation) => $operation === 'create'),
            Select::make('roles')->relationship('roles', 'name', modifyQueryUsing: fn ($query) => $query->where('guard_name', 'backpack'))->searchable()->preload()->multiple(),
            Select::make('permissions')->relationship('permissions', 'name', modifyQueryUsing: fn ($query) => $query->where('guard_name', 'backpack'))->searchable()->preload()->multiple(),
            Toggle::make('can_email'),
        ];
    }

    public static function getPages(): array
    {
        return ['index' => UserResource\Pages\ManageUser::route('/')];
    }
}
