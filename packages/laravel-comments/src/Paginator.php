<?php

namespace Hazzard\Comments;

use Illuminate\Pagination\LengthAwarePaginator;

class Paginator extends LengthAwarePaginator
{
    /**
     * @var int
     */
    protected $count;

    /**
     * Get the next page.
     *
     * @return int|null
     */
    public function nextPage()
    {
        if ($this->lastPage() > $this->currentPage()) {
            return $this->currentPage() + 1;
        }
    }

    /**
     * Get the previous page.
     *
     * @return int|null
     */
    public function previousPage()
    {
        if ($this->currentPage() > 1) {
            return $this->currentPage() - 1;
        }
    }

    /**
     * Get the first and last adjacent pages.
     *
     * @return array
     */
    public function getAdjacentPages()
    {
        $count = 1;

        if ($this->currentPage() <= $count + $count) {
            $first = 1;
            $last = min(1 + $count + $count, $this->lastPage());
        } elseif ($this->currentPage() > $this->lastPage() - $count - $count) {
            $last = $this->lastPage();
            $first = $this->lastPage() - $count - $count;
        } else {
            $first = $this->currentPage() - $count;
            $last = $this->currentPage() + $count;
        }

        return [$first, $last];
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        list($firstAdjacentPage, $lastAdjacentPage) = $this->getAdjacentPages();

        $data = [
            'comments' => $this->items->toArray(),
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

        if (isset($this->count)) {
            $data['total'] = $this->count;
        }

        if (isset($this->pageCount)) {
            $data['page_count'] = $this->pageCount;
        }

        if (isset($this->commentableCount)) {
            $data['commentable_count'] = $this->commentableCount;
        }

        if (isset($this->statusCount)) {
            $data['status_count'] = $this->statusCount;
        }

        return $data;
    }
}
