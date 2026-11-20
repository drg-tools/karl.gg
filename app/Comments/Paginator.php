<?php

namespace App\Comments;

use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Paginates top-level comments in the shape the comments Vue app expects.
 */
class Paginator extends LengthAwarePaginator
{
    /**
     * Every visible comment on the page, replies included.
     */
    protected int $count = 0;

    public function nextPage(): ?int
    {
        return $this->lastPage() > $this->currentPage() ? $this->currentPage() + 1 : null;
    }

    public function previousPage(): ?int
    {
        return $this->currentPage() > 1 ? $this->currentPage() - 1 : null;
    }

    /**
     * The page links shown either side of the current page.
     *
     * @return array{0: int, 1: int}
     */
    public function adjacentPages(): array
    {
        if ($this->currentPage() <= 2) {
            return [1, min(3, $this->lastPage())];
        }

        if ($this->currentPage() > $this->lastPage() - 2) {
            return [$this->lastPage() - 2, $this->lastPage()];
        }

        return [$this->currentPage() - 1, $this->currentPage() + 1];
    }

    /**
     * @return array{comments: array<int, mixed>, total: int, pagination: array<string, int|null>}
     */
    public function toArray(): array
    {
        [$firstAdjacentPage, $lastAdjacentPage] = $this->adjacentPages();

        return [
            'comments' => $this->items->toArray(),
            'total' => $this->count,
            'pagination' => [
                'total' => $this->total(),
                'per_page' => $this->perPage(),
                'current_page' => $this->currentPage(),
                'last_page' => $this->lastPage(),
                'next_page' => $this->nextPage(),
                'prev_page' => $this->previousPage(),
                'first_adjacent_page' => $firstAdjacentPage,
                'last_adjacent_page' => $lastAdjacentPage,
            ],
        ];
    }
}
