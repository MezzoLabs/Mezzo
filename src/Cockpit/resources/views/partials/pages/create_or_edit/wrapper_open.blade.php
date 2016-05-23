@if($module_page->isType('create'))
    @include('cockpit::partials.pages.create.wrapper_open')
@else
    @include('cockpit::partials.pages.edit_wrapper_open')
@endif