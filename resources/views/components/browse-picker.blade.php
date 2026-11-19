@props([
    'name',
    'label',
    'groups',
    'selected' => [],
    'classIds' => [],
    'requiresClass' => false,
    'prompt' => null,
])

{{--
    A searchable checkbox list. Native checkboxes keep it keyboard-usable and
    submittable without JavaScript; browse.js adds the search box and re-scopes
    the options when the class changes. Options outside the selected class are
    hidden unless they are already checked.
--}}
@php
    $isInScope = fn (int $classId) => $requiresClass
        ? in_array($classId, $classIds, true)
        : ($classIds === [] || in_array($classId, $classIds, true));
    $showPrompt = $requiresClass && $classIds === [];
@endphp

<details class="browse-picker" data-picker data-requires-class="{{ $requiresClass ? 'true' : 'false' }}" @if ($selected !== []) open @endif>
    <summary class="browse-picker__summary">
        <span>{{ $label }}</span>
        <span class="browse-picker__count" data-picker-count @if ($selected === []) hidden @endif>
            {{ count($selected) }}<span class="sr-only"> selected</span>
        </span>
    </summary>
    <div class="browse-picker__body">
        <label class="browse-picker__search" data-picker-search hidden>
            <span class="sr-only">Search {{ Str::lower($label) }} options</span>
            <input type="search" placeholder="Search {{ Str::lower($label) }}…" autocomplete="off">
        </label>
        @if ($prompt)
            <p class="browse-picker__note" data-class-prompt @if (! $showPrompt) hidden @endif>{{ $prompt }}</p>
        @endif
        <div class="browse-picker__options">
            @foreach ($groups as $group)
                @php
                    $groupInScope = $isInScope($group['classId']);
                    $groupVisible = $groupInScope || collect($group['options'])->contains(fn ($option) => in_array($option['id'], $selected, true));
                @endphp
                <fieldset @class(['browse-picker__group', 'is-out-of-scope' => ! $groupVisible]) data-class="{{ $group['classId'] }}" style="--class-accent: {{ $group['accent'] }}">
                    <legend>{{ $group['label'] }}</legend>
                    @foreach ($group['options'] as $option)
                        @php $isChecked = in_array($option['id'], $selected, true); @endphp
                        <label @class(['browse-option', 'is-out-of-scope' => ! $groupInScope && ! $isChecked]) data-option="{{ Str::lower($option['name'].' '.$group['label'].' '.$option['meta']) }}">
                            <input type="checkbox" name="{{ $name }}[]" value="{{ $option['id'] }}" @checked($isChecked)>
                            <span class="browse-option__name">{{ $option['name'] }}</span>
                            @if ($option['meta'])
                                <span class="browse-option__meta">{{ $option['meta'] }}</span>
                            @endif
                        </label>
                    @endforeach
                </fieldset>
            @endforeach
            <p class="browse-picker__note" data-picker-empty hidden>No matches.</p>
        </div>
    </div>
</details>
