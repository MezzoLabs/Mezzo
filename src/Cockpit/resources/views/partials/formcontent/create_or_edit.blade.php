@if($module_page->isType('create'))
    @include(cockpit_html()->viewKey('form-content-create'), ['hide_submit' => $hide_submit ?? false, 'without' => $without ?? []])
@else
    @include(cockpit_html()->viewKey('form-content-edit'), ['hide_submit' => $hide_submit ?? false, 'without' => $without ?? []])
@endif