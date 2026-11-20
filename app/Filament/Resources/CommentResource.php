<?php

namespace App\Filament\Resources;

use App\Comment;
use App\Comments\Report;
use App\Filament\Resources\CommentResource\Pages\ManageComment;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use UnitEnum;

class CommentResource extends AdminResource
{
    protected static ?string $model = Comment::class;

    protected static string|UnitEnum|null $navigationGroup = 'Community';

    protected static string $permission = 'manage-comments';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChatBubbleLeftRight;

    /**
     * @var array<string, string>
     */
    protected const STATUS_LABELS = [
        Comment::STATUS_APPROVED => 'Approved',
        Comment::STATUS_PENDING => 'Pending',
        Comment::STATUS_SPAM => 'Spam',
        Comment::STATUS_TRASH => 'Trash',
    ];

    public static function canCreate(): bool
    {
        return false;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->with(['user', 'parent.user'])->withCount('reports'))
            ->columns([
                TextColumn::make('author_name')
                    ->label('Author')
                    ->weight('bold')
                    ->description(fn (Comment $record): string => $record->user_id ? 'Member' : 'Guest · '.$record->author_email)
                    ->searchable(query: fn (Builder $query, string $search): Builder => $query->where(
                        fn (Builder $query) => $query->where('author_name', 'like', "%{$search}%")
                            ->orWhere('author_email', 'like', "%{$search}%")
                            ->orWhereHas('user', fn (Builder $query) => $query->where('name', 'like', "%{$search}%")),
                    )),
                TextColumn::make('content')
                    ->label('Comment')
                    ->wrap()
                    ->limit(200)
                    ->description(fn (Comment $record): ?string => $record->parent ? 'In reply to '.$record->parent->author_name : null, position: 'above')
                    ->searchable(),
                TextColumn::make('page_id')
                    ->label('Page')
                    ->formatStateUsing(fn (string $state): string => Str::replaceStart('loadout-', 'Loadout #', $state))
                    ->url(fn (Comment $record): ?string => static::publicUrl($record), shouldOpenInNewTab: true)
                    ->color('primary')
                    ->searchable(),
                TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => static::STATUS_LABELS[$state] ?? $state)
                    ->color(fn (string $state): string => match ($state) {
                        Comment::STATUS_APPROVED => 'success',
                        Comment::STATUS_PENDING => 'warning',
                        Comment::STATUS_SPAM => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('reports_count')
                    ->label('Reports')
                    ->numeric()
                    ->color(fn (int $state): string => $state > 0 ? 'danger' : 'gray')
                    ->sortable(),
                TextColumn::make('upvotes')
                    ->label('Votes')
                    ->formatStateUsing(fn (Comment $record): string => "+{$record->upvotes} / −{$record->downvotes}")
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Posted')
                    ->since()
                    ->dateTimeTooltip()
                    ->sortable(),
            ])
            ->filters([
                Filter::make('reported')
                    ->query(fn (Builder $query): Builder => $query->has('reports')),
            ])
            ->recordActions([
                Action::make('approve')
                    ->icon(Heroicon::OutlinedCheck)
                    ->color('success')
                    ->visible(fn (Comment $record): bool => ! $record->approved() || $record->reports_count > 0)
                    ->action(fn (Comment $record) => static::updateStatus([$record->id], Comment::STATUS_APPROVED)),
                Action::make('hold')
                    ->label('Unapprove')
                    ->icon(Heroicon::OutlinedPause)
                    ->color('warning')
                    ->visible(fn (Comment $record): bool => $record->approved())
                    ->action(fn (Comment $record) => $record->update(['status' => Comment::STATUS_PENDING])),
                ActionGroup::make([
                    Action::make('view')
                        ->label('View on site')
                        ->icon(Heroicon::OutlinedArrowTopRightOnSquare)
                        ->url(fn (Comment $record): ?string => static::publicUrl($record), shouldOpenInNewTab: true),
                    EditAction::make()
                        ->using(function (Comment $record, array $data): Comment {
                            return DB::transaction(function () use ($record, $data): Comment {
                                $record->update($data);

                                if ($record->wasChanged('status') && $record->approved()) {
                                    $record->reports()->delete();
                                }

                                return $record;
                            });
                        }),
                    Action::make('spam')
                        ->label('Mark as spam')
                        ->icon(Heroicon::OutlinedShieldExclamation)
                        ->visible(fn (Comment $record): bool => ! $record->spam())
                        ->action(fn (Comment $record) => $record->update(['status' => Comment::STATUS_SPAM])),
                    Action::make('trash')
                        ->label('Move to trash')
                        ->icon(Heroicon::OutlinedArchiveBox)
                        ->visible(fn (Comment $record): bool => $record->status !== Comment::STATUS_TRASH)
                        ->action(fn (Comment $record) => $record->update(['status' => Comment::STATUS_TRASH])),
                    DeleteAction::make(),
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    static::statusBulkAction(Comment::STATUS_APPROVED, 'Approve', Heroicon::OutlinedCheck),
                    static::statusBulkAction(Comment::STATUS_PENDING, 'Unapprove', Heroicon::OutlinedPause),
                    static::statusBulkAction(Comment::STATUS_SPAM, 'Mark as spam', Heroicon::OutlinedShieldExclamation),
                    static::statusBulkAction(Comment::STATUS_TRASH, 'Move to trash', Heroicon::OutlinedArchiveBox),
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    protected static function fields(): array
    {
        return [
            Textarea::make('content')
                ->required()
                ->minLength(2)
                ->rows(6)
                ->columnSpanFull(),
            Select::make('status')
                ->options(static::STATUS_LABELS)
                ->required(),
            TextInput::make('author_name')
                ->maxLength(100)
                ->required()
                ->visible(fn (?Comment $record): bool => $record && ! $record->user_id),
            TextInput::make('author_email')
                ->email()
                ->maxLength(200)
                ->required()
                ->visible(fn (?Comment $record): bool => $record && ! $record->user_id),
            TextInput::make('author_url')
                ->url()
                ->maxLength(200)
                ->visible(fn (?Comment $record): bool => $record && ! $record->user_id),
        ];
    }

    public static function getPages(): array
    {
        return ['index' => ManageComment::route('/')];
    }

    /**
     * Link to the comment on the loadout page it was posted on.
     */
    protected static function publicUrl(Comment $record): ?string
    {
        if (! preg_match('/^loadout-(\d+)$/', (string) $record->page_id, $matches)) {
            return $record->permalink ? $record->url : null;
        }

        return route('preview.show', $matches[1]).'#!comment='.$record->getKey();
    }

    protected static function statusBulkAction(string $status, string $label, Heroicon $icon): BulkAction
    {
        return BulkAction::make($status)
            ->label($label)
            ->icon($icon)
            ->action(fn (Collection $records) => static::updateStatus($records->modelKeys(), $status))
            ->deselectRecordsAfterCompletion();
    }

    /**
     * @param  list<int>  $commentIds
     */
    protected static function updateStatus(array $commentIds, string $status): void
    {
        DB::transaction(function () use ($commentIds, $status): void {
            Comment::query()->whereKey($commentIds)->update(['status' => $status]);

            if ($status === Comment::STATUS_APPROVED) {
                Report::query()->whereIn('comment_id', $commentIds)->delete();
            }
        });
    }
}
