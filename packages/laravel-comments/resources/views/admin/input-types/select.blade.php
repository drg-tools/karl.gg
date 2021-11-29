<div class="mb-3">
  <label for="_{{ $key }}">{{ $label }}</label>
  <select name="{{ $key }}" id="_{{ $key }}" class="form-select">
    @foreach ($options as $value => $label)
    <option value="{{ $value }}" {{ $selected === $value ? 'selected' : '' }}>{{ $label }}</option>
    @endforeach
  </select>
  @if (isset($help))
  <small class="form-text text-muted">{!! $help !!}</small>
  @endif
</div>
