@if($module_page->isType('create'))
@include(cockpit_html()->viewKey('form-content-create'), ['hide_submit' => true, ])
@else
@include(cockpit_html()->viewKey('form-content-edit'), ['hide_submit' => true, ])
@endif