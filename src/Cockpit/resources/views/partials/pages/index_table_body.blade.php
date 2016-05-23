@section('index_table_body_cell')
    @include('cockpit::partials.pages.index_table_body_cell')
@endsection

<tbody>
<tr ng-repeat="model in vm.getPagedModels() track by $index">
    <td>
                            <span class="locked-for-user" title="Locked by @{{ vm.lockedBy(model) }}"
                                  ng-show="vm.isLocked(model)"><i class="ion-ios-locked"></i></span>
    </td>
    <td>
        <input type="checkbox" ng-model="model._meta.selected">
    </td>
    <td>
        <a href="" ng-if="vm.displayAsLink(true, model)" class="disabled" title="ID: @{{ model.id }}"
           ng-click="vm.editId(model.id)"><i
                    class="ion-eye"></i></a>
    </td>
    <td ng-repeat="value in vm.getModelValues(model) track by $index">
        @yield('index_table_body_cell')
    </td>
</tr>
</tbody>