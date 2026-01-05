@if (! empty($flexibleModules))
  @foreach ($flexibleModules as $module)
    @if (! empty($module['acf_fc_layout']))
      @includeIf('modules.' . $module['acf_fc_layout'], ['module' => $module])
    @endif
  @endforeach
@endif
