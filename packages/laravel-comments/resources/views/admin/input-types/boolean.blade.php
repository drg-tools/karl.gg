<div class="mb-3">
  <label for="_{{ $key }}">{{ $label }}</label>
  <select name="{{ $key }}" id="_{{ $key }}" class="form-select">
    <option value="1" {{ $selected ? 'selected' : '' }}>
      @lang('comments::admin.setting_yes')
    </option>
    <option value="0" {{ !$selected ? 'selected' : '' }}>
      @lang('comments::admin.setting_no')
    </option>
  </select>
  @if (isset($help))
  <small class="form-text text-muted">{!! $help !!}</small>
  @endif
</div>
