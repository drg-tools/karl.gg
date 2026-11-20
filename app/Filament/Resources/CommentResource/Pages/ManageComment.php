<?php

namespace App\Filament\Resources\CommentResource\Pages;

use App\Comment;
use App\Filament\Resources\CommentResource;
use Filament\Resources\Pages\ManageRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ManageComment extends ManageRecords
{
    protected static string $resource = CommentResource::class;

    /**
     * @return array<string, Tab>
     */
    public function getTabs(): array
    {
        $counts = Comment::query()->toBase()
            ->selectRaw('status, count(*) as aggregate')
            ->groupBy('status')
            ->pluck('aggregate', 'status');

        return [
            'all' => Tab::make('All')
                ->badge($counts->only([Comment::STATUS_APPROVED, Comment::STATUS_PENDING])->sum())
                ->modifyQueryUsing(fn (Builder $query) => $query->whereIn('status', [Comment::STATUS_APPROVED, Comment::STATUS_PENDING])),
            Comment::STATUS_PENDING => Tab::make('Pending')
                ->badge($counts->get(Comment::STATUS_PENDING, 0))
                ->badgeColor('warning')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', Comment::STATUS_PENDING)),
            Comment::STATUS_APPROVED => Tab::make('Approved')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', Comment::STATUS_APPROVED)),
            Comment::STATUS_SPAM => Tab::make('Spam')
                ->badge($counts->get(Comment::STATUS_SPAM, 0))
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', Comment::STATUS_SPAM)),
            Comment::STATUS_TRASH => Tab::make('Trash')
                ->badge($counts->get(Comment::STATUS_TRASH, 0))
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', Comment::STATUS_TRASH)),
        ];
    }
}
