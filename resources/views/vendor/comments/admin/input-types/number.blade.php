<div class="mb-3">
  <label for="_{{ $key }}">{{ $label }}</label>
  <input type="number" name="{{ $key }}" id="_{{ $key }}" class="form-control" value="{{ config("comments.$key") }}" min="0" autocomplete="off">
  @if (isset($help))
  <small class="form-text text-muted">{!! $help !!}</small>
  @endif
</div>
