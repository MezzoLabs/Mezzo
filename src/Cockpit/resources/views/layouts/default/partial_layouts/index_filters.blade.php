<div class="panel panel-bordered panel-collapsible">
    <div class="panel-heading">
        <h3 class="panel-title">
            <a data-toggle="collapse" data-target="#extra_filters"><i class="ion-funnel light-icon highlight"></i><span>
                    {{ trans_choice('mezzo.general.filter', 2) }}
                </span></a>
        </h3>
    </div>

    <div id="extra_filters" class="panel-collapse collapse">
        <form name="vm.filtersForm" class="index__filters_form">
            <div class="panel-body">

                @yield('filters_form')
            </div>
            <div class="panel-footer text-right">
                <button type="button" class="btn btn-secondary" ng-click="vm.applyScopes($event)">
                    <i class="ion-funnel"></i> {{ trans('mezzo.general.do_filter') }}
                </button>
            </div>
        </form>
    </div>

</div>