<div class="mb-3">
  <label for="_{{ $key }}">{{ $label }}</label>
  <input type="text" name="{{ $key }}" id="_{{ $key }}" class="form-control" value="{{ $value }}" autocomplete="off">
  @if (isset($help))
  <small class="form-text text-muted">{!! $help !!}</small>
  @endif
</div>
