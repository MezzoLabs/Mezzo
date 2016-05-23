<!-- Top Container -->
<div class="panel panel-bordered">
    <div class="panel-body">
        <!-- Search -->
        <input ng-if="vm.useSearch()" type="search" class="form-control pull-right"
               style="display: inline-block; width: 200px"
               placeholder="{{ Lang::get('mezzo.general.search') }}" ng-model="vm.searchText">
        <!-- Search -->

        @if($module_page->sibling('create') && $module_page->sibling('create')->isVisible())
            <a href="{{ $module_page->sibling('create')->url() }}" class="btn btn-primary">
                <span class="ion-plus"></span> {{ Lang::get('mezzo.general.add_new') }}
            </a>
            @endif

            {{--
            <button type="button" class="btn btn-default" ng-disabled="!vm.canEdit()" ng-click="vm.duplicate()">
                <span class="ion-ios-copy"></span> {{ Lang::get('mezzo.general.duplicate') }}</button>
                --}}

            @if($module_page->model()->canBeDeletedBy())
                    <!-- Delete -->
            <button type="button" class="btn btn-default" ng-disabled="!vm.canRemove()" ng-click="vm.remove()">
                <span class="ion-trash-b"></span> {{ Lang::get('mezzo.general.delete') }}
                <span class="badge" ng-bind="vm.countSelected()"></span>
            </button>
            <!-- Delete -->
        @endif

    </div>
</div>
<!-- Top Container -->