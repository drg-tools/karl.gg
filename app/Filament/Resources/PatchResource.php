<?php

namespace App\Filament\Resources;

use Filament\Forms\Components\{DateTimePicker, Hidden, RichEditor, Select, Textarea, TextInput, Toggle};
use Illuminate\Support\Facades\Hash;

class PatchResource extends AdminResource
{
    protected static ?string $model = \App\Patch::class;
    protected static string $permission = 'manage-stats';
    protected static array $listColumns = ['patch_title', 'patch_num', 'launched_at'];
    protected static array $relationshipFilters = [];

    protected static function fields(): array
    {
        return [
            TextInput::make('patch_title')->maxLength(100)->required(),
            TextInput::make('patch_num')->maxLength(10)->required(),
            TextInput::make('patch_num_dev')->maxLength(20)->required(),
            DateTimePicker::make('launched_at'),
        ];
    }

    public static function getPages(): array
    {
        return ['index' => PatchResource\Pages\ManagePatch::route('/')];
    }
}
