<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

/**
 * Normalised filter and sort input for the loadout browse page.
 *
 * Accepts every parameter the page has ever used (including the legacy
 * multi-value arrays and column-sortable `sort`/`direction` pairs) and
 * turns it into one canonical set of filters for LoadoutFilter.
 */
class LoadoutBrowseQuery
{
    public const DEFAULT_SORT = 'updated';

    /**
     * @var array<string, string>
     */
    public const SORTS = [
        'salutes' => 'Top salutes',
        'newest' => 'Newest',
        'updated' => 'Recently updated',
    ];

    /**
     * Column-sortable `sort` values from the old table headers, mapped to the new sort options.
     *
     * @var array<string, string>
     */
    private const LEGACY_SORTS = [
        'votes_sum_value' => 'salutes',
        'created_at' => 'newest',
        'updated_at' => 'updated',
    ];

    /**
     * @var list<string>
     */
    public const ID_LIST_FILTERS = ['characters', 'primaries', 'secondaries', 'overclocks', 'mods', 'throwables'];

    /**
     * @param  array{characters?: list<int>, primaries?: list<int>, secondaries?: list<int>, overclocks?: list<int>, mods?: list<int>, throwables?: list<int>, search?: string, hasOverclock?: string, hasGuide?: string, isCurrentPatch?: int, favorites?: int}  $filters
     */
    public function __construct(public array $filters, public string $sort = self::DEFAULT_SORT)
    {
    }

    public static function fromRequest(Request $request, int $defaultPatchScope): self
    {
        $filters = [];

        foreach (self::ID_LIST_FILTERS as $key) {
            $values = Arr::wrap($request->query($key));

            // The original filter also accepted a single `character` id.
            if ($key === 'characters' && $request->filled('character')) {
                $values[] = $request->query('character');
            }

            $ids = collect($values)
                ->filter(fn ($value) => is_scalar($value) && ctype_digit((string) $value))
                ->map(fn ($value) => (int) $value)
                ->unique()
                ->values()
                ->all();

            if ($ids !== []) {
                $filters[$key] = $ids;
            }
        }

        $search = $request->query('search');
        if (is_string($search) && trim($search) !== '') {
            $filters['search'] = trim($search);
        }

        foreach (['hasOverclock', 'hasGuide'] as $key) {
            if (in_array($request->query($key), ['Yes', 'No'], true)) {
                $filters[$key] = $request->query($key);
            }
        }

        $patchScope = $request->query('isCurrentPatch');
        if (in_array($patchScope, ['0', '1'], true) && (int) $patchScope !== $defaultPatchScope) {
            $filters['isCurrentPatch'] = (int) $patchScope;
        }

        if ($request->filled('favorites') && $request->query('favorites') !== '0') {
            $filters['favorites'] = 1;
        }

        return new self($filters, self::resolveSort($request));
    }

    private static function resolveSort(Request $request): string
    {
        $sort = $request->query('sort');

        if (is_string($sort) && array_key_exists($sort, self::SORTS)) {
            return $sort;
        }

        if (is_string($sort) && array_key_exists($sort, self::LEGACY_SORTS) && $request->query('direction') !== 'asc') {
            return self::LEGACY_SORTS[$sort];
        }

        return self::DEFAULT_SORT;
    }

    public function applySort(Builder $query): Builder
    {
        $column = match ($this->sort) {
            'salutes' => 'votes_sum_value',
            'newest' => 'loadouts.created_at',
            default => 'loadouts.updated_at',
        };

        return $query->orderByDesc($column)->orderByDesc('loadouts.id');
    }

    /**
     * The classes the page is scoped to. The UI picks one, but legacy URLs may carry several.
     *
     * @return list<int>
     */
    public function classIds(): array
    {
        return $this->filters['characters'] ?? [];
    }

    /**
     * @return list<int>
     */
    public function ids(string $key): array
    {
        return $this->filters[$key] ?? [];
    }

    public function has(string $key, mixed $value = null): bool
    {
        if (! array_key_exists($key, $this->filters)) {
            return false;
        }

        if ($value === null) {
            return true;
        }

        return is_array($this->filters[$key])
            ? in_array($value, $this->filters[$key], true)
            : $this->filters[$key] === $value;
    }

    public function isFiltered(): bool
    {
        return $this->filters !== [];
    }

    /**
     * Canonical query-string parameters, optionally without one filter value.
     *
     * @return array<string, mixed>
     */
    public function query(?string $withoutKey = null, mixed $withoutValue = null): array
    {
        $query = $this->filters;

        if ($withoutKey !== null) {
            if ($withoutValue !== null && is_array($query[$withoutKey] ?? null)) {
                $query[$withoutKey] = array_values(array_diff($query[$withoutKey], [$withoutValue]));
            } else {
                unset($query[$withoutKey]);
            }

            if (($query[$withoutKey] ?? null) === []) {
                unset($query[$withoutKey]);
            }
        }

        if ($this->sort !== self::DEFAULT_SORT) {
            $query['sort'] = $this->sort;
        }

        return $query;
    }

    public function urlWithout(string $key, mixed $value = null): string
    {
        return route('loadout.index', $this->query($key, $value));
    }

    public function clearUrl(): string
    {
        return route('loadout.index', $this->sort === self::DEFAULT_SORT ? [] : ['sort' => $this->sort]);
    }
}
