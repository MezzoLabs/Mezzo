@section('index_table_heading')
@include('cockpit::partials.pages.index_table_heading')
@endsection

@section('index_table_body')
@include('cockpit::partials.pages.index_table_body')
@endsection

@section('index_pagination')
@include('cockpit::partials.pages.index_pagination')
@endsection

@if(!empty($module_page->filtersView))
@include($module_page->filtersView)
@endif

        <!-- Bottom Container -->
<div class="panel panel-bordered">

    <div class="panel-heading">
        <h3>{{ $model_reflection->pluralTitle() }} (@{{ vm.totalCount }})</h3>
    </div>
    <div class="panel-body">
        <div class="progress" ng-show="vm.loading">
            <div class="progress-bar progress-bar-danger progress-bar-striped active" style="width: 100%">
                {{ trans('mezzo.general.please_wait') }}..
            </div>
        </div>

        <div class="table-responsive">
            <table class="resource-index-table table table-responsive">
                @yield('index_table_heading')
                @yield('index_table_body')
            </table>
        </div>

        @yield('index_pagination')


    </div>
</div>
<!-- Bottom Container -->