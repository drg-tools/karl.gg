<?php

namespace App\Filament\Resources;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

abstract class AdminResource extends Resource
{
    protected static string $permission = 'manage-stats';

    protected static array $listColumns = ['name'];

    protected static array $relationshipFilters = [];

    public static function canViewAny(): bool
    {
        $user = auth('backpack')->user();

        return $user && $user->can('view-admin') && $user->can(static::$permission);
    }

    public static function canCreate(): bool
    {
        return static::canViewAny();
    }

    public static function canView(Model $record): bool
    {
        return static::canViewAny();
    }

    public static function canEdit(Model $record): bool
    {
        return static::canViewAny();
    }

    public static function canDelete(Model $record): bool
    {
        return static::canViewAny();
    }

    public static function canDeleteAny(): bool
    {
        return static::canViewAny();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components(static::fields())->columns(2);
    }

    abstract protected static function fields(): array;

    public static function table(Table $table): Table
    {
        $columns = [TextColumn::make('id')->sortable()];
        foreach (static::$listColumns as $name) {
            $columns[] = TextColumn::make($name)->searchable()->sortable()->limit(80);
        }
        $columns[] = TextColumn::make('updated_at')->dateTime()->sortable();

        return $table->columns($columns)
            ->filters(static::filters())
            ->recordActions([ViewAction::make(), EditAction::make(), DeleteAction::make()])
            ->defaultSort('id', 'desc');
    }

    protected static function filters(): array
    {
        $filters = [];
        foreach (static::$relationshipFilters as $relationship => $title) {
            $filters[] = \Filament\Tables\Filters\SelectFilter::make($relationship)
                ->relationship($relationship, $title)->searchable()->preload();
        }

        return $filters;
    }
}
