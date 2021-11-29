<div class="mb-3">
  <label for="_{{ $key }}">{{ $label }}</label>
  <textarea name="{{ $key }}" id="_{{ $key }}" class="form-control" rows="3">{!! $value !!}</textarea>
  @if (isset($help))
  <small class="form-text text-muted">{!! $help !!}</small>
  @endif
</div>
