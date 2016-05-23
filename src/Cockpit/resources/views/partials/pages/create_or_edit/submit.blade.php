@if($module_page->isType('create'))
    {!! cockpit_form()->submitCreate($model_reflection) !!}
@else

    @if(!empty($module_page->frontendUrl))
        <a href="{{ url($module_page->frontendUrl) }}" class="btn btn-link btn-xs" target="_blank"><i class="fa fa-eye"></i> {{ trans('mezzo.general.show') }}</a>
    @endif

    {!! cockpit_form()->submitEdit($model_reflection) !!}
@endif