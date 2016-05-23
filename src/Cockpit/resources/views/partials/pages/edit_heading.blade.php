<h3>@lang('mezzo.general.editing') {{ $model_reflection->title() }}: @{{ vm.modelId }}</h3>
<div class="panel-actions">
    @yield('main_panel.actions')

    @if($module_page->sibling('index'))
        <a class="highlight" href="{{ $module_page->sibling('index')->url() }}"><i
                    class="ion-arrow-return-left"></i></a>
    @endif
</div>