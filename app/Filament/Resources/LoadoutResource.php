<?php

namespace App\Filament\Resources;

use App\Loadout;
use App\Patch;
use App\User;
use Carbon\CarbonImmutable;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Facades\Filament;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Support\Facades\FilamentTimezone;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\Indicator;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Auth\Access\Response;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use UnitEnum;

class LoadoutResource extends AdminResource
{
    protected static ?string $model = Loadout::class;

    protected static string|UnitEnum|null $navigationGroup = 'Community';

    protected static array $relationshipFilters = ['character' => 'name', 'creator' => 'name'];

    public static function canViewAny(): bool
    {
        $user = auth('backpack')->user();

        return $user instanceof User && $user->canAccessPanel(Filament::getPanel('admin'));
    }

    public static function getAuthorizationResponse(string|UnitEnum $action, ?Model $record = null): Response
    {
        return match ($action) {
            'update', 'delete' => static::canViewAny() ? Response::allow() : Response::deny(),
            default => parent::getAuthorizationResponse($action, $record),
        };
    }

    public static function table(Table $table): Table
    {
        return parent::table($table)->columns([
            TextColumn::make('id')->label('ID')->sortable()->toggleable(),
            TextColumn::make('name')->searchable()->sortable()->limit(80),
            TextColumn::make('creator.name')->label('Creator')->searchable()->sortable()->toggleable(),
            TextColumn::make('character.name')->label('Class')->searchable()->sortable()->toggleable(),
            TextColumn::make('patch.label')->label('Patch')->toggleable(isToggledHiddenByDefault: true),
            TextColumn::make('throwable.name')->label('Throwable')->toggleable(isToggledHiddenByDefault: true),
            TextColumn::make('description')->limit(80)->wrap()->toggleable(isToggledHiddenByDefault: true),
            IconColumn::make('is_hidden')->label('Hidden')->boolean()->sortable()->toggleable(isToggledHiddenByDefault: true),
            TextColumn::make('favorites_count')->label('Favorites')->counts('favorites')->numeric()->sortable()->toggleable(isToggledHiddenByDefault: true),
            TextColumn::make('created_at')->label('Created')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            TextColumn::make('updated_at')->label('Updated')->dateTime()->sortable()->toggleable(),
        ])->filtersFormColumns(2)->recordActions([
            Action::make('viewLoadout')
                ->label('View loadout')
                ->url(fn (Loadout $record): string => route('preview.show', $record->id))
                ->openUrlInNewTab(),
            EditAction::make(),
            DeleteAction::make(),
            Action::make('hide')
                ->label('Hide')
                ->icon(Heroicon::OutlinedEyeSlash)
                ->authorize(fn (Loadout $record): Response => static::getEditAuthorizationResponse($record))
                ->visible(fn (Loadout $record): bool => ! $record->is_hidden)
                ->action(fn (Loadout $record) => $record->setHiddenFromLists(true)),
            Action::make('restoreListing')
                ->label('Show in lists')
                ->authorize(fn (Loadout $record): Response => static::getEditAuthorizationResponse($record))
                ->visible(fn (Loadout $record): bool => $record->is_hidden)
                ->action(fn (Loadout $record) => $record->setHiddenFromLists(false)),
        ]);
    }

    protected static function filters(): array
    {
        return [
            ...parent::filters(),
            SelectFilter::make('patch')
                ->relationship('patch', 'patch_num', fn (Builder $query) => $query->orderByDesc('launched_at'))
                ->getOptionLabelFromRecordUsing(fn (Patch $record): string => $record->label)
                ->searchable()
                ->preload(),
            SelectFilter::make('throwable')->relationship('throwable', 'name')->preload(),
            TernaryFilter::make('is_hidden')
                ->label('List visibility')
                ->trueLabel('Hidden from lists')
                ->falseLabel('Shown in lists'),
            TernaryFilter::make('has_description')
                ->label('Description')
                ->trueLabel('Has a description')
                ->falseLabel('No description')
                ->queries(
                    true: fn (Builder $query): Builder => $query->whereNotNull('description')->where('description', '!=', ''),
                    false: fn (Builder $query): Builder => $query->where(fn (Builder $query) => $query->whereNull('description')->orWhere('description', '')),
                ),
            static::dateRangeFilter('created_at', 'Created'),
            static::dateRangeFilter('updated_at', 'Updated'),
        ];
    }

    /**
     * Filters a timestamp column between two calendar days, read in the admin display timezone.
     */
    protected static function dateRangeFilter(string $column, string $label): Filter
    {
        $localDay = fn (string $date): CarbonImmutable => CarbonImmutable::parse($date, FilamentTimezone::get());

        return Filter::make($column)
            ->schema([
                DatePicker::make('from')->label("{$label} from"),
                DatePicker::make('until')->label("{$label} until"),
            ])
            ->columns(2)
            ->columnSpanFull()
            ->query(fn (Builder $query, array $data): Builder => $query
                ->when($data['from'] ?? null, fn (Builder $query, string $from) => $query->where($column, '>=', $localDay($from)->startOfDay()->utc()))
                ->when($data['until'] ?? null, fn (Builder $query, string $until) => $query->where($column, '<=', $localDay($until)->endOfDay()->utc())))
            ->indicateUsing(function (array $data) use ($label): array {
                $indicators = [];

                if ($data['from'] ?? null) {
                    $indicators[] = Indicator::make("{$label} from ".CarbonImmutable::parse($data['from'])->toFormattedDateString())->removeField('from');
                }

                if ($data['until'] ?? null) {
                    $indicators[] = Indicator::make("{$label} until ".CarbonImmutable::parse($data['until'])->toFormattedDateString())->removeField('until');
                }

                return $indicators;
            });
    }

    protected static function fields(): array
    {
        return [
            TextInput::make('name')->maxLength(255)->required(),
            Textarea::make('description')->columnSpanFull(),
            Select::make('user_id')->relationship('creator', 'name')->searchable(),
            Select::make('character_id')->relationship('character', 'name')->searchable()->preload()->required(),
            Select::make('mods')->relationship('mods', 'mod_name')->searchable()->preload()->multiple(),
            Select::make('overclocks')->relationship('overclocks', 'overclock_name')->searchable()->preload()->multiple(),
            Select::make('equipment_mods')->relationship('equipment_mods', 'mod_name')->searchable()->preload()->multiple(),
            Select::make('throwable_id')->relationship('throwable', 'name')->searchable()->preload(),
        ];
    }

    public static function getPages(): array
    {
        return ['index' => LoadoutResource\Pages\ManageLoadout::route('/')];
    }
}
