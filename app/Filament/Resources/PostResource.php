<?php

namespace App\Filament\Resources;

use App\Models\Post;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use UnitEnum;

class PostResource extends AdminResource
{
    protected static ?string $model = Post::class;

    protected static string|UnitEnum|null $navigationGroup = 'Community';

    protected static string $permission = 'manage-posts';

    protected static array $listColumns = ['title', 'user.name', 'published_at'];

    protected static array $relationshipFilters = ['user' => 'name'];

    protected static function fields(): array
    {
        return [
            TextInput::make('title')->maxLength(255)->required(),
            RichEditor::make('content')->required()->columnSpanFull(),
            DateTimePicker::make('published_at'),
            Select::make('user_id')->relationship('user', 'name')->searchable()->required(),
        ];
    }

    public static function getPages(): array
    {
        return ['index' => PostResource\Pages\ManagePost::route('/')];
    }
}
