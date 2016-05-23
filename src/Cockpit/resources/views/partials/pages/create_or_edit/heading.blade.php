@if($module_page->isType('create'))
    @include('cockpit::partials.pages.create_heading')
@else
    @include('cockpit::partials.pages.edit_heading')
@endif