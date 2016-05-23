{!! cockpit_form()->open(['angular' => true]) !!}

@yield('main_panel:before')
{{ $module_page->renderSection('main_panel:before') }}

<div class="main-panel panel panel-bordered">
    <div class="panel-heading">
        @include('cockpit::partials.pages.edit_heading')
    </div>
    <div class="panel-body">
        @include(cockpit_html()->viewKey('form-content-edit'))
    </div>
</div>

@yield('main_panel:after')
{{ $module_page->renderSection('main_panel:after') }}
{!! cockpit_form()->close() !!}
