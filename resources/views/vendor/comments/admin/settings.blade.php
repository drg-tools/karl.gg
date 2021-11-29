@extends('comments::admin._layout')

@section('content')
<settings inline-template>
  <div class="card">
    <div class="card-body">
      <h4 class="card-title mb-4 pb-2">
        @lang('comments::admin.settings_title')
        <button @click="reset" type="button" class="btn btn-outline-danger btn-sm float-end">
          @lang('comments::admin.settings_reset_button')
        </button>
      </h4>

      <ul class="nav nav-tabs mb-3">
        @foreach (array_keys($tabs) as $tab)
          <li class="nav-item">
            <a href="#{{ $tab }}" :class="{ active: tab === '{{ $tab }}' }" class="nav-link"
              @click="changeTab('{{ $tab }}')">
              {{ trans('comments::admin.settings_'.$tab) }}
            </a>
          </li>
        @endforeach
      </ul>

      <form @submit.prevent="save" class="col-md-7 p-0">
        <alert-success :form="form" message="@lang('comments::admin.settings_saved')"></alert-success>

        <div class="tab-content">
          @foreach ($tabs as $tab => $inputs)
            <div class="tab-pane" :class="{ active: tab === '{{ $tab }}' }" role="tabpanel">
              @foreach ($inputs as $input)
                {{ $input->render() }}
              @endforeach
            </div>
          @endforeach
        </div>

        <div class="mb-3">
          <button type="submit" class="btn btn-success" :disabled="form.busy">
            @lang('comments::admin.settings_save_button')
          </button>
        </div>

        @if (app()->configurationIsCached())
          <div class="alert alert-danger">
            @lang('comments::admin.setting_config_cached', [
              'command' => '<br><code>php artisan config:cache</code>'
            ])
          </div>
        @endif
      </form>
    </div>
  </div>
</settings>
@endsection
